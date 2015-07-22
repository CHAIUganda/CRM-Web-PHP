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
        $this->set("detailer_visits", $this->median_visits_by_detailers());
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

		$query = new Everyman\Neo4j\Cypher\Query($this->client, "start n = node(25237) match n-[:`USER_TERRITORY`]-(t) match 
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
        	if (!isset($res[$task["user.username"]])) {
        		$res[$task["user.username"]] = array();
        		$res[$task["user.username"]]["Tasks"] = array();
        	}

        	$res[$task["user.username"]]["Tasks"][$task["task.uuid"]] = 0;
        	if ($task["stock.stockLevel"] > 0) {
        		$res[$task["user.username"]]["Tasks"][$task["task.uuid"]] = 1;
        	}
        }

        $stockAvailabilityStats = array();
        foreach (array_keys($res) as $username) {
        	$stockAvailabilityStats[$username] = $this->calculate_positive_percentage($res[$username]["Tasks"]);
        }

        return $stockAvailabilityStats;
	}

	function median_visits_by_detailers(){
		$this->client = new Everyman\Neo4j\Client();
        $this->client->getTransport()->setAuth("neo4j", "neo4j");

        $query = new Everyman\Neo4j\Cypher\Query($this->client, "start n = node(25237) match n-[:`USER_TERRITORY`]-(t) match 
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
        		}
        	}

        	if (!isset($stats[$item["user.username"]])) {
        		$stats[$item["user.username"]] = array();
			}
			if (!isset($stats[$item["user.username"]][$item["task.completionDate"]])) {
    			$stats[$item["user.username"]][$item["task.completionDate"]] = 1;
			} else {
				$stats[$item["user.username"]][$item["task.completionDate"]]++;
			}
        	$res[] = $item;
        }

        $medians = array();
        foreach ($stats as $key => $value) {
        	$medians[$key] = $this->calculate_average(array_values($value));
        }

        return $medians;
	}

	function median_zinc_price(){
		$this->client = new Everyman\Neo4j\Client();
        $this->client->getTransport()->setAuth("neo4j", "neo4j");

		$query = new Everyman\Neo4j\Cypher\Query($this->client, "start n = node(25237) match n-[:`USER_TERRITORY`]-(t) match 
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
        	if (!isset($res[$task["user.username"]])) {
        		$res[$task["user.username"]] = array();
        	}

        	if (!empty($task["stock.sellingPrice"])) {
        		$res[$task["user.username"]][] = $task["stock.sellingPrice"];
        	}
        }
        
        $stats = array();
        foreach (array_keys($res) as $value) {
        	$stats[$value] = $this->calculate_median($res[$value]);
        }

        return $stats;
	}

	function median_ors_price(){
		$this->client = new Everyman\Neo4j\Client();
        $this->client->getTransport()->setAuth("neo4j", "neo4j");

		$query = new Everyman\Neo4j\Cypher\Query($this->client, "start n = node(25237) match n-[:`USER_TERRITORY`]-(t) match 
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
        	if (!isset($res[$task["user.username"]])) {
        		$res[$task["user.username"]] = array();
        	}

        	if (!empty($task["stock.sellingPrice"])) {
        		$res[$task["user.username"]][] = $task["stock.sellingPrice"];
        	}
        }
        
        $stats = array();
        foreach (array_keys($res) as $value) {
        	$stats[$value] = $this->calculate_median($res[$value]);
        }

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
}
