<?php
require(APP . 'Vendor/autoload.php');
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	var $helpers = array('Html','Form','Session');
	var $uses = array('User');
    var $components = array('Auth' => array(
        'authenticate' => array(
            'Form' => array(
                'passwordHasher' => 'Blowfish'
            )
        )
    ),'Session');
    var $_user;
    function getNeo4jUser($uuid){
            $this->client = new Everyman\Neo4j\Client();
            $this->client->getTransport()->setAuth("neo4j", "neo4j");
            $query = new Everyman\Neo4j\Cypher\Query($this->client, "MATCH (n:`User`) where n.uuid = \"$uuid\" match (n)-[:`HAS_ROLE`]-(role) RETURN 
                n.username as username, n.password as password, id(n) as node_id, role.authority");

            $results = $query->getResultSet();
            if (empty($results)) {
                return array();
            } else {
                $user = array();
                $roles = array();
                foreach ($results as $result) {
                    $columns = $result->columns();
                    foreach ($columns as $column) {
                        if ($column == "role.authority") {
                            $roles[] = $result[$column];
                            continue;
                        }
                        $user[$column] = $result[$column];
                    }
                }
                $user["roles"] = $roles;
                return $user;
            }
    }
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->loginError = "Sorry your username or password is incorrect";
    	$this->Auth->loginAction = array('admin' => false, 'controller' => 'users', 'action' => 'login');
    	$this->Auth->loginRedirect = array('admin' => false, 'controller' => 'dashboard', 'action' => 'index');
        $this->Auth->loginError = "Sorry your username or password is incorrect";
        $this->Auth->logoutRedirect = array("controller"=>"users", "action"=>"login");

        $id = $this->Auth->user("id");
        if(!empty($id)){
            $authUser = $this->User->read(null, $this->Auth->user("id"));
            $neo_user = $this->getNeo4jUser($authUser['User']['uuid']);
            $authUser['User']['neo_id'] = $neo_user['node_id'];
            $authUser['User']['roles'] = $neo_user['roles'];
            $this->_user = $authUser;
            $this->set("user", $authUser);
        } else {
            $this->_user = array();
            $this->set("user", array());
        }
        
        if(!empty($id) && $this->action == "home" && $this->name == "Pages"){
            //$this->redirect("/dashboards");
        }
    }

    function isAdmin(){
        return in_array("ROLE_SUPER_ADMIN", $this->_user["User"]["roles"]);
    }

    private function allowAccess() {
        if(in_array($this->name, array("Users"))) {
            $this->Auth->allow('*');
        }
    }

    function logSQL(){
        $sources = ConnectionManager::sourceList();
        if (!isset($logs)):
    	   $logs = array();
    	   foreach ($sources as $source):
            $db =& ConnectionManager::getDataSource($source);
    		if (!$db->isInterfaceSupported('getLog')):
    			continue;
    		endif;
    		$logs[$source] = $db->getLog(false,false);
    	   endforeach;
        endif;
        echo '<pre>';
    			print_r($logs);
        echo '</pre>';
    }
}
