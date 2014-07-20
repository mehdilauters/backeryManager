<?php
App::uses('AppController', 'Controller');
/**
 * Companies Controller
 *
 * @property Company $Company
 */
class CompaniesController extends AppController {
var $publicActions = array('view');

  var $administratorActions = array('edit');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Company->recursive = 0;
		$this->set('companies', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Company->exists($id)) {
			throw new NotFoundException(__('Invalid company'));
		}
		$options = array('conditions' => array('Company.' . $this->Company->primaryKey => $id));
		$result = $this->Company->find('first', $options);
		$this->set('company', $result);
		
		
        if (isset($this->params['requested'])) {
            return $result;
        }
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
	    $company = $this->Company->find('first');
	     
		if ($this->request->is('post')) {
			$eventType = array('EventType'=> array('name'=>'news'));
			$this->Company->EventType->save($eventType);
			$this->request->data['Company']['event_type_id'] = $this->Company->EventType->getInsertID();
			$this->Company->create();
			if ($this->Company->save($this->request->data)) {
				$this->Session->setFlash(__('The company has been saved'),'flash/ok');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The company could not be saved. Please, try again.'),'flash/fail');
			}
		}
		$media = $this->Company->Media->find('list');
		$this->set(compact('media'));
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
			$this->Session->setFlash(__('The company does not exists'),'flash/fail');
			return $this->redirect(array('action' => 'add'));	      
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Company->save($this->request->data)) {
				$this->Session->setFlash(__('The company has been saved'),'flash/ok');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The company could not be saved. Please, try again.'),'flash/fail');
			}
		} else {
			$options = array('conditions' => array('Company.' . $this->Company->primaryKey => $id));
			$this->request->data = $this->Company->find('first', $options);
		}
		$media = $this->Company->Media->find('list');
		$this->set(compact('media'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Company->id = $id;
		if (!$this->Company->exists()) {
			throw new NotFoundException(__('Invalid company'));
		}
		if ($this->request->is('post') || $this->request->is('delete')) {
			if ($this->Company->delete()) {
				$this->Session->setFlash(__('Company deleted'),'flash/ok');
				return $this->redirect(array('action' => 'index'));
			}
		}
		$this->Session->setFlash(__('Company was not deleted'),'flash/fail');
		return $this->redirect(array('action' => 'index'));
	}
}
