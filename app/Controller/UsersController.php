<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	var $publicActions = array('login', 'add', 'autologin','logout');
	var $memberActions = array('logout');
	var $administratorActions = array('*');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		//TODO isRoot replacement
		$this->User->recursive = 0;
		$this->set('users', $this->paginate(array('User.company_id'=>$this->getCompanyId())));
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
		$user = $this->User->find('first', $options);
		if ($user['User']['company_id'] != $this->getCompanyId()) {
			throw new NotFoundException(__('Invalid user for this company'));
		}

		$this->set('user', $user);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			if($this->request->data['User']['media_id'] != '')
			{
			  $media = $this->User->Media->findById($this->request->data['User']['media_id']);
			  if ($media['User']['company_id'] != $this->getCompanyId()) {
				throw new NotFoundException(__('Invalid Media for this company'));
			    }
			}

			$this->User->create();
			$this->request->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
			$this->request->data['User']['company_id'] = $this->getCompanyId();

			$count = $this->User->find('count', array('conditions' => array('User.company_id' => $this->getCompanyId(), 'User.email' => $this->request->data['User']['email'] ))); 
			if($count != 0)
			{
			    throw new NotFoundException(__('User already exists'));
			}

			if ($this->User->save($this->request->data)) {

				$parentId = 1; // members
				//check users for this company
				$count = $this->User->find('count', array('conditions' => array('User.company_id' => $this->getCompanyId())));
				if($count == 1)
				{
				  $parentId = 2; // administrators
				}
				// check if already users registered
				$count = $this->User->find('count');
				if($count == 1)
				{
				  $parentId = 3; // root
				}


				// creating corresponding aro
				$aro = $this->Acl->Aro;
				$aroData = array('alias' => $this->data['User']['name'],
				'parent_id' => $parentId,
				'model' => 'User',
				'foreign_key' => $this->User->getInsertID(),
				);
				$aro->create();
				$aro->save($aroData);
				$this->Session->setFlash(__('The user has been saved'),'flash/ok');
				$tokens = $this->getUserTokens();
				if($tokens['isRoot'] || $tokens['isAdmin'])
				{
				  $this->redirect(array('action' => 'index'));
				}
				else
				{
				    $this->redirect('/');
				}
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'),'flash/fail');
			}
		}
		$this->User->Media->contain('User');
		$media = array(''=>'')  + $this->User->Media->find('list', array('conditions' => array('User.company_id' => $this->getCompanyId())));
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
		$user = $this->User->findById($id);
		if ($user['User']['company_id'] != $this->getCompanyId()) {
			throw new NotFoundException(__('Invalid user for this company'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->request->data['User']['media_id'] != '')
			{
			  $media = $this->User->Media->findById($this->request->data['User']['media_id']);
			  if ($media['User']['company_id'] != $this->getCompanyId()) {
				throw new NotFoundException(__('Invalid Media for this company'));
			    }
			}
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
		$this->User->Media->contain('User');
		$media = array(''=>'')  + $this->User->Media->find('list', array('conditions' => array('User.company_id' => $this->getCompanyId())));
		$this->set(compact('media'));
	}

	public function setPresentationMode($autostartHelp = false) {
			$id = $this->Auth->user('id');
// 			if ($this->request->is('post') || $this->request->is('put')) {
			  $user = $this->User->findById($id);
			  $user['User']['autostart_help'] = $autostartHelp;
			  if ($this->User->save($user)) {
				  $this->Session->setFlash(__('L\aide à été désactivée pour le compte #'.$id),'flash/ok');
				  $this->redirect('/');
			  } else {
				  $this->Session->setFlash(__('Impossible de désactiver l\'aide'),'flash/fail');
				  $this->redirect('/');
			  }
// 			}
	}

	
// 	public function setIsAdmin($id = null, $isAdmin = false) {
// 		if (!$this->User->exists($id)) {
// 			throw new NotFoundException(__('Invalid user'));
// 		}
// 		$user = $this->User->findById($id);
// 		if ($user['User']['company_id'] != $this->getCompanyId()) {
// 			throw new NotFoundException(__('Invalid user for this company'));
// 		}
// 		if ($this->request->is('post') || $this->request->is('put')) {
// 			$nbRoot = $this->User->find('count', array('conditions'=>'User.isRoot = true'));
// 			if(!(!$isRoot && $nbRoot = 1))
// 			{
// 				$user = $this->User->findById($id);
// 				$user['User']['isRoot'] = $isRoot;
// 				$this->log('user '.$user['name'].' isRoot set from '.(!$isRoot).' to '.$isRoot.' by '.$this->Auth->user('name'), 'debug');
// 				if ($this->User->save($user)) {
// 					$this->Session->setFlash(__('The user has been saved'),'flash/ok');
// 					$this->redirect(array('action' => 'index'));
// 				} else {
// 					$this->Session->setFlash(__('The user could not be saved. Please, try again.'),'flash/fail');
// 					$this->redirect(array('action' => 'index'));
// 				}
// 			}
// 			else
// 			{
// 				$this->Session->setFlash(__('This is the last Root user.'),'flash/error');
// 				$this->redirect(array('action' => 'index'));
// 			}
// 		} else {
// 		}
// 	}

	
/**
 * delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
//TODO delete ARO
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$user = $this->User->findById($id);
		if ($user['User']['company_id'] != $this->getCompanyId()) {
			throw new NotFoundException(__('Invalid user for this company'));
		}
		if($id == $this->Auth->user('id'))
		{
		  $this->Session->setFlash(__('Vous ne pouvez pas vous supprimer vous-même'),'flash/fail');
		  $this->redirect(array('action' => 'index'));
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
		if( Configure::read('Settings.demo.active') )
		{
			if($this->Cookie->check('demoLogout'))
			{
				$this->Cookie->delete('demoLogout');
			}
		}
		
		 $user = $this->User->find('first', array('conditions'=>array(
					    'User.password' => AuthComponent::password($this->data['User']['password']),
					    'User.email' => $this->data['User']['email'],
					    'User.company_id' => $this->getCompanyId()
					      )));
		
			$authRes = $this->Auth->login($user['User']); //TODO correct message if wrong user/pwd
			if ($authRes) {
				//TODO with auth scope??
				$tokens = $this->getUserTokens();
				if(!$tokens['isAdmin'] && $this->Auth->user('company_id') != $this->getCompanyId())
				{
				      $this->Session->setFlash(
						__('Invalid company for login'),
						'flash/fail'
					);
				    $this->logout('/users/login');
				}

				$cookieValue = array(
						'id' => $this->Auth->user('id'),
						'key' => AuthComponent::password(AuthComponent::password($this->data['User']['password']).$this->Auth->user('id'))
					);
				$this->Cookie->write('bakeryManagerUser', $cookieValue, true, '10 weeks');
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

	public function logout($url = Null) {
		if( Configure::read('Settings.demo.active') )
		{
			$this->Cookie->write('demoLogout', true, true, '10 weeks');
		}
		$this->Cookie->delete('bakeryManagerUser');
		if( $url == NULL )
		{
		  $url = $this->Auth->logout();
		}
		$this->redirect($url);
	}	
	

	public function setDemoEmail()
	{
	    if ($this->request->is('post') || $this->request->is('put')) {
	      $this->Session->write('demoEmail', $this->request->data['User']['email']);
	      $this->log($this->request->data['User']['email'], 'demoEmail');
	      $this->Session->setFlash(__('Adresse email prise en compte'),'flash/ok');
	      $this->redirect($this->request->data['User']['referer']);
	    }
	  $this->set('referer', $this->referer());
	}

	public function autologin()
	{
		if( Configure::read('Settings.demo.active') )
		{
			if(!$this->Cookie->check('demoLogout'))
			{
				$user = $this->User->find('first',array('conditions'=>array('User.email' => Configure::read('Settings.demo.User.email'))));
				if(isset($user['User']['id']))
				{
					$this->Auth->login($user['User']);
				}
			}
		}
	
	
		if($this->Cookie->check('bakeryManagerUser'))
		{
			$user = $this->User->find('first',array('conditions'=>array('User.id' => $this->Cookie->read('bakeryManagerUser.id'))));
			//TODO with auth scope??
			if(isset($user['User']) && $user['User']['company_id'] != $this->getCompanyId())
			{
			      $this->Session->setFlash(
					__('Invalid company for autologin'),
					'flash/fail'
				);
			    $this->log('invalid autologin cookie key for user '.$this->Cookie->read('bakeryManagerUser.id'), 'debug');
			    $this->Cookie->delete('bakeryManagerUser');
			    return;
			}

			if(isset($user['User']['id']))
			{
				$key = AuthComponent::password($user['User']['password'].$user['User']['id']);
				if($key == $this->Cookie->read('bakeryManagerUser.key'))
				{
					$this->Auth->login($user['User']);
				}
				else
				{
				  $this->log('invalid autologin cookie key for user '.$this->Cookie->read('bakeryManagerUser.id'), 'debug');
				  $this->Cookie->delete('bakeryManagerUser');
				}
			}
		      else
		      {
				$this->log('invalid autologin cookie: cannot find user id', 'debug');
				$this->Cookie->delete('bakeryManagerUser');
		      }
		}
	}

   public function beforeFilter()
  {
      parent::beforeFilter();
      if( Configure::read('Settings.Security.ssl') && !($this->Session->check('noSSL') && $this->Session->read('noSSL')))
      {
	  	if(!$this->request->is('ssl') && $this->request->params['action'] == 'login')
		{
			$this->blackHole('secure');
		}
      }
  }

}
