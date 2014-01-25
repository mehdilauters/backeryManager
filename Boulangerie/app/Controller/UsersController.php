<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	var $publicActions = array('login', 'add', 'autologin');

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
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$media = array_merge(array(''=>''), $this->User->Media->find('list'));
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
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$media = array_merge(array(''=>''), $this->User->Media->find('list'));
		$this->set(compact('media'));
	}

	
	public function setIsRoot($id = null, $isRoot = false) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$nbRoot = $this->User->find('count', array('conditions'=>'User.isRoot = true'));
			debug($nbRoot);
			if($nbRoot > 1)
			{
				$user = $this->User->findById($id);
				$user['User']['isRoot'] = $isRoot;
				if ($this->User->save($user)) {
					$this->Session->setFlash(__('The user has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
					$this->redirect(array('action' => 'index'));
				}
			}
			else
			{
				$this->Session->setFlash(__('This is the last Root user.'));
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
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	
	public function login() {
		if ($this->request->is('post')) {
// 			debug($this->request->data['User']['password']);
			if ($this->Auth->login()) {
				$cookieValue = array(
										'id' => $this->Auth->user('id'),
										'key' => AuthComponent::password(AuthComponent::password($this->data['User']['password']).$this->Auth->user('id'))
									);
				$this->Cookie->write('boulangerieFaury', $cookieValue, true, '2 weeks');
			
				return $this->redirect($this->Auth->redirectUrl());
				// Avant 2.3, utilisez
				// `return $this->redirect($this->Auth->redirect());`
			} else {
				$this->Session->setFlash(
					__('Username ou password est incorrect'),
					'default',
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
			$user = $this->User->findById($this->Cookie->read('boulangerieFaury.id'));
			if(isset($user['User']['id']))
			{
				$key = AuthComponent::password($user['User']['password'].$user['User']['id']);
				if($key == $this->Cookie->read('boulangerieFaury.key'))
				{
					$this->Auth->login($user);
				}
			}
		}
	}

//   public function beforeFilter()
//   {
//     if(!$this->request->params['action'] != 'autologin')
//     {
//       parent::beforeFilter();
//     }
//   }

}
