<?php
App::uses('AccountManagementAppController', 'AccountManagement.Controller');
/**
 * AccountEntries Controller
 *
 * @property AccountEntry $AccountEntry
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class AccountEntriesController extends AccountManagementAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->AccountEntry->recursive = 0;
		$this->set('accountEntries', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->AccountEntry->exists($id)) {
			throw new NotFoundException(__('Invalid account entry'));
		}
		$options = array('conditions' => array('AccountEntry.' . $this->AccountEntry->primaryKey => $id));
		$this->set('accountEntry', $this->AccountEntry->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($idAccount) {
		if ($this->request->is('post')) {
			$this->AccountEntry->create();
			$this->request->data['AccountEntry']['account_id'] = $idAccount;
			$this->request->data['AccountEntry']['date'] = $this->Functions->viewDateToDateTime($this->request->data['AccountEntry']['date'])->format('Y-m-d H:i:s');
			if ($this->AccountEntry->save($this->request->data)) {
				$this->Session->setFlash(__('The account entry has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account entry could not be saved. Please, try again.'));
			}
		}
		$accounts = $this->AccountEntry->Account->find('list');

		$date = date('d/m/Y');
		if(isset($this->request->data['date']))
		{
		  $date = $this->request->data['date'];
		}
		$this->set(compact('accounts', 'date'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->AccountEntry->exists($id)) {
			throw new NotFoundException(__('Invalid account entry'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->AccountEntry->save($this->request->data)) {
				$this->Session->setFlash(__('The account entry has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account entry could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('AccountEntry.' . $this->AccountEntry->primaryKey => $id));
			$this->request->data = $this->AccountEntry->find('first', $options);
		}
		$accounts = $this->AccountEntry->Account->find('list');
		$this->set(compact('accounts'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->AccountEntry->id = $id;
		if (!$this->AccountEntry->exists()) {
			throw new NotFoundException(__('Invalid account entry'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->AccountEntry->delete()) {
			$this->Session->setFlash(__('The account entry has been deleted.'));
		} else {
			$this->Session->setFlash(__('The account entry could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
