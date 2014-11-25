<?php
App::uses('AppController', 'Controller');
/**
 * Emails Controller
 *
 * @property Email $Email
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class EmailsController extends AppController {

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
		$this->Email->recursive = 0;
		$this->set('emails', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Email->exists($id)) {
			throw new NotFoundException(__('Invalid email'));
		}
		$options = array('conditions' => array('Email.' . $this->Email->primaryKey => $id));
		$this->set('email', $this->Email->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Email->create();
			$this->request->data['Email']['password'] = 'encrypt('.$this->request->data['Email']['password'].')';
			if ($this->Email->save($this->request->data)) {
				$this->Session->setFlash(__('The email has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The email could not be saved. Please, try again.'));
			}
		}
		$companies = $this->Email->Company->find('list');
		$this->set(compact('companies'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Email->exists($id)) {
			throw new NotFoundException(__('Invalid email'));
		}
		if ($this->request->is(array('post', 'put'))) {
                        if( $this->request->data['Email']['password'] != '')
                        {
                          $this->request->data['Email']['password'] = 'encrypt('.$this->request->data['Email']['password'].')';
                        }
			if ($this->Email->save($this->request->data)) {
				$this->Session->setFlash(__('The email has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The email could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Email.' . $this->Email->primaryKey => $id));
			$this->request->data = $this->Email->find('first', $options);
		}
		$this->request->data['Email']['password'] = '';
		$companies = $this->Email->Company->find('list');
		$this->set(compact('companies'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Email->id = $id;
		if (!$this->Email->exists()) {
			throw new NotFoundException(__('Invalid email'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Email->delete()) {
			$this->Session->setFlash(__('The email has been deleted.'));
		} else {
			$this->Session->setFlash(__('The email could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
