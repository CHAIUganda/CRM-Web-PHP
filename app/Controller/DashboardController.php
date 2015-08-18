<?php
require(APP . 'Vendor/autoload.php');
use League\Csv\Writer;
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
        if(!empty($_GET["export"])){
            $this->export($_GET["export"]);
            exit();
        }

        $this->set("detailer_visits", $this->average_visits_by_detailers("all"));
        $this->set("zinc_stats", $this->zinc_percentage_availability());
        $this->set("zinc_price", $this->median_zinc_price());
        $this->set("ors_price", $this->median_ors_price());
	}
    public function export($export){
        switch ($export) {
            case 'average_daily':
                $this->exportCSV($this->average_visits_by_detailers_export("all"));
                break;
            case 'zinc_availability':
                $this->formatExport($this->zinc_percentage_availability_export());
                break;
            case 'zinc_price':
                $this->formatExport($this->median_zinc_price_export());
                break;
            case 'ors_price':
                $this->formatExport($this->median_ors_price_export());
                break;
            default:
                break;
        }
    }

    public function formatExport($data){
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        
        $detailer_task = $data["detailer_task"];
        //pr($detailer_task);

        $title = array("Date", $data["title"], "Detailer Name", "Detailer ID", "Region");
        $csv->insertOne($title);

        foreach ($data["lines"] as $detailerName => $detailerData) {
            foreach ($detailerData as $date => $value) {
                $line = array();
                $line[] = $date;
                $line[] = $value;
                if (!empty($detailer_task[$detailerName]["user.name"])) {
                    $line[] = $detailer_task[$detailerName]["user.name"];
                } else {
                    $line[] = $detailer_task[$detailerName]["user.username"];
                }
                
                $line[] = $detailer_task[$detailerName]["user_id"];
                $line[] = $detailer_task[$detailerName]["rg.name"];

                $csv->insertOne($line);
            }
        }
        $csv->output('report.csv');
        die;
    }
    public function exportCSV($lines){
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        foreach ($lines as $line) {
            $csv->insertOne($line);
        }
        
        $csv->output('report.csv');
        die;
    }

    public function availability(){
        $this->set("nZincStats", $this->avail_nzinc_ors_avail());
        $this->set("rZincStats", $this->avail_rzinc_ors_avail());
    }

    public function price(){
        set_time_limit(0);
        $this->set("zinc_price_change", $this->percentagePriceChange("zinc"));
        $this->set("ors_price_change", $this->percentagePriceChange("ors"));
        $this->set("regional_zinc_price", $this->average_regional_zinc_price());
        $this->set("regional_ors_price", $this->average_regional_ors_price());

        $this->set("zinc_price", $this->median_zinc_price());
        $this->set("ors_price", $this->median_ors_price());
    }

    public function productivity(){
        set_time_limit(0);
        $this->set("average_daily_visits", $this->average_daily_visits());
        $this->set("average_task_completion", $this->average_task_completion());

        $this->set("weekly_visits", $this->dweekly_visits());
        $this->set("rtask_completion", $this->rtask_completion());
        $this->set("detailer_visits", $this->average_visits_by_detailers("diarrhoea"));
        $this->set("dtask_completion", $this->dtask_completion());
        $this->set("detailers", $this->detailers());
    }
    public function average_daily_visits(){
        $classification = 1;
        $dt = new DateTime();
        //$thismonth_range = $this->getTimeRange($classification, $dt->format("n"));
        //$lastmonth_range = $this->getTimeRange($classification, (intval($dt->format("n")) - 1));
        $thismonth_range = $this->getTimeRange($classification, 3);
        $lastmonth_range = $this->getTimeRange($classification, 2);

        $tasks = $this->runNeoQuery("MATCH (task:`DetailerTask`) where task.completionDate > ". $lastmonth_range[0] .
            " AND task.completionDate < " . $thismonth_range[1] . " RETURN distinct task.uuid, task.completionDate, task.status");

        $res = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));
            $task["day"] = $dt->format("j");

            if(empty($res[$task["month"]][$task["day"]])){
                $res[$task["month"]][$task["day"]] = 0;
            }
            $res[$task["month"]][$task["day"]]++;
        }
        $data = array();
        foreach($res as $month=>$values){
            foreach ($values as $selling_price) {
                $data[$month][] = $selling_price;
            }
        }
        $res = array("February"=>0, "March"=>0);
        foreach ($data as $month => $info) {
            $res[$month] = $this->calculate_average($info);
        }
        //$lastMonth = $dt->format("F", strtotime("first day of previous month"));
        //$thisMonth = $dt->format("F");
        $lastMonth = "February";
        $thisMonth = "March";

        if($res[$lastMonth] == 0){
            $res["change"] = 0;
        } else {
            $res["change"] = round(($res[$lastMonth] - $res[$thisMonth])/$res[$lastMonth], 2);
        }

        return $res;
    }

    public function average_task_completion(){
        $classification = 1;
        $dt = new DateTime();
        //$thismonth_range = $this->getTimeRange($classification, $dt->format("n"));
        //$lastmonth_range = $this->getTimeRange($classification, (intval($dt->format("n")) - 1));
        $thismonth_range = $this->getTimeRange($classification, 3);
        $lastmonth_range = $this->getTimeRange($classification, 2);

        $zinc_tasks = $this->runNeoQuery("MATCH (task:`DetailerTask`) where task.completionDate > ". $lastmonth_range[0] .
            " AND task.completionDate < " . $thismonth_range[1] . "  RETURN distinct task.uuid, task.completionDate, task.status");

        $res = array();
        foreach ($zinc_tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));

            if(empty($res[$task["month"]][$task["task.status"]])){
                $res[$task["month"]][$task["task.status"]] = 0;
            }
            $res[$task["month"]][$task["task.status"]]++;
        }
        
        //$lastMonth = $dt->format("F", strtotime("first day of previous month"));
        //$thisMonth = $dt->format("F");
        $lastMonth = "February";
        $thisMonth = "March";

        if($res[$lastMonth]["complete"] == 0){
            $res["change"] = 0;
        } else {
            $res["change"] = round(($res[$thisMonth]["complete"] - $res[$lastMonth]["complete"])/$res[$lastMonth]["complete"], 2);
        }

        return $res;
    }

    public function percentagePriceChange($drug){
        $classification = 1;
        $dt = new DateTime();
        //$thismonth_range = $this->getTimeRange($classification, $dt->format("n"));
        //$lastmonth_range = $this->getTimeRange($classification, (intval($dt->format("n")) - 1));
        $thismonth_range = $this->getTimeRange($classification, 3);
        $lastmonth_range = $this->getTimeRange($classification, 2);

        $zinc_tasks = $this->runNeoQuery("MATCH (task:`DetailerTask`) where task.completionDate > ". $lastmonth_range[0] .
            " AND task.completionDate < " . $thismonth_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock) 
            where stock.category = \"$drug\" RETURN distinct task.uuid, task.completionDate, 
            stock.uuid, stock.category, stock.stockLevel, stock.sellingPrice");

        $res = array();
        foreach ($zinc_tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));

            if($task["stock.sellingPrice"] > 0){
                $res[$task["month"]][$task["task.uuid"]] = $task["stock.sellingPrice"];
            }
        }

        $data = array();
        foreach($res as $month=>$values){
            foreach ($values as $selling_price) {
                $data[$month][] = $selling_price;
            }
        }
        $res = array("February"=>0, "March"=>0);
        foreach ($data as $month => $info) {
            $res[$month] = $this->calculate_average($info);
        }
        
        //$lastMonth = $dt->format("F", strtotime("first day of previous month"));
        //$thisMonth = $dt->format("F");
        $lastMonth = "February";
        $thisMonth = "March";

        if($res[$lastMonth] == 0){
            $res["change"] = 0;
        } else {
            $res["change"] = round(($res[$lastMonth] - $res[$thisMonth])/$res[$lastMonth], 2);
        }

        return $res;
    }
    public function detailers(){
        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t) match t<-[:`USER_TERRITORY`]-(user)
            match (user)-[:`HAS_ROLE`]->role where role.authority = \"ROLE_DETAILER\" return  user.username as username, id(user) as user_id limit 1000");

        return $tasks;
    }

    public function dweekly_visits(){
        // Get filters
        @$classification = $_GET['weeklyVisitClassification'];
        @$period = $_GET['weeklyDailyVisitsPeriod'];
        @$detId = $_GET['detId'];
        $date_range = $this->getTimeRange($classification, $period);
        
        $det_filter = "";
        if (!empty($detId) || $detId != 0) {
            $det_filter = "id(user) = " . $detId . " and ";
        }

        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match cust-[:`IN_SEGMENT`]->seg
            match task-[:`COMPLETED_TASK`]-(user) where $det_filter task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, user.username, seg.name");
        
        $res = array();

        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));
            $task["day_of_week"] = $dt->format("l");

            if (!isset($res[$task["day_of_week"]])) {
                $res[$task["day_of_week"]] = array();
            }
            if(empty($res[$task["day_of_week"]][$task["seg.name"]])){
                $res[$task["day_of_week"]][$task["seg.name"]] = array();
            }
            
            $res[$task["day_of_week"]][$task["seg.name"]][$task["task.uuid"]] = 1;
        }

        $segments = array("A"=>0,"B"=>0,"C"=>0,"D"=>0);
        $stockAvailabilityStats = array("Monday"=>$segments, "Tuesday"=>$segments, "Wednesday"=>$segments,
         "Thursday"=>$segments, "Friday"=>$segments, "Saturday"=>$segments, "Sunday"=>$segments);
        foreach ($res as $username => $monthData) {
            if(!isset($stockAvailabilityStats[$username])){
                $stockAvailabilityStats[$username] = array("A"=>0,"B"=>0,"C"=>0,"D"=>0);
            }

            foreach($monthData as $month => $data){
                $stockAvailabilityStats[$username][$month] = count($res[$username][$month]);
            }
        }

        return $stockAvailabilityStats;
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

    function zinc_percentage_availability_export(){
        // Get filters
        @$classification = $_GET['orsAvailClassification'];
        @$period = $_GET['zincPercent'];

        $date_range = $this->getTimeRange($classification, $period);

        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]-(stock) where stock.category = 
            \"zinc\" match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) return task.uuid, task.description, task.completionDate, user.username, user.name, id(user) as user_id,
            stock.uuid, stock.category, stock.stockLevel, rg.name ");

        $res = array();
        $detailer_task = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["day"] = $dt->format("M. j, Y");

            if (!isset($res[$task["user.username"]])) {
                $res[$task["user.username"]] = array();
                $res[$task["user.username"]] = array();
            }

            $res[$task["user.username"]][$task["day"]][$task["task.uuid"]] = 0;
            if ($task["stock.stockLevel"] > 0) {
                $res[$task["user.username"]][$task["day"]][$task["task.uuid"]] = 1;
            }

            $detailer_task[$task["user.username"]] = $task;
        }

        $stockAvailabilityStats = array();
        foreach ($res as $username => $monthData) {
            if(!isset($stockAvailabilityStats[$username])){
                $stockAvailabilityStats[$username] = array();
            }

            foreach($monthData as $month => $data){
                $stockAvailabilityStats[$username][$month] = $this->calculate_positive_percentage($res[$username][$month]);
            }
        }

        return array("lines"=>$stockAvailabilityStats, "detailer_task"=>$detailer_task, "title"=>"Zinc Availability");
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

    function average_visits_by_detailers_export(){
        @$classification = $_GET['visitClassification'];
        @$period = $_GET['dailyVisitsPeriod'];

        $date_range = $this->getTimeRange($classification, $period);
        

        $tasks = $this->runNeoQuery("MATCH (task:`DetailerTask`) where task.completionDate > ". $date_range[0] .
            " AND task.completionDate < " . $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock) 
            match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t)
            where stock.category = \"zinc\" match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) RETURN distinct task.uuid, task.description, task.completionDate, id(user) as user_id,
            user.name, user.username, t.name, rg.name");
        //echo count($tasks);
        //pr($tasks);
        $res = array();
        $detailer_task = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $completionDate = $dt->format("M. j, Y");
            if (!isset($res[$completionDate])) {
                $res[$completionDate] = array();
            }

            if(!isset($res[$completionDate][$task["user.username"]])){
                $res[$completionDate][$task["user.username"]] = array();
            }
            
            $res[$completionDate][$task["user.username"]][] = $task["task.uuid"];
            $detailer_task[$task["user.username"]] = $task;
        }

        $lines = array();
        $titles = array("Date", "Detailer Name", "Detailer ID",  "Region", "Visits #");
        $lines[] = $titles;

        foreach ($res as $date => $moredata) {
            foreach ($moredata as $detailerName => $visits) {
                $line = array();
                $task_info = $detailer_task[$detailerName];

                $line[] = $date;
                if (!empty($task_info["user.name"])) {
                    $line[] = $task_info["user.name"];
                } else {
                    $line[] = $task_info["user.username"];
                }
                $line[] = $task_info["user_id"];
                $line[] = $task_info["rg.name"];
                $line[] = count($visits);
                $lines[] = $line;
            }
        }

        return $lines;
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

    function median_zinc_price_export(){
        @$classification = $_GET['zincClassification'];
        @$period = $_GET['zincPrice'];

        $date_range = $this->getTimeRange($classification, $period);
        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock) where stock.category = 
            \"zinc\"  return task.uuid, task.description, task.completionDate, user.username, id(user) as user_id,
             user.name, stock.uuid, stock.category, stock.stockLevel, stock.sellingPrice, rg.name");
        $res[] = array();
        $detailer_task = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["day"] = $dt->format("M. j, Y");

            if (!isset($res[$task["user.username"]])) {
                $res[$task["user.username"]] = array();
            }

            if(!isset($res[$task["user.username"]][$task["day"]])){
                $res[$task["user.username"]][$task["day"]] = array();
            }
            if (!empty($task["stock.sellingPrice"])) {
                $res[$task["user.username"]][$task["day"]][] = $task["stock.sellingPrice"];
            }
            $detailer_task[$task["user.username"]] = $task;
        }
        
        $stats = array();
        foreach ($res as $detName=>$monthData) {
            if(!isset($stats[$detName])){
                $stats[$detName] = array();
            }

            foreach($monthData as $month => $data){
                $stats[$detName][$month] = $this->calculate_median($res[$detName][$month]);
            }
        }
        unset($stats[0]);

        return array("lines"=>$stats, "detailer_task"=>$detailer_task, "title"=>"Average Zinc Price");
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

    function median_ors_price_export(){
        @$classification = $_GET['orsClassification'];
        @$period = $_GET['ORSPrice'];

        $date_range = $this->getTimeRange($classification, $period);

        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock) where stock.category = 
            \"ors\" return task.uuid, task.description, task.completionDate, user.username, user.name, id(user) as user_id,
             stock.uuid, stock.category, stock.stockLevel, stock.sellingPrice, rg.name");

        $res[] = array();
        $detailer_task = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["day"] = $dt->format("M. j, Y");

            if (!isset($res[$task["user.username"]])) {
                $res[$task["user.username"]] = array();
            }

            if(!isset($res[$task["user.username"]][$task["day"]])){
                $res[$task["user.username"]][$task["day"]] = array();
            }
            if (!empty($task["stock.sellingPrice"])) {
                $res[$task["user.username"]][$task["day"]][] = $task["stock.sellingPrice"];
            }
            $detailer_task[$task["user.username"]] = $task;
        }
        
        $stats = array();
        foreach ($res as $detName=>$monthData) {
            if(!isset($stats[$detName])){
                $stats[$detName] = array();
            }

            foreach($monthData as $month => $data){
                $stats[$detName][$month] = $this->calculate_median($res[$detName][$month]);
            }
        }
        unset($stats[0]);

        return array("lines"=>$stats, "detailer_task"=>$detailer_task, "title"=>"Average ORS Price");
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
        if(!in_array($classification, array(1,2))){
            $classification = 2;
        }

        if(!in_array($period, array(1,2,3,4,5,6,7,8,9,10,11,12))){
            $month = date("n");
            $period = ceil($month/3);
        }

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
