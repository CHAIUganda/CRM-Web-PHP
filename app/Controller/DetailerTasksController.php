<?php
App::uses('AppController', 'Controller');
/**
 * DetailerTasks Controller
 *
 * @property DetailerTask $DetailerTask
 */
class DetailerTasksController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->DetailerTask->recursive = 0;
		$this->set('tasks', $this->paginate());
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
}
