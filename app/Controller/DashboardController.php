<?php
require(APP . 'Vendor/autoload.php');

App::uses('AppController', 'Controller');
/**
 * Dashboard Controller
 *
 * @property Dashboard $Dashboard
 */
class DashboardController extends AppController {

	var $client;
/**
 * index method
 *
 * @return void
 */
	public function index() {
        $this->set("detailer_visits", $this->average_visits_by_detailers());
        $this->set("zinc_stats", $this->zinc_percentage_availability());
        $this->set("zinc_price", $this->median_zinc_price());
        $this->set("ors_price", $this->median_ors_price());
	}

    public function availability(){
        $this->set("nZincStats", $this->avail_nzinc_ors_avail());
        $this->set("rZincStats", $this->avail_rzinc_ors_avail());
    }

    public function price(){
        set_time_limit(0);
        $this->set("regional_zinc_price", $this->average_regional_zinc_price());
        $this->set("regional_ors_price", $this->average_regional_ors_price());

        $this->set("zinc_price", $this->median_zinc_price());
        $this->set("ors_price", $this->median_ors_price());
    }

    public function productivity(){
        set_time_limit(0);
        $this->set("dv", $this->average_visits_by_detailers("all"));
        $this->set("rtask_completion", $this->rtask_completion());
        $this->set("detailer_visits", $this->average_visits_by_detailers("diarrhoea"));
        $this->set("dtask_completion", $this->dtask_completion());
    }

    public function rtask_completion(){
        // Get filters
        @$classification = $_GET['orsAvailClassification'];
        @$period = $_GET['zincPercent'];

        $date_range = $this->getTimeRange($classification, $period);

        $tasks = $this->runNeoQuery("MATCH (task:`Task`) where task.completionDate > ". $date_range[0] .
            " AND task.completionDate < " . $date_range[1] . " 
            match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t)
            match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) RETURN distinct task.uuid, task.description, task.completionDate, task.status, user.username, 
            t.name, rg.name");
        
