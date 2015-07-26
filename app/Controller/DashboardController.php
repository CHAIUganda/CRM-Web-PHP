<?php
require(APP . 'vendor/autoload.php');

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

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->DetailerTask->exists($id)) {
			throw new NotFoundException(__('Invalid company'));
		}
		$options = array('conditions' => array('Company.' . $this->Company->primaryKey => $id));
		$this->set('company', $this->Company->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Company->create();
			if ($this->Company->save($this->request->data)) {
				$this->Session->setFlash(__('The company has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The company could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Company->exists($id)) {
			throw new NotFoundException(__('Invalid company'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Company->save($this->request->data)) {
				$this->Session->setFlash(__('The company has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The company could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Company.' . $this->Company->primaryKey => $id));
			$this->request->data = $this->Company->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Company->id = $id;
		if (!$this->Company->exists()) {
			throw new NotFoundException(__('Invalid company'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Company->delete()) {
			$this->Session->setFlash(__('Company deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Company was not deleted'));
		$this->redirect(array('action' => 'index'));
	}


	function zinc_percentage_availability(){
		$this->client = new Everyman\Neo4j\Client();
        $this->client->getTransport()->setAuth("neo4j", "neo4j");

		$query = new Everyman\Neo4j\Cypher\Query($this->client, "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`USER_TERRITORY`]-(t) match 
        	t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
        	task-[:`COMPLETED_TASK`]-(user) optional match task-[:`HAS_DETAILER_STOCK`]-(stock) where stock.category = 
        	\"zinc\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category, stock.stockLevel 
        	LIMIT 1000");
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

        $res = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $item[$column] = $dt->format('Y-m-d');
            $task["month"] = $dt->format("F");

        	if (!isset($res[$task["user.username"]])) {
        		$res[$task["user.username"]] = array();
        		$res[$task["user.username"]] = array();
        	}

            if(!isset($res[$task["user.username"]][$task["month"]])){
                $res[$task["user.username"]][$task["month"]] = array();
            }

        	$res[$task["user.username"]][$task["month"]][$task["task.uuid"]] = 0;
        	if ($task["stock.stockLevel"] > 0) {
        		$res[$task["user.username"]][$task["month"]][$task["task.uuid"]] = 1;
        	}
        }
        $stockAvailabilityStats = array();
        foreach ($res as $username => $monthData) {
            if(!isset($stockAvailabilityStats[$username])){
                $stockAvailabilityStats[$username] = array();
                $stockAvailabilityStats[$username] = $this->getMonths();
            }

            foreach($monthData as $month => $data){
                $stockAvailabilityStats[$username][$month] = $this->calculate_positive_percentage($res[$username][$month]);
            }
        }
        return $stockAvailabilityStats;
	}

	function average_visits_by_detailers(){
        $otherMonth = date('F', mktime(0, 0, 0, date('m')-2, 1, date('Y')));
        $lastMonth = date('F', mktime(0, 0, 0, date('m')-1, 1, date('Y')));
        $thisMonth = date('F');

		$this->client = new Everyman\Neo4j\Client();
        $this->client->getTransport()->setAuth("neo4j", "neo4j");

        $query = new Everyman\Neo4j\Cypher\Query($this->client, "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`USER_TERRITORY`]-(t) match 
        	t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) optional match 
        	task-[:`COMPLETED_TASK`]-(user) return distinct task.uuid, task.description, task.completionDate, user.username");

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
        		}
        	}

        	if (!isset($stats[$item["user.username"]])) {
        		$stats[$item["user.username"]] = array();
			}
			if (!isset($stats[$item["user.username"]][$item["month"]])) {
                $stats[$item["user.username"]][$item["month"]] = array();
			}
            if(!isset($stats[$item["user.username"]][$item["month"]][$item["task.completionDate"]])){
                $stats[$item["user.username"]][$item["month"]][$item["task.completionDate"]] = 1;
            } else {
				$stats[$item["user.username"]][$item["month"]][$item["task.completionDate"]]++;
			}
        	$res[] = $item;
        }

        unset($stats["anon"]);
        $medians = array();
        foreach ($stats as $detName => $detData) {
            foreach ($detData as $month => $values) {
                if(!isset($medians[$detName])){
                    $medians[$detName] = $this->getMonths();
                }
                $medians[$detName][$month] = $this->calculate_average(array_values($values));
            }
        }

        return $medians;
	}

	function median_zinc_price(){
		$this->client = new Everyman\Neo4j\Client();
        $this->client->getTransport()->setAuth("neo4j", "neo4j");

		$query = new Everyman\Neo4j\Cypher\Query($this->client, "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`USER_TERRITORY`]-(t) match 
        	t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
        	task-[:`COMPLETED_TASK`]-(user) optional match task-[:`HAS_DETAILER_STOCK`]-(stock) where stock.category = 
        	\"zinc\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category, stock.stockLevel
        	, stock.sellingPrice LIMIT 1000");
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

        $res[] = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $item[$column] = $dt->format('Y-m-d');
            $task["month"] = $dt->format("F");

        	if (!isset($res[$task["user.username"]])) {
        		$res[$task["user.username"]] = array();
        	}

            if(!isset($res[$task["user.username"]][$task["month"]])){
                $res[$task["user.username"]][$task["month"]] = array();
            }
        	if (!empty($task["stock.sellingPrice"])) {
        		$res[$task["user.username"]][$task["month"]][] = $task["stock.sellingPrice"];
        	}
        }
        
        $stats = array();
        foreach ($res as $detName=>$monthData) {
            if(!isset($stats[$detName])){
                $stats[$detName] = array();
                $stats[$detName] = $this->getMonths();
            }

            foreach($monthData as $month => $data){
                $stats[$detName][$month] = $this->calculate_median($res[$detName][$month]);
            }
        }
        unset($stats[0]);

        return $stats;
	}

	function median_ors_price(){
		$this->client = new Everyman\Neo4j\Client();
        $this->client->getTransport()->setAuth("neo4j", "neo4j");

		$query = new Everyman\Neo4j\Cypher\Query($this->client, "start n = node(". $this->_user['User']['neo_id'] .") match n-[:`USER_TERRITORY`]-(t) match 
        	t-[:`SC_IN_TERRITORY`]-(sc) match sc-[:`CUST_IN_SC`]-(cust) match cust-[:`CUST_TASK`]-(task) match 
        	task-[:`COMPLETED_TASK`]-(user) optional match task-[:`HAS_DETAILER_STOCK`]-(stock) where stock.category = 
        	\"ors\" return task.uuid, task.description, task.completionDate, user.username, stock.uuid, stock.category, stock.stockLevel
        	, stock.sellingPrice LIMIT 1000");
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

        $res[] = array();
        foreach ($tasks as $task) {
            $epoch = floor($task["task.completionDate"]/1000);
            $dt = new DateTime("@$epoch");
            $item[$column] = $dt->format('Y-m-d');
            $task["month"] = $dt->format("F");

        	if (!isset($res[$task["user.username"]])) {
                $res[$task["user.username"]] = array();
            }

            if(!isset($res[$task["user.username"]][$task["month"]])){
                $res[$task["user.username"]][$task["month"]] = array();
            }
            if (!empty($task["stock.sellingPrice"])) {
                $res[$task["user.username"]][$task["month"]][] = $task["stock.sellingPrice"];
            }
        }
        
        $stats = array();
        foreach ($res as $detName=>$monthData) {
            if(!isset($stats[$detName])){
                $stats[$detName] = array();
                $stats[$detName] = $this->getMonths();
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
		return ($positive_count/count($arr))*100;
	}

    function getMonths(){
        $months = array();

        for ($x = 1; $x < 13; $x++) {
            $months[date('F', mktime(0, 0, 0, $x, 1))] = 0;
        }
        return $months;
    }
}
