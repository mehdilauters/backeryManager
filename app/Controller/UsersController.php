<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	var $publicActions = array('login', 'add', 'autologin');
	var $memberActions = array('logout');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			$this->request->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'),'flash/ok');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'),'flash/fail');
			}
		}
		$media = array(''=>'')  + $this->User->Media->find('list');
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
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if( $this->request->data['User']['password'] != '' )
			{
			  $this->request->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
			}
			else
			{
			  unset($this->request->data['User']['password']);
			}

			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'),'flash/ok');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'),'flash/fail');
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$media = array(''=>'')  + $this->User->Media->find('list');
		$this->set(compact('media'));
	}

	
	public function setIsRoot($id = null, $isRoot = false) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$nbRoot = $this->User->find('count', array('conditions'=>'User.isRoot = true'));
			if(!(!$isRoot && $nbRoot = 1))
			{
				$user = $this->User->findById($id);
				$user['User']['isRoot'] = $isRoot;
				$this->log('user '.$user['name'].' isRoot set from '.(!$isRoot).' to '.$isRoot.' by '.$this->Auth->user('name'), 'debug');
				if ($this->User->save($user)) {
					$this->Session->setFlash(__('The user has been saved'),'flash/ok');
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The user could not be saved. Please, try again.'),'flash/fail');
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash(__('This is the last Root user.'),'flash/error');
				$this->redirect(array('action' => 'index'));
			}
		} else {
		}
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'),'flash/ok');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'),'flash/fail');
		$this->redirect(array('action' => 'index'));
	}
	
	
	public function login() {
		if ($this->request->is('post')) {
// $this->request->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
			$authRes = $this->Auth->login();
			if ($authRes) {
				$cookieValue = array(
										'id' => $this->Auth->user('id'),
										'key' => AuthComponent::password(AuthComponent::password($this->data['User']['password']).$this->Auth->user('id'))
									);
				$this->Cookie->write('boulangerieFaury', $cookieValue, true, '10 weeks');
				$this->log($this->request->data['User']['email'].' logged, redirect to '.$this->Auth->redirectUrl(), 'debug');
				return $this->redirect($this->Auth->redirectUrl());
				// Avant 2.3, utilisez
				// `return $this->redirect($this->Auth->redirect());`
			} else {
				$this->log('cannot log '.$this->request->data['User']['email'], 'debug');
				$this->Session->setFlash(
					__('Username ou password est incorrect'),
					'flash/fail',
					array(),
					'auth'
				);
			}
		}
	}

	public function logout() {
		$this->Cookie->destroy();
		$this->redirect($this->Auth->logout());
	}	
	
	public function autologin()
	{
		if($this->Cookie->check('boulangerieFaury'))
		{
			$user = $this->User->find('first',array('conditions'=>array('User.id' => $this->Cookie->read('boulangerieFaury.id'))));
			if(isset($user['User']['id']))
			{
				$key = AuthComponent::password($user['User']['password'].$user['User']['id']);
				if($key == $this->Cookie->read('boulangerieFaury.key'))
				{
					$this->Auth->login($user['User']);
				}
				else
				{
				  $this->log('invalid autologin cookie key for user '.$this->Cookie->read('boulangerieFaury.id'), 'debug');
				  $this->Cookie->destroy();
				}
			}
		      else
		      {
			$this->log('invalid autologin cookie: cannot find user id', 'debug');
			$this->Cookie->destroy();
		      }
		}
	}

   public function beforeFilter()
  {
      parent::beforeFilter();
      if(!($this->Session->check('noSSL') && $this->Session->read('noSSL')))
      {
	$this->Security->requireSecure('login');
      }
  }

}