        $res = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));

            if (!isset($res[$task["rg.name"]])) {
                $res[$task["rg.name"]] = array();
            }

            $res[$task["rg.name"]][$task["task.status"]][$task["task.uuid"]] = 0;
        }

        $stockAvailabilityStats = array();
        foreach ($res as $username => $monthData) {
            if(!isset($stockAvailabilityStats[$username])){
                $stockAvailabilityStats[$username] = array("complete"=>0, "cancelled"=>0, "new"=>0);
            }

            $total = 0;
            foreach($monthData as $month => $data){
                $total += count($res[$username][$month]);
            }

            foreach($monthData as $month => $data){
                $stockAvailabilityStats[$username][$month] = (count($res[$username][$month])/$total) * 100;
            }
        }

        return $stockAvailabilityStats;
    }

    public function dtask_completion(){
        // Get filters
        @$classification = $_GET['orsAvailClassification'];
        @$period = $_GET['zincPercent'];

        $date_range = $this->getTimeRange($classification, $period);

        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]->(t) match 
            t<-[:`SC_IN_TERRITORY`]-(sc) match sc<-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]->(task) match 
            task<-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, task.status, user.username");
        
        $res = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));

            if (!isset($res[$task["user.username"]])) {
                $res[$task["user.username"]] = array();
            }

            $res[$task["user.username"]][$task["task.status"]][$task["task.uuid"]] = 0;
        }

        $stockAvailabilityStats = array();
        foreach ($res as $username => $monthData) {
            if(!isset($stockAvailabilityStats[$username])){
                $stockAvailabilityStats[$username] = array("complete"=>0, "cancelled"=>0, "new"=>0);
            }

            $total = 0;
            foreach($monthData as $month => $data){
                $total += count($res[$username][$month]);
            }

            foreach($monthData as $month => $data){
                $stockAvailabilityStats[$username][$month] = (count($res[$username][$month])/$total) * 100;
            }
        }

        return $stockAvailabilityStats;
    }

    function avail_nzinc_ors_avail(){
        // Get filters
        @$classification = $_GET['nOrsAvailClassification'];
        @$period = $_GET['nZincPercent'];

        $date_range = $this->getTimeRange($classification, $period);

        $tasks = $this->runNeoQuery("MATCH (task:`DetailerTask`) where task.completionDate > ". $date_range[0] .
            " AND task.completionDate < " . $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock) 
            match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t)
            where stock.category = \"zinc\" match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) RETURN distinct task.uuid, task.description, task.completionDate, user.username, 
            stock.uuid, stock.category, stock.stockLevel, t.name, rg.name");

        $res = array();
        //pr($tasks);
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));

            if (!isset($res[$task["rg.name"]])) {
                $res[$task["rg.name"]] = array();
            }

            if($date_range["classification"] == 1){
                $res[$task["rg.name"]][$task["week"]][$task["task.uuid"]] = 0;
                if ($task["stock.stockLevel"] > 0) {
                    $res[$task["rg.name"]][$task["week"]][$task["task.uuid"]] = 1;
                }
            } else {
                $res[$task["rg.name"]][$task["month"]][$task["task.uuid"]] = 0;
                if ($task["stock.stockLevel"] > 0) {
                    $res[$task["rg.name"]][$task["month"]][$task["task.uuid"]] = 1;
                }
            }
        }

        $stockAvailabilityStats = array();
        foreach ($res as $username => $monthData) {
            if(!isset($stockAvailabilityStats[$username])){
                $stockAvailabilityStats[$username] = array();
                $stockAvailabilityStats[$username] = $this->getMonths($classification, $period);
            }

            foreach($monthData as $month => $data){
                $stockAvailabilityStats[$username][$month] = $this->calculate_positive_percentage($res[$username][$month]);
            }
        }
        return $stockAvailabilityStats;
    }

    function avail_rzinc_ors_avail(){
        // Get filters
        @$classification = $_GET['rOrsAvailClassification'];
        @$period = $_GET['rZincPercent'];

        $date_range = $this->getTimeRange($classification, $period);

        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]->(t) match 
            t<-[:`SC_IN_TERRITORY`]-(sc) match sc<-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]->(task) match 
            task<-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock) where stock.category = 
            \"ors\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category, 
            stock.stockLevel");
        
        $res = array();

        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));

            if (!isset($res[$task["user.username"]])) {
                $res[$task["user.username"]] = array();
                $res[$task["user.username"]] = array();
            }

            if($date_range["classification"] == 1){
                $res[$task["user.username"]][$task["week"]][$task["task.uuid"]] = 0;
                if ($task["stock.stockLevel"] > 0) {
                    $res[$task["user.username"]][$task["week"]][$task["task.uuid"]] = 1;
                }
            } else {
                $res[$task["user.username"]][$task["month"]][$task["task.uuid"]] = 0;
                if ($task["stock.stockLevel"] > 0) {
                    $res[$task["user.username"]][$task["month"]][$task["task.uuid"]] = 1;
                }
            }
        }

        $stockAvailabilityStats = array();
        foreach ($res as $username => $monthData) {
            if(!isset($stockAvailabilityStats[$username])){
                $stockAvailabilityStats[$username] = array();
                $stockAvailabilityStats[$username] = $this->getMonths($classification, $period);
            }

            foreach($monthData as $month => $data){
                $stockAvailabilityStats[$username][$month] = $this->calculate_positive_percentage($res[$username][$month]);
            }
        }
        return $stockAvailabilityStats;
    }

	function zinc_percentage_availability(){
        // Get filters
        @$classification = $_GET['orsAvailClassification'];
        @$period = $_GET['zincPercent'];

		$date_range = $this->getTimeRange($classification, $period);

        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]-(stock) where stock.category = 
            \"zinc\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
            stock.stockLevel ");
        
        $res = array();

        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));

        	if (!isset($res[$task["user.username"]])) {
        		$res[$task["user.username"]] = array();
        		$res[$task["user.username"]] = array();
        	}

            if($date_range["classification"] == 1){
                $res[$task["user.username"]][$task["week"]][$task["task.uuid"]] = 0;
                if ($task["stock.stockLevel"] > 0) {
                    $res[$task["user.username"]][$task["week"]][$task["task.uuid"]] = 1;
                }
            } else {
                $res[$task["user.username"]][$task["month"]][$task["task.uuid"]] = 0;
                if ($task["stock.stockLevel"] > 0) {
                    $res[$task["user.username"]][$task["month"]][$task["task.uuid"]] = 1;
                }
            }
        }

        $stockAvailabilityStats = array();
        foreach ($res as $username => $monthData) {
            if(!isset($stockAvailabilityStats[$username])){
                $stockAvailabilityStats[$username] = array();
                $stockAvailabilityStats[$username] = $this->getMonths($classification, $period);
            }

            foreach($monthData as $month => $data){
                $stockAvailabilityStats[$username][$month] = $this->calculate_positive_percentage($res[$username][$month]);
            }
        }
        return $stockAvailabilityStats;
	}

	function average_visits_by_detailers($type){
        // Get filters
        @$classification = $_GET['visitClassification'];
        @$period = $_GET['dailyVisitsPeriod'];

        if(!in_array($classification, array(1,2))){
            $classification = 2;
        }

        if(!in_array($period, array(1,2,3,4,5,6,7,8,9,10,11,12))){
            $month = date("n");
            $period = ceil($month/3);
        }

        $date_range = array();
        if ($classification == 1) {
            $date_range = $this->getTimesForMonth($period);
        } else if ($classification == 2) {
            $date_range = $this->getTimesForQuarter($period);
        }

		$this->client = new Everyman\Neo4j\Client();
        $this->client->getTransport()->setAuth("neo4j", "neo4j");

        $qs = "";
        if ($type == "diarrhoea") {
            $qs = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task:`DetailerTask`) match 
            (task)-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return distinct task.uuid, task.description, task.completionDate, user.username";
        } else {
            $qs = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return distinct task.uuid, task.description, task.completionDate, user.username";
        }
        

        $query = new Everyman\Neo4j\Cypher\Query($this->client, $qs);
        $results = $query->getResultSet();

        $stats = array();
        $res = array();
        foreach ($results as $result) {
        	$item = array();
        	$columns = $result->columns();
        	foreach ($columns as $column) {
        		$item[$column] = $result[$column];
        		if (empty($result[$column])) {
        			$item[$column] = "anon";
        		}
        		if ($column == "task.completionDate") {
        			$epoch = floor($result[$column]/1000);
					$dt = new DateTime("@$epoch");
					$item[$column] = $dt->format('Y-m-d');
                    $item["month"] = $dt->format("F");
                    $item["week"] = $this->getWeekOfMonth($dt->format("j"));
        		}
        	}

        	if (!isset($stats[$item["user.username"]])) {
        		$stats[$item["user.username"]] = array();
			}
			
            if($classification == 1){
                if (!isset($stats[$item["user.username"]][$item["week"]])) {
                    $stats[$item["user.username"]][$item["week"]] = array();
                }
                if(!isset($stats[$item["user.username"]][$item["week"]][$item["task.completionDate"]])){
                    $stats[$item["user.username"]][$item["week"]][$item["task.completionDate"]] = 1;
                } else {
                    $stats[$item["user.username"]][$item["week"]][$item["task.completionDate"]]++;
                }
            } else {
                if (!isset($stats[$item["user.username"]][$item["month"]])) {
                    $stats[$item["user.username"]][$item["month"]] = array();
                }
                if(!isset($stats[$item["user.username"]][$item["month"]][$item["task.completionDate"]])){
                    $stats[$item["user.username"]][$item["month"]][$item["task.completionDate"]] = 1;
                } else {
                    $stats[$item["user.username"]][$item["month"]][$item["task.completionDate"]]++;
                }
            }

        	$res[] = $item;
        }

        unset($stats["anon"]);
        $medians = array();
        foreach ($stats as $detName => $detData) {
            foreach ($detData as $month => $values) {
                if(!isset($medians[$detName])){
                    $medians[$detName] = $this->getMonths($classification, $period);
                }
                $medians[$detName][$month] = $this->calculate_average(array_values($values));
            }
        }
        return $medians;
	}
    function average_regional_zinc_price(){
        @$classification = $_GET['visitClassification'];
        @$period = $_GET['dailyVisitsPeriod'];

        $date_range = $this->getTimeRange($classification, $period);
        

        $tasks = $this->runNeoQuery("MATCH (task:`DetailerTask`) where task.completionDate > ". $date_range[0] .
            " AND task.completionDate < " . $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock) 
            match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t)
            where stock.category = \"zinc\" match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) RETURN distinct task.uuid, task.description, task.completionDate, user.username, 
            stock.uuid, stock.category, stock.stockLevel, stock.sellingPrice, t.name, rg.name");

        $res[] = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));

            if (!isset($res[$task["rg.name"]])) {
                $res[$task["rg.name"]] = array();
            }

            if($date_range["classification"] == 1){
                if(!isset($res[$task["rg.name"]][$task["week"]])){
                    $res[$task["rg.name"]][$task["week"]] = array();
                }
                if (!empty($task["stock.sellingPrice"])) {
                    $res[$task["rg.name"]][$task["week"]][] = $task["stock.sellingPrice"];
                }
            } else {
                if(!isset($res[$task["rg.name"]][$task["month"]])){
                    $res[$task["rg.name"]][$task["month"]] = array();
                }
                if (!empty($task["stock.sellingPrice"])) {
                    $res[$task["rg.name"]][$task["month"]][] = $task["stock.sellingPrice"];
                }
            }
            
        }
        
        $stats = array();
        foreach ($res as $detName=>$monthData) {
            if(!isset($stats[$detName])){
                $stats[$detName] = array();
                $stats[$detName] = $this->getMonths($classification, $period);
            }

            foreach($monthData as $month => $data){
                $stats[$detName][$month] = $this->calculate_median($res[$detName][$month]);
            }
        }
        unset($stats[0]);

        return $stats;
    }

    function average_regional_ors_price(){
        @$classification = $_GET['orsAvailClassification'];
        @$period = $_GET['zincPercent'];

        $date_range = $this->getTimeRange($classification, $period);
        $tasks = $this->runNeoQuery("MATCH (task:`DetailerTask`) where task.completionDate > ". $date_range[0] .
            " AND task.completionDate < " . $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock) 
            match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t)
            where stock.category = \"ors\" match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) RETURN distinct task.uuid, task.description, task.completionDate, user.username, 
            stock.uuid, stock.category, stock.stockLevel, stock.sellingPrice, t.name, rg.name");

        $res[] = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));

            if (!isset($res[$task["rg.name"]])) {
                $res[$task["rg.name"]] = array();
            }

            if($date_range["classification"] == 1){
                if(!isset($res[$task["rg.name"]][$task["week"]])){
                    $res[$task["rg.name"]][$task["week"]] = array();
                }
                if (!empty($task["stock.sellingPrice"])) {
                    $res[$task["rg.name"]][$task["week"]][] = $task["stock.sellingPrice"];
                }
            } else {
                if(!isset($res[$task["rg.name"]][$task["month"]])){
                    $res[$task["rg.name"]][$task["month"]] = array();
                }
                if (!empty($task["stock.sellingPrice"])) {
                    $res[$task["rg.name"]][$task["month"]][] = $task["stock.sellingPrice"];
                }
            }
        }
        
        $stats = array();
        foreach ($res as $detName=>$monthData) {
            if(!isset($stats[$detName])){
                $stats[$detName] = array();
                $stats[$detName] = $this->getMonths($classification, $period);
            }

            foreach($monthData as $month => $data){
                $stats[$detName][$month] = $this->calculate_median($res[$detName][$month]);
            }
        }
        unset($stats[0]);

        return $stats;
    }
	function median_zinc_price(){
        @$classification = $_GET['zincClassification'];
        @$period = $_GET['zincPrice'];

        $date_range = $this->getTimeRange($classification, $period);
        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock) where stock.category = 
            \"zinc\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
            stock.stockLevel, stock.sellingPrice");
        $res[] = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));

        	if (!isset($res[$task["user.username"]])) {
        		$res[$task["user.username"]] = array();
        	}

            if($date_range["classification"] == 1){
                if(!isset($res[$task["user.username"]][$task["week"]])){
                    $res[$task["user.username"]][$task["week"]] = array();
                }
                if (!empty($task["stock.sellingPrice"])) {
                    $res[$task["user.username"]][$task["week"]][] = $task["stock.sellingPrice"];
                }
            } else {
                if(!isset($res[$task["user.username"]][$task["month"]])){
                    $res[$task["user.username"]][$task["month"]] = array();
                }
                if (!empty($task["stock.sellingPrice"])) {
                    $res[$task["user.username"]][$task["month"]][] = $task["stock.sellingPrice"];
                }
            }
            
        }
        
        $stats = array();
        foreach ($res as $detName=>$monthData) {
            if(!isset($stats[$detName])){
                $stats[$detName] = array();
                $stats[$detName] = $this->getMonths($classification, $period);
            }

            foreach($monthData as $month => $data){
                $stats[$detName][$month] = $this->calculate_median($res[$detName][$month]);
            }
        }
        unset($stats[0]);

        return $stats;
	}

	function median_ors_price(){
        @$classification = $_GET['orsClassification'];
        @$period = $_GET['ORSPrice'];

        $date_range = $this->getTimeRange($classification, $period);

		$tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock) where stock.category = 
            \"ors\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category, stock.stockLevel
            , stock.sellingPrice");

        $res[] = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
           // $item[$column] = $dt->format('Y-m-d');
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));

        	if (!isset($res[$task["user.username"]])) {
                $res[$task["user.username"]] = array();
            }

            if($date_range["classification"] == 1){
                if(!isset($res[$task["user.username"]][$task["week"]])){
                    $res[$task["user.username"]][$task["week"]] = array();
                }
                if (!empty($task["stock.sellingPrice"])) {
                    $res[$task["user.username"]][$task["week"]][] = $task["stock.sellingPrice"];
                }
            } else {
                if(!isset($res[$task["user.username"]][$task["month"]])){
                    $res[$task["user.username"]][$task["month"]] = array();
                }
                if (!empty($task["stock.sellingPrice"])) {
                    $res[$task["user.username"]][$task["month"]][] = $task["stock.sellingPrice"];
                }
            }
        }
        
        $stats = array();
        foreach ($res as $detName=>$monthData) {
            if(!isset($stats[$detName])){
                $stats[$detName] = array();
                if ($classification == 1) {
                    $stats[$detName] = $this->getWeeks();
                } else {
                    $stats[$detName] = $this->getMonths($classification, $period);
                }
                
            }

            foreach($monthData as $month => $data){
                $stats[$detName][$month] = $this->calculate_median($res[$detName][$month]);
            }
        }
        unset($stats[0]);
        return $stats;
	}

	function calculate_median($arr) {
		sort($arr);
	    $count = count($arr); //total numbers in array

	    if ($count == 0) {
	    	return 0;
	    }
	    $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
	    if($count % 2) { // odd number, middle is the median
	        $median = $arr[$middleval];
	    } else { // even number, calculate avg of 2 medians
	        $low = $arr[$middleval];
	        $high = $arr[$middleval+1];
	        $median = (($low+$high)/2);
	    }
	    return $median;
	}

	function calculate_average($arr) {
	    $count = count($arr);
	    $total = 0;
	    foreach ($arr as $value) {
	        $total += $value;
	    }
	    $average = ($total/$count); // get average value
	    return ceil($average);
	}

	function calculate_positive_percentage($arr){
		$positive_count = 0;
		foreach ($arr as $val) {
			if ($val > 0) {
				$positive_count++;
			}
		}
		return @($positive_count/count($arr))*100;
	}

    function getMonths($classification = 2, $period = 1){
        $start = 1;
        $end = 13;
        if ($classification == 1) {
            $start = $period;
            $end = $period + 1;
            return array("W1"=>0, "W2"=>0, "W3"=>0, "W4"=>0);
        }

        if ($classification == 2) {
            $start = 1 + (3 * ($period - 1));
            $end = $start + 3;
        }
        $months = array();

        for ($x = $start; $x < $end; $x++) {
            $months[date('F', mktime(0, 0, 0, $x, 1))] = 0;
        }

        return $months;
    }

    function getTimesForMonth($month){
        $times  = array();
        $first_minute = mktime(0, 0, 0, $month, 1);
        $last_minute = mktime(23, 59, 0, $month, date('t', $first_minute));
        $times = array($first_minute*1000, $last_minute*1000);

        return $times;
    }

    function getTimesForQuarter($quarter){
        $results = array();
        switch ($quarter) {
            case 1:
                $firstMonth = $this->getTimesForMonth(1);
                $lastMonth = $this->getTimesForMonth(3);
                $results[0] = $firstMonth[0];
                $results[1] = $lastMonth[1];
                break;
            case 2:
                $firstMonth = $this->getTimesForMonth(4);
                $lastMonth = $this->getTimesForMonth(6);
                $results[0] = $firstMonth[0];
                $results[1] = $lastMonth[1];
                break;
            case 3:
                $firstMonth = $this->getTimesForMonth(7);
                $lastMonth = $this->getTimesForMonth(9);
                $results[0] = $firstMonth[0];
                $results[1] = $lastMonth[1];
                break;
            case 4:
                $firstMonth = $this->getTimesForMonth(10);
                $lastMonth = $this->getTimesForMonth(12);
                $results[0] = $firstMonth[0];
                $results[1] = $lastMonth[1];
                break;
            default:
                $firstMonth = $this->getTimesForMonth(1);
                $lastMonth = $this->getTimesForMonth(3);
                $results[0] = $firstMonth[0];
                $results[1] = $lastMonth[1];
                break;
        }

        return $results;
    }

    function runNeoQuery($query){
        $this->client = new Everyman\Neo4j\Client();
        $this->client->getTransport()->setAuth("neo4j", "neo4j");

        $query = new Everyman\Neo4j\Cypher\Query($this->client, $query);
        $results = $query->getResultSet();

        $tasks = array();
        foreach ($results as $result) {
            $columns = $result->columns();
            $item = array();
            foreach ($columns as $column) {
                $item[$column] = $result[$column];
            }

            $tasks[] = $item;
        }

        return $tasks;
    }

    function getTimeRange($classification, $period){
        if(!in_array($classification, array(1,2))){
            $classification = 2;
        }

        if(!in_array($period, array(1,2,3,4,5,6,7,8,9,10,11,12))){
            $month = date("n");
            $period = ceil($month/3);
        }

        $date_range = array();
        if ($classification == 1) {
            $date_range = $this->getTimesForMonth($period);
        } else if ($classification == 2) {
            $date_range = $this->getTimesForQuarter($period);
        }
        $date_range["classification"] = $classification;

        return $date_range;
    }

    function getWeekOfMonth($day){
        $week = "W1";
        if ($day > 0 && $day < 8) {
            $week = "W1";
        } else if ($day > 7 && $day < 15) {
            $week = "W2";
        } else if ($day > 14 && $day < 22) {
            $week = "W3";
        } else {
            $week = "W4";
        }
        return $week;
    }

    function getWeeks(){
        return array("W1"=>0, "W2"=>0, "W3"=>0, "W4"=>0);
    }
}
