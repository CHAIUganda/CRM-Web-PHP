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
    var $timeLog = array();

    function beforeFilter() {
        parent::beforeFilter();
        if (in_array("ROLE_SALES_SUPERVISOR", $this->_user["User"]["roles"])) {
            $this->redirect("/sales");
        }
    }
/**
 * index method
 *
 * @return void
 */
	public function index() {
        set_time_limit(0);
        $time1 = time();
        if(!empty($_GET["export"])){
            $this->export($_GET["export"]);
            exit();
        }

        $detailer_product = @$_GET["dproduct"];
        if(empty($detailer_product)){
            $detailer_product = "ors";
        }

        $availability_product = @$_GET["productAvailability"];
        if (empty($availability_product)) {
            $availability_product = "ors";
        }

        $this->set("detailer_visits", $this->average_visits_by_detailers("all"));
        $this->set("weekly_visits", $this->dweekly_visits());
        $this->set("zinc_stats", $this->zinc_percentage_availability($availability_product));
        $this->set("zinc_price", $this->average_product_detailer_price($detailer_product));
        //$this->set("ors_price", $this->median_ors_price());

        $this->set("detailers", $this->detailers());

        $time2 = time();
        $this->timeLog["total"] = $time2 - $time1;
        $this->set("time", $this->timeLog);
	}

    public function availability(){
        $time1 = time();
        if(!empty($_GET["export"])){
            $this->export($_GET["export"]);
            exit();
        }
        
        $data = $this->availability_data();
        $availabilityData = $data["data"];

        $this->set("availabilityData", $availabilityData);
        $this->set("detailers", $this->getSupervisorDetailers());
        $this->set("districts", $this->getDistrictsAndDetailers());

        $time2 = time();
        $this->timeLog["total"] = $time2 - $time1;
        $this->set("time", $this->timeLog);
    }

    public function price(){
        set_time_limit(0);
        $time1 = time();
        if(!empty($_GET["export"])){
            $this->export($_GET["export"]);
            exit();
        }
        $regional_product = @$_GET["rproduct"];
        $detailer_product = @$_GET["dproduct"];
        if (empty($regional_product)) {
            $regional_product = "ors";
        }

        if(empty($detailer_product)){
            $detailer_product = "ors";
        }

        $this->set("zinc_price_change", $this->percentagePriceChange("zinc"));
        $this->set("ors_price_change", $this->percentagePriceChange("ors"));

        $this->set("regional_zinc_price", $this->average_regional_product_price($regional_product));
        $this->set("ors_price", $this->average_product_detailer_price($detailer_product));

        //$this->set("regional_ors_price", $this->average_regional_ors_price());

        //$this->set("zinc_price", $this->median_zinc_price());
        //$this->set("ors_price", $this->median_ors_price());
        

        $time2 = time();
        $this->timeLog["total"] = $time2 - $time1;
        $this->set("time", $this->timeLog);
    }

    public function productivity(){
        set_time_limit(0);
        $time1 = time();
        if(!empty($_GET["export"])){
            $this->export($_GET["export"]);
            exit();
        }

        $this->set("average_daily_visits", $this->average_daily_visits());
        $this->set("average_task_completion", $this->average_task_completion());

        $this->set("weekly_visits", $this->dweekly_visits());
        $this->set("rtask_completion", $this->rtask_completion());
        $this->set("detailer_visits", $this->average_visits_by_detailers("diarrhoea"));
        $this->set("dtask_completion", $this->dtask_completion());
        $this->set("task_summary", $this->task_summary());
        
        $this->set("detailers", $this->detailers());
        $this->set("weekDates", $this->getWeekDates());
        

        $time2 = time();
        $this->timeLog["total"] = $time2 - $time1;
        $this->set("time", $this->timeLog);
    }

    public function export($export){
        $regional_product = @$_GET["rproduct"];
        $detailer_product = @$_GET["dproduct"];
        $availability_product = @$_GET["productAvailability"];

        if (empty($regional_product)) {
            $regional_product = "ors";
        }

        if(empty($detailer_product)){
            $detailer_product = "ors";
        }

        if(empty($availability_product)){
            $availability_product = "ors";
        }

        switch ($export) {
            case 'average_daily':
                $this->exportCSV($this->average_visits_by_detailers_export("all"));
                break;
            case 'zinc_availability':
                $this->formatExport($this->zinc_percentage_availability_export($availability_product));
                break;
            case 'zinc_price':
                $this->formatExport($this->median_zinc_price_export());
                break;
            case 'ors_price':
                $this->formatExport($this->median_ors_price_export());
                break;
            case 'product_avail':
                $this->exportCSV($this->product_avail_export());
                break;
            case 'rzinc_avail':
                $this->availFormat($this->avail_rzinc_ors_avail_export());
                break;
            case 'task_summary':
                $this->exportCSV($this->task_summary_export());
                break;
            case 'rprice':
                $this->priceFormat($this->average_regional_product_price_export($regional_product));
                break;
            case 'dprice':
                $this->priceFormat($this->average_detailer_product_price_export($detailer_product));
                break;
            case 'dzinc_price':
                $this->formatExport($this->median_zinc_price_export());
                break;
            case 'dors_price':
                $this->formatExport($this->median_ors_price_export());
                break;
            case 'pweekly_visits':
                $this->exportCSV($this->dweekly_visits_export());
                break;
            case 'prtask_completion':
                $this->exportCSV($this->rtask_completion_export());
                break;
            case 'paverage_visits':
                $this->exportCSV($this->average_visits_by_detailers_export("diarrhoea"));
                break;
            case 'ptask_completion':
                $this->exportCSV($this->dtask_completion_export());
                break;
            default:
                break;
        }
    }

    public function priceFormat($data){
        $lines = array();
        
        $title2 = "Region";
        if (!empty($data["title2"])) {
            $title2 = $data["title2"];
        }
        $title = array("Date", $data["title"], $title2);
        $lines[] = $title;

        foreach ($data["lines"] as $detailerName => $detailerData) {
            foreach ($detailerData as $date => $value) {
                $line = array();
                $line[] = $date;
                $line[] = $value;
                $line[] = $detailerName;

                $lines[] = $line;
            }
        }
        $this->exportCSV($lines);
    }

    public function availFormat($data){
        $lines = array();
        $title = array("Date", $data["title"], "Zinc & ORS Availability");
        $lines[] = $title;

        foreach ($data["lines"] as $detailerName => $detailerData) {
            foreach ($detailerData as $date => $value) {
                $line = array();
                $line[] = $date;
                $line[] = $detailerName;
                $line[] = $value;

                $lines[] = $line;
            }
        }
        
        $this->exportCSV($lines);
    }
    public function formatExport($data){
        $detailer_task = $data["detailer_task"];
        //pr($detailer_task);

        $lines = array();
        $title = array("Date", $data["title"], "Detailer Name", "Detailer ID", "Region");
        $lines[] = $title;

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

                $lines[] = $line;
            }
        }

        $this->exportCSV($lines);
    }

    function date_sorter($a, $b){
        $date1 = 0;
        $date2 = 0;

        if (count(explode(" ", $a[0])) == 3) {
            $date1 = DateTime::createFromFormat('M. j, Y', $a[0]);
            $date2 = DateTime::createFromFormat('M. j, Y', $b[0]);
        } else {
            $date1 = DateTime::createFromFormat('F Y', $a[0]);
            $date2 = DateTime::createFromFormat('F Y', $b[0]);
        }

        return ($date1 > $date2) ? -1 : 1;
    }
    public function exportCSV($lines){
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->insertOne($lines[0]);

        $sorted_lines = array();
        if (count($lines) > 1) {
            $sorted_lines = array_slice($lines, 1);
            usort($sorted_lines, array($this, "date_sorter"));
        }
        
        foreach ($sorted_lines as $line) {
            $csv->insertOne($line);
        }
        
        $csv->output('report.csv');
        die;
    }

    public function average_daily_visits(){
        $classification = 1;
        $dt = new Datetime();
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

    public function task_summary_export(){
        // Get filters
        @$taskDetailer = $_GET['taskDetailer'];
        @$taskWeek = $_GET['taskWeek'];
        if (empty($taskWeek)) {
            $taskWeek = date("W");
        }

        $date_range = $this->getTimeRange(3, $taskWeek);
        
        $det_filter = "";
        if (!empty($taskDetailer) || $taskDetailer != 0) {
            $det_filter = "id(user) = " . $taskDetailer . " and ";
        }

        $q = "";
        if ($this->isAdmin()) {
            $q = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match cust-[:`IN_SEGMENT`]->seg
             where $det_filter task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, user.username, seg.name, task.status";
        } else {
            $q = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match cust-[:`IN_SEGMENT`]->seg
            match task-[:`COMPLETED_TASK`]-(user) where $det_filter task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, user.username, seg.name, task.status";
        }

        $tasks = $this->runNeoQuery($q);
        
        $res = array();

        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));
            $task["day_of_week"] = $dt->format("M. j, Y");

            if (!isset($res[$task["day_of_week"]])) {
                $res[$task["day_of_week"]] = array();
            }
            if(empty($res[$task["day_of_week"]][$task["task.status"]])){
                $res[$task["day_of_week"]][$task["task.status"]] = array();
            }
            
            $res[$task["day_of_week"]][$task["task.status"]][$task["task.uuid"]] = 1;
        }

        $stockAvailabilityStats = array();
        foreach ($res as $username => $monthData) {
            if(!isset($stockAvailabilityStats[$username])){
                $stockAvailabilityStats[$username] = array("complete"=>0,"new"=>0,"cancelled"=>0);
            }

            foreach($monthData as $month => $data){
                $stockAvailabilityStats[$username][$month] = count($res[$username][$month]);
            }
        }
        $lines = array();
        $lines[] = array("date", "complete", "new", "cancelled");
        foreach ($stockAvailabilityStats as $date => $data) {
            $line = array();
            $line[] = $date;
            $line[] = $data["complete"];
            $line[] = $data["new"];
            $line[] = $data["cancelled"];
            $lines[] = $line;
        }
        
        return $lines;
    }

    public function task_summary(){
        // Get filters
        @$taskDetailer = $_GET['taskDetailer'];
        @$taskWeek = $_GET['taskWeek'];
        if (empty($taskWeek)) {
            $taskWeek = date("W");
        }

        $date_range = $this->getTimeRange(3, $taskWeek);
        
        $det_filter = "";
        if (!empty($taskDetailer) || $taskDetailer != 0) {
            $det_filter = "id(user) = " . $taskDetailer . " and ";
        }

        $q = "";
        if ($this->isAdmin()) {
            $q = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match cust-[:`IN_SEGMENT`]->seg
             where $det_filter task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, user.username, seg.name, task.status";
        } else {
            $q = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match cust-[:`IN_SEGMENT`]->seg
            match task-[:`COMPLETED_TASK`]-(user) where $det_filter task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, user.username, seg.name, task.status";
        }
        $tasks = $this->runNeoQuery($q);
        
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
            if(empty($res[$task["day_of_week"]][$task["task.status"]])){
                $res[$task["day_of_week"]][$task["task.status"]] = array();
            }
            
            $res[$task["day_of_week"]][$task["task.status"]][$task["task.uuid"]] = 1;
        }

        $segments = array("complete"=>0,"new"=>0,"cancelled"=>0);
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

    function getWeekDates(){
        $year = date("Y");
        $weeks = array();
        
        for ($i = 1; $i < 53; $i++) {
            $week = sprintf("%02s", $i);
            $from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
            $weeks[] = array("start"=>$from, "number"=>$i);
        }
        
        return $weeks;
    }

    public function average_task_completion(){
        $classification = 1;
        $dt = new Datetime();
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
        $dt = new Datetime();
        //$thismonth_range = $this->getTimeRange($classification, $dt->format("n"));
        //$lastmonth_range = $this->getTimeRange($classification, (intval($dt->format("n")) - 1));
        $thismonth_range = $this->getTimeRange($classification, 3);
        $lastmonth_range = $this->getTimeRange($classification, 2);

        $zinc_tasks = $this->runNeoQuery("MATCH (task:`DetailerTask`) where task.completionDate > ". $lastmonth_range[0] .
            " AND task.completionDate < " . $thismonth_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock:`DetailerStock`) 
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
        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match (t)<-[:`USER_TERRITORY`]-(user:`User`)
            match (user)-[:`HAS_ROLE`]->(role:`Role`) where role.authority = \"ROLE_DETAILER\" return  user.username as username, id(user) as user_id limit 1000");

        return $tasks;
    }

    public function dweekly_visits_export(){
        // Get filters
        @$classification = $_GET['weeklyVisitClassification'];
        @$period = $_GET['weeklyDailyVisitsPeriod'];
        @$detId = $_GET['detId'];
        $date_range = $this->getTimeRange($classification, $period);
        
        $det_filter = "";
        if (!empty($detId) || $detId != 0) {
            $det_filter = "id(user) = " . $detId . " and ";
        }
        $q = "";
        if($this->isAdmin()){
            $q = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match cust-[:`IN_SEGMENT`]->seg
             where $det_filter task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, user.username, seg.name";
        } else {
            $q = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match cust-[:`IN_SEGMENT`]->seg
            match task-[:`COMPLETED_TASK`]-(user) where $det_filter task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, user.username, seg.name";
        }

        $tasks = $this->runNeoQuery($q);
        
        $res = array();

        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["day"] = $dt->format("M. j, Y");

            if (!isset($res[$task["day"]])) {
                $res[$task["day"]] = array();
            }
            if(empty($res[$task["day"]][$task["seg.name"]])){
                $res[$task["day"]][$task["seg.name"]] = array();
            }
            
            $res[$task["day"]][$task["seg.name"]][$task["task.uuid"]] = 1;
        }

        $stockAvailabilityStats = array();
        foreach ($res as $username => $monthData) {
            if(!isset($stockAvailabilityStats[$username])){
                $stockAvailabilityStats[$username] = array("A"=>0,"B"=>0,"C"=>0,"D"=>0);
            }

            foreach($monthData as $month => $data){
                $stockAvailabilityStats[$username][$month] = count($res[$username][$month]);
            }
        }

        $lines = array();
        $lines[] = array("Date", "#A", "#B", "#C", "#D");

        foreach ($stockAvailabilityStats as $date => $segments) {
            $line = array();
            $line[] = $date;
            foreach ($segments as $segment => $value) {
                $line[] = $value;
            }
            $lines[] = $line;
        }
        
        return $lines;
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
        $q = "";
        if($this->isAdmin()){
            $q = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match cust-[:`IN_SEGMENT`]->seg
            where $det_filter task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, user.username, seg.name";
        } else {
            $q = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match cust-[:`IN_SEGMENT`]->seg
            match task-[:`COMPLETED_TASK`]-(user) where $det_filter task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, user.username, seg.name";
        }
        $tasks = $this->runNeoQuery($q);
        
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
    public function rtask_completion_export(){
        // Get filters
        @$classification = $_GET['orsAvailClassification'];
        @$period = $_GET['zincPercent'];

        $date_range = $this->getTimeRange($classification, $period);
        $tasks = $this->runNeoQuery("MATCH (task:`Task`) where task.completionDate > ". $date_range[0] .
            " AND task.completionDate < " . $date_range[1] . " 
            match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t)
            match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) RETURN distinct task.uuid, task.description, task.dateCreated, task.status, user.username, 
            t.name, rg.name");
        
        $res = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.dateCreated"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F Y");

            if (!isset($res[$task["month"]])) {
                $res[$task["month"]] = array();
            }
            if (!isset($res[$task["month"]][$task["rg.name"]])) {
                $res[$task["month"]][$task["rg.name"]] = array();
            }

            $res[$task["month"]][$task["rg.name"]][$task["task.status"]][$task["task.uuid"]] = 0;
        }

        $stockAvailabilityStats = array();
        foreach ($res as $date => $data) {
            foreach ($data as $username => $monthData) {
                if(!isset($stockAvailabilityStats[$date])){
                    $stockAvailabilityStats[$date] = array();
                }
                if(!isset($stockAvailabilityStats[$date][$username])){
                    $stockAvailabilityStats[$date][$username] = array(
                        "complete"=>array("total"=>0, "percent"=>0),
                        "cancelled"=>array("total"=>0, "percent"=>0),
                        "new"=>array("total"=>0, "percent"=>0)
                    );
                }

                $total = 0;
                foreach($monthData as $month => $data){
                    $total += count($res[$date][$username][$month]);
                }

                foreach($monthData as $month => $data){
                    $stockAvailabilityStats[$date][$username][$month] = array(
                        "total"=>count($res[$date][$username][$month]),
                        "percent"=>(count($res[$date][$username][$month])/$total) * 100
                        );
                }
            }
        }
        
        $lines = array();
        $lines[] = array("Month", "Region", "Total Active", "% Active", "Total Completed"," % Completed", "Total Cancelled", "% Cancelled");
        foreach ($stockAvailabilityStats as $date => $data) {
            foreach ($data as $region => $stats) {
                $line = array();
                $line[] = $date;
                $line[] = $region;
                $line[] = $stats["new"]["total"];
                $line[] = $stats["new"]["percent"];

                $line[] = $stats["complete"]["total"];
                $line[] = $stats["complete"]["percent"];

                $line[] = $stats["cancelled"]["total"];
                $line[] = $stats["cancelled"]["percent"];
                $lines[] = $line;
            }
        }

        return $lines;
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

    public function dtask_completion_export(){
        // Get filters
        @$classification = $_GET['orsAvailClassification'];
        @$period = $_GET['zincPercent'];

        $date_range = $this->getTimeRange($classification, $period);

        $q = "";
        if ($this->isAdmin()) {
            $q = "match user-[:`SUPERVISES_TERRITORY`]->(:`Territory`) match 
            t<-[:`SC_IN_TERRITORY`]-(sc) match sc<-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]->(task)  where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, task.status, user.username";
        } else {
            "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]->(:`Territory`) match 
            t<-[:`SC_IN_TERRITORY`]-(sc) match sc<-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]->(task) match 
            task<-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, task.status, user.username";
        }
        $tasks = $this->runNeoQuery($q);
        
        $res = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F Y");

            if (!isset($res[$task["month"]])) {
                $res[$task["month"]] = array();
            }

            if (!isset($res[$task["month"]][$task["user.username"]])) {

                $res[$task["month"]][$task["user.username"]] = array();
            }

            $res[$task["month"]][$task["user.username"]][$task["task.status"]][$task["task.uuid"]] = 0;
        }

        $stockAvailabilityStats = array();

        foreach ($res as $date => $stats) {
            foreach ($stats as $username => $monthData) {
                if(!isset($stockAvailabilityStats[$date])){
                    $stockAvailabilityStats[$date] = array();
                }

                if(!isset($stockAvailabilityStats[$date][$username])){
                    $stockAvailabilityStats[$date][$username] = array(
                        "complete"=>array("total"=>0, "percent"=>0),
                        "cancelled"=>array("total"=>0, "percent"=>0),
                        "new"=>array("total"=>0, "percent"=>0)
                    );
                }

                $total = 0;
                foreach($monthData as $month => $data){
                    $total += count($res[$date][$username][$month]);
                }

                foreach($monthData as $month => $data){
                    $stockAvailabilityStats[$date][$username][$month] = array(
                        "total"=>count($res[$date][$username][$month]),
                        "percent"=>(count($res[$date][$username][$month])/$total) * 100
                        );
                }
            }
        }
        
        $lines = array();
        $lines[] = array("Month", "Detailer", "Total Active", "% Active", "Total Completed"," % Completed", "Total Cancelled", "% Cancelled");
        foreach ($stockAvailabilityStats as $date => $data) {
            foreach ($data as $region => $stats) {
                $line = array();
                $line[] = $date;
                $line[] = $region;
                $line[] = $stats["new"]["total"];
                $line[] = $stats["new"]["percent"];

                $line[] = $stats["complete"]["total"];
                $line[] = $stats["complete"]["percent"];

                $line[] = $stats["cancelled"]["total"];
                $line[] = $stats["cancelled"]["percent"];
                $lines[] = $line;
            }
        }

        return $lines;
    }

    public function dtask_completion(){
        // Get filters
        @$classification = $_GET['orsAvailClassification'];
        @$period = $_GET['zincPercent'];

        $date_range = $this->getTimeRange($classification, $period);

        $q = "";
        if ($this->isAdmin()) {
            $q = "match user-[:`SUPERVISES_TERRITORY`]->(t:`Territory`) match 
            t<-[:`SC_IN_TERRITORY`]-(sc) match sc<-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]->(task)
             where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, task.status, user.username";
        } else {
            $q = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]->(t:`Territory`) match 
            t<-[:`SC_IN_TERRITORY`]-(sc) match sc<-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]->(task) match 
            task<-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return task.uuid, task.description, task.completionDate, task.status, user.username";
        }

        $tasks = $this->runNeoQuery($q);
        
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
    function product_avail_export(){
        set_time_limit(0);
        $time1 = time();

        $detailer = empty($_GET["detailer"]) ? "" : $_GET["detailer"];
        $stock = empty($_GET["stock"]) ? "ors" : $_GET["stock"];
        $district = empty($_GET["district"]) ? "" : $_GET["district"];

        $date_range = $this->getYearRange();
        $stockFilter = "";
        $districtFilter = "";
        $detailerFilter = "";

        // Stock filter
        if(!empty($stock) ){
            $stockFilter = "where stock.category = \"$stock\"";
        }

        // Detailer filter
        if(!empty($detailer) && $detailer != "All"){
            $detailerFilter = "and user.username = \"$detailer\"";
        }

        // District filter
        if($district){
            $districtFilter = "where stock.category = \"$district\"";
        }

        $query = "";
        if (!empty($district && $district != "All")) {
            if ($this->isAdmin()) {
                $query = "MATCH (n:`District`) where n.name = \"$district\" MATCH (n)-[:`HAS_SUB_COUNTY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) 
                match t-[:`SC_IN_TERRITORY`]-(sc) match user-[:`SUPERVISES_TERRITORY`]-(t) match cust-[:`CUST_TASK`]-(task) 
                where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . " $detailerFilter match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) $stockFilter return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
                stock.stockLevel";
            } else {
                $query = "MATCH (n:`District`) where n.name = \"$district\" MATCH (n)-[:`HAS_SUB_COUNTY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
                task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . " $detailerFilter match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) $stockFilter return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
                stock.stockLevel";
            }
        } else {
            if($this->isAdmin()){
                $query = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`)
                match t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task)
                 where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . " $detailerFilter match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) $stockFilter return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
                stock.stockLevel";
            } else {
                $query = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`)
                match t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
                task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . " $detailerFilter match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) $stockFilter return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
                stock.stockLevel";
            }
        }

        $tasks = $this->runNeoQuery($query);
        
        $res = array();
        $detailers = array();

        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("M. j, Y");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));
            $detailers[$task["user.username"]] = 0;

            if (!isset($res[$task["month"]][$task["user.username"]])) {
                $res[$task["month"]][$task["user.username"]] = array();
            }
            $res[$task["month"]][$task["user.username"]][$task["stock.uuid"]] = 0;

            if ($task["stock.stockLevel"] > 0) {
                $res[$task["month"]][$task["user.username"]][$task["stock.uuid"]] = 1;
            }
        }

        $stockAvailabilityStats = array();
        foreach ($res as $month => $monthData) {
            foreach($monthData as $username => $data){
                $stockAvailabilityStats[$month][$username] = $this->calculate_positive_percentage($res[$month][$username]);
            }
        }
        
        $lines = array();
        $lines[] = array("Date", "Detailer", "Availability");
        foreach ($stockAvailabilityStats as $date => $stats) {
            foreach ($stats as $det => $value) {
                $line = array();
                $line[] = $date;
                $line[] = $det;
                $line[] = $value;
                $lines[] = $line;
            }
        }

        $time2 = time();
        $this->timeLog["product_availability"] = $time2 - $time1;
        return $lines;
    }

    function avail_nzinc_ors_avail(){
        $time1 = time();
        // Get filters
        @$classification = $_GET['nOrsAvailClassification'];
        @$period = $_GET['nZincPercent'];

        $date_range = $this->getTimeRange($classification, $period);

        $tasks = $this->runNeoQuery("MATCH (task:`DetailerTask`) where task.completionDate > ". $date_range[0] .
            " AND task.completionDate < " . $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock) 
            match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t)
            where stock.category = \"zinc\" match (t)<-[:`SC_IN_TERRITORY`]-(sc:`SubCounty`) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
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
        $time2 = time();
        $this->timeLog["avail_nzinc_ors_avail"] = $time2 - $time1;

        return $stockAvailabilityStats;
    }

    function getSupervisorDetailers(){
        $date_range = $this->getYearRange();
        $users = $this->runNeoQuery("match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`)
         match t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task)
          where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return user.username");
        $u = array();
        foreach ($users as $user) {
            $u[] = $user["user.username"];
        }
        return array_unique($u);
    }

    function availability_data(){
        set_time_limit(0);
        $time1 = time();
        // Get filters

        $detailer = empty($_GET["detailer"]) ? "" : $_GET["detailer"];
        $stock = empty($_GET["stock"]) ? "ors" : $_GET["stock"];
        $district = empty($_GET["district"]) ? "" : $_GET["district"];

        $date_range = $this->getYearRange();
        $stockFilter = "";
        $detailerFilter = "";

        // Stock filter
        if(!empty($stock) ){
            $stockFilter = "where stock.category = \"$stock\"";
        }

        // Detailer filter
        if(!empty($detailer) && $detailer != "All"){
            $detailerFilter = "and user.username = \"$detailer\"";
        }

        $query = "";
        if (!empty($district && $district != "All")) {
            if ($this->isAdmin()) {
                $query = "MATCH (n:`District`) where n.name = \"$district\" MATCH (n)-[:`HAS_SUB_COUNTY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) 
                match t-[:`SC_IN_TERRITORY`]-(sc) match user-[:`SUPERVISES_TERRITORY`]-(t) match cust-[:`CUST_TASK`]-(task) 
                where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . " $detailerFilter match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) $stockFilter return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
                stock.stockLevel";
            } else {
                $query = "MATCH (n:`District`) where n.name = \"$district\" MATCH (n)-[:`HAS_SUB_COUNTY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
                task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . " $detailerFilter match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) $stockFilter return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
                stock.stockLevel";
            }
        } else {
            if($this->isAdmin()){
                $query = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`)
                match t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task)
                 where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . " $detailerFilter match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) $stockFilter return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
                stock.stockLevel";
            } else {
                $query = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`)
                match t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
                task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . " $detailerFilter match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) $stockFilter return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
                stock.stockLevel";
            }
        }

        $tasks = $this->runNeoQuery($query);
        
        $res = $this->getMonthsOfYear();
        $detailers = array();

        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["month"] = $dt->format("F");
            $task["week"] = $this->getWeekOfMonth($dt->format("j"));
            $detailers[$task["user.username"]] = 0;

            if (!isset($res[$task["month"]][$task["user.username"]])) {
                $res[$task["month"]][$task["user.username"]] = array();
            }
            $res[$task["month"]][$task["user.username"]][$task["stock.uuid"]] = 0;

            if ($task["stock.stockLevel"] > 0) {
                $res[$task["month"]][$task["user.username"]][$task["stock.uuid"]] = 1;
            }
        }

        $stockAvailabilityStats = $this->getMonthsOfYear($detailers);
        foreach ($res as $month => $monthData) {
            foreach($monthData as $username => $data){
                $stockAvailabilityStats[$month][$username] = $this->calculate_positive_percentage($res[$month][$username]);
            }
        }
        $time2 = time();
        $this->timeLog["zinc_percentage_availability"] = $time2 - $time1;
        return array("data" => $stockAvailabilityStats, "detailers" => $detailers);
    }

    function getDistrictsAndDetailers(){
        $q = "";
        if ($this->isAdmin()) {
            $q = "MATCH (detailer)-[:`SUPERVISES_TERRITORY`]->(territory) 
             MATCH (sc)-[:`SC_IN_TERRITORY`]-(territory) MATCH (ds)-[:`HAS_SUB_COUNTY`]-(sc) 
            RETURN territory.name, detailer.username, sc.name, ds.name;";
        } else {
            $q = "start n = node(". $this->_user['User']['neo_id'] .") MATCH (n)-[:`SUPERVISES_TERRITORY`]->(territory) 
            MATCH (detailer)-[:`USER_TERRITORY`]-(territory)  MATCH (sc)-[:`SC_IN_TERRITORY`]-(territory) MATCH (ds)-[:`HAS_SUB_COUNTY`]-(sc) 
            RETURN n.username,territory.name, detailer.username, sc.name, ds.name;";
        }
        $users = $this->runNeoQuery($q);
    
        $districts = array();
        foreach ($users as $user) {
            if($user["detailer.username"] == $this->_user['User']['username']){
                continue;
            }
            if (!isset($districts[$user["ds.name"]])) {
                $districts[$user["ds.name"]] = array();
            }
            $districts[$user["ds.name"]][] = ($user["detailer.username"]);
        }

        foreach ($districts as $district => $data) {
            $districts[$district] = array_unique($data);
        }

        return $districts;
    }
    function avail_rzinc_ors_avail_export(){
        // Get filters
        @$classification = $_GET['rOrsAvailClassification'];
        @$period = $_GET['rZincPercent'];

        $date_range = $this->getTimeRange($classification, $period);

        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]->(t:`Territory`) match 
            t<-[:`SC_IN_TERRITORY`]-(sc) match sc<-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]->(task) match 
            task<-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock:`DetailerStock`) where stock.category = 
            \"ors\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category, 
            stock.stockLevel");
        
        $res = array();

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

        return array("lines"=>$stockAvailabilityStats, "title"=>"Detailer");
    }
    function avail_rzinc_ors_avail(){
        $time1 = time();
        // Get filters
        @$classification = $_GET['rOrsAvailClassification'];
        @$period = $_GET['rZincPercent'];

        $date_range = $this->getTimeRange($classification, $period);

        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]->(t:`Territory`) match 
            t<-[:`SC_IN_TERRITORY`]-(sc) match sc<-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]->(task) match 
            task<-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock:`DetailerStock`) where stock.category = 
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

        $time2 = time();
        $this->timeLog["avail_rzinc_ors_avail"] = $time2 - $time1;
        return $stockAvailabilityStats;
    }

    function zinc_percentage_availability_export($product){
        // Get filters
        @$classification = $_GET['orsAvailClassification'];
        @$period = $_GET['zincPercent'];

        $date_range = $this->getTimeRange($classification, $period);

        $q = "";
        if($this->isAdmin()){
            $q = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) 
             where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) where stock.category = 
            \"$product\" match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) return task.uuid, task.description, task.completionDate, user.username, user.name, id(user) as user_id,
            stock.uuid, stock.category, stock.stockLevel, rg.name";
        } else {
            $q = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) where stock.category = 
            \"$product\" match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) return task.uuid, task.description, task.completionDate, user.username, user.name, id(user) as user_id,
            stock.uuid, stock.category, stock.stockLevel, rg.name";
        }
        $tasks = $this->runNeoQuery($q);

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

        return array("lines"=>$stockAvailabilityStats, "detailer_task"=>$detailer_task, "title"=>"$product Availability");
    }
	function zinc_percentage_availability($product){
        $time1 = time();
        // Get filters
        @$classification = $_GET['orsAvailClassification'];
        @$period = $_GET['zincPercent'];

		$date_range = $this->getTimeRange($classification, $period);

        $q = "";
        if ($this->isAdmin()) {
            $q = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) 
             where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) where stock.category = 
            \"$product\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
            stock.stockLevel ";
        } else {
            $q = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) where stock.category = 
            \"$product\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category,
            stock.stockLevel ";
        }
        $tasks = $this->runNeoQuery($q);
        
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
        $time2 = time();
        $this->timeLog["zinc_percentage_availability"] = $time2 - $time1;
        return $stockAvailabilityStats;
	}

	function average_visits_by_detailers($type){
        $time1 = time();
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
            $qs = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task:`DetailerTask`) match 
            (task)-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return distinct task.uuid, task.description, task.completionDate, user.username";

            if ($this->isAdmin()) {
                $qs = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) 
                match cust-[:`CUST_TASK`]-(task:`DetailerTask`) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . " return distinct task.uuid, task.description, task.completionDate, user.username";
            }
        } else {
            $qs = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " return distinct task.uuid, task.description, task.completionDate, user.username";

            if ($this->isAdmin()) {
                $qs = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
                t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task)
                 where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . " return distinct task.uuid, task.description, task.completionDate, user.username";
            }
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

        $time2 = time();
        $this->timeLog["average_visits_by_detailers"] = $time2 - $time1;
        return $medians;
	}

    function average_visits_by_detailers_export($type){
        ini_set('memory_limit','1600M');
        @$classification = $_GET['visitClassification'];
        @$period = $_GET['dailyVisitsPeriod'];

        $date_range = $this->getTimeRange($classification, $period);
        
        $qs = "";
        if ($type == "diarrhoea") {
            if($this->isAdmin()){
                $qs = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
                t-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
                match (rg)-[:`HAS_DISTRICT`]->(ds) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task)
                 where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . "match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) where stock.category = \"zinc\" 
                 return distinct task.uuid, task.description, task.completionDate, id(user) as user_id, user.name, user.username, stock.uuid,
                stock.category, stock.stockLevel, stock.sellingPrice, t.name, rg.name";
            } else {
                $qs = "MATCH (task:`DetailerTask`) where task.completionDate > ". $date_range[0] .
                " AND task.completionDate < " . $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock:`DetailerStock`) 
                match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t:`Territory`)
                where stock.category = \"zinc\" match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
                match (rg)-[:`HAS_DISTRICT`]->(ds) RETURN distinct task.uuid, task.description, task.completionDate, id(user) as user_id,
                user.name, user.username, t.name, rg.name";
            }
        } else {
            if($this->isAdmin()){
                $qs = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
                t-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
                match (rg)-[:`HAS_DISTRICT`]->(ds) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task)
                 where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
                 $date_range[1] . "match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`)  
                 return distinct task.uuid, task.description, task.completionDate, id(user) as user_id, user.name, user.username, stock.uuid,
                stock.category, stock.stockLevel, stock.sellingPrice, t.name, rg.name";
            } else {
                $qs = "MATCH (task:`Task`) where task.completionDate > ". $date_range[0] .
                " AND task.completionDate < " . $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock:`DetailerStock`) 
                match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t:`Territory`)
                 match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
                match (rg)-[:`HAS_DISTRICT`]->(ds) RETURN distinct task.uuid, task.description, task.completionDate, id(user) as user_id,
                user.name, user.username, t.name, rg.name";
            }
        }
        
        $tasks = $this->runNeoQuery($qs);
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

    function average_regional_product_price_export($product_name){
        @$classification = $_GET['visitClassification'];
        @$period = $_GET['dailyVisitsPeriod'];

        $date_range = $this->getTimeRange($classification, $period);
        

        $tasks = $this->runNeoQuery("MATCH (task:`DetailerTask`) where task.completionDate > ". $date_range[0] .
            " AND task.completionDate < " . $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock:`DetailerStock`) 
            match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t:`Territory`)
            where stock.category = \"$product_name\" match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) RETURN distinct task.uuid, task.description, task.completionDate, user.username, 
            stock.uuid, stock.category, stock.stockLevel, stock.sellingPrice, t.name, rg.name");

        $res[] = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $task["day"] = $dt->format("M. j, Y");

            if (!isset($res[$task["rg.name"]])) {
                $res[$task["rg.name"]] = array();
            }

            if(!isset($res[$task["rg.name"]][$task["day"]])){
                $res[$task["rg.name"]][$task["day"]] = array();
            }
            if (!empty($task["stock.sellingPrice"])) {
                $res[$task["rg.name"]][$task["day"]][] = $task["stock.sellingPrice"];
            }
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
        
        return array("lines"=>$stats, "title"=>"Average $product_name Price") ;
    }

    function average_regional_product_price($product_name){
        @$classification = $_GET['visitClassification'];
        @$period = $_GET['dailyVisitsPeriod'];

        $date_range = $this->getTimeRange($classification, $period);
        $q = "MATCH (task:`Task`) where task.completionDate > ". $date_range[0] .
        " AND task.completionDate < " . $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock:`DetailerStock`) 
        match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t:`Territory`)
        where stock.category = \"$product_name\" match (t)<-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
        match (rg)-[:`HAS_DISTRICT`]->(ds) RETURN distinct task.uuid, task.description, task.completionDate, user.username, 
        stock.uuid, stock.category, stock.stockLevel, stock.sellingPrice, t.name, rg.name";

        $tasks = $this->runNeoQuery($q);

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

    function average_detailer_product_price_export($product_name){
        @$classification = $_GET['productClassification'];
        @$period = $_GET['productPrice'];

        $date_range = $this->getTimeRange($classification, $period);

        $q = "";

        if ($this->isAdmin()) {
            $q = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(det) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) where stock.category = 
            \"$product_name\"  return task.uuid, task.description, task.completionDate, user.username, id(user) as user_id,
             user.name, stock.uuid, stock.category, stock.stockLevel, stock.sellingPrice, rg.name";
        } else {
            $q = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) where stock.category = 
            \"$product_name\"  return task.uuid, task.description, task.completionDate, user.username, id(user) as user_id,
             user.name, stock.uuid, stock.category, stock.stockLevel, stock.sellingPrice, rg.name";
        }

        $tasks = $this->runNeoQuery($q);
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

        return array("lines"=>$stats, "detailer_task"=>$detailer_task, "title"=>"Average $product_name Price", "title2"=>"Detailer");
    }

    function average_regional_ors_price(){
        @$classification = $_GET['orsAvailClassification'];
        @$period = $_GET['zincPercent'];

        $date_range = $this->getTimeRange($classification, $period);
        $tasks = $this->runNeoQuery("MATCH (task:`DetailerTask`) where task.completionDate > ". $date_range[0] .
            " AND task.completionDate < " . $date_range[1] . " match task-[:`HAS_DETAILER_STOCK`]->(stock:`DetailerStock`) 
            match task<-[:`COMPLETED_TASK`]-(user) match (user)-[:`USER_TERRITORY`]->(t:`Territory`)
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
        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) where stock.category = 
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
        $time1 = time();
        @$classification = $_GET['zincClassification'];
        @$period = $_GET['zincPrice'];

        $date_range = $this->getTimeRange($classification, $period);
        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) where stock.category = 
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
        $time2 = time();
        $this->timeLog["median_zinc_price"] = $time2 - $time1;
        return $stats;
	}

    function average_product_detailer_price($product_name){
        $time1 = time();
        @$classification = $_GET['productClassification'];
        @$period = $_GET['productPrice'];

        $date_range = $this->getTimeRange($classification, $period);

        $q = "";
        if($this->isAdmin()){
            $q = "match user-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task)
             where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock) where stock.category = 
            \"$product_name\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category, stock.stockLevel
            , stock.sellingPrice";
        } else {
            $q = "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock) where stock.category = 
            \"$product_name\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category, stock.stockLevel
            , stock.sellingPrice";
        }
        $tasks = $this->runNeoQuery($q);

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

        $time2 = time();
        $this->timeLog["median_ors_price"] = $time2 - $time1;
        return $stats;
    }

	function median_ors_price(){
        $time1 = time();
        @$classification = $_GET['orsClassification'];
        @$period = $_GET['ORSPrice'];

        $date_range = $this->getTimeRange($classification, $period);

		$tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) where stock.category = 
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

        $time2 = time();
        $this->timeLog["median_ors_price"] = $time2 - $time1;
        return $stats;
	}

    function median_ors_price_export(){
        @$classification = $_GET['orsClassification'];
        @$period = $_GET['ORSPrice'];

        $date_range = $this->getTimeRange($classification, $period);

        $tasks = $this->runNeoQuery("start n = node(". $this->_user['User']['neo_id'] .") match n-[:`SUPERVISES_TERRITORY`]-(t:`Territory`) match 
            t-[:`SC_IN_TERRITORY`]-(sc) match (ds)-[:`HAS_SUB_COUNTY`]->(sc) 
            match (rg)-[:`HAS_DISTRICT`]->(ds) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
            task-[:`COMPLETED_TASK`]-(user) where task.completionDate > " . $date_range[0] . " and task.completionDate < ".
             $date_range[1] . " optional match task-[:`HAS_DETAILER_STOCK`]-(stock:`DetailerStock`) where stock.category = 
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

	function calculate_average($arr, $num = 0) {
        $count = 0;
        if ($num > 0) {
            $count = count($arr);
        } else {
            $count = count($arr);
        }
	    
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

    function getTimesForWeek($weekNo){
        $week = sprintf("%02s", $weekNo);
        $year = date("Y");
        
        $first_minute = mktime(0, 0, 0, date("n", strtotime("$year-W$week-1")), date("j", strtotime("$year-W$week-1")));

        $last_minute = mktime(23, 59, 0, date("n", strtotime('+6 days', strtotime("$year-W$week-1"))),
         date("j", strtotime('+6 days', strtotime("$year-W$week-1"))));

         return array($first_minute*1000, $last_minute*1000);
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
        $time1 = time();
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
        $time2 = time();
        if (!isset($this->timeLog["query_time"])) {
            $this->timeLog["query_time"] = array();
        }
        $this->timeLog["query_time"][] = $time2 - $time1;
        return $tasks;
    }

    function getTimeRange($classification, $period){
        if(!in_array($classification, array(1,2,3))){
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
        } else if ($classification == 3){
            $date_range = $this->getTimesForWeek($period);
        }
        $date_range["classification"] = $classification;

        return $date_range;
    }

    function getYearRange(){
        $firstMonth = $this->getTimesForMonth(1);
        $lastMonth = $this->getTimesForMonth(12);

        $results = array();
        $results[0] = $firstMonth[0];
        $results[1] = $lastMonth[1];

        return $results;
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
    function getMonthsOfYear($data = ""){
        if (!empty($data)) {
            return array("January"=>$data, "February"=>$data, "March"=>$data, "April"=>$data, "May"=>$data, "June"=>$data,
         "July"=>$data, "August"=>$data, "September"=>$data, "October"=>$data, "November"=>$data, "December"=>$data);
        }
        return array("January"=>array(), "February"=>array(), "March"=>array(), "April"=>array(), "May"=>array(), "June"=>array(),
         "July"=>array(), "August"=>array(), "September"=>array(), "October"=>array(), "November"=>array(), "December"=>array());
    }
}
