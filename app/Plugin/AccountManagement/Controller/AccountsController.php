<?php
App::uses('AccountManagementAppController', 'AccountManagement.Controller');
/**
 * Accounts Controller
 *
 * @property Account $Account
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class AccountsController extends AccountManagementAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');
	var $administratorActions = array('*');
	var $uses = array('AccountManagement.Account','AccountManagement.AccountEntry');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Account->recursive = 0;
		$tokens = $this->getUserTokens();
		$conditions = array();
		if( ! $tokens['isRoot'] )
		{
                  $conditions = array('Account.company_id' => $this->getCompanyId());
		}
		$this->set('accounts', $this->Paginator->paginate($conditions));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Account->exists($id)) {
			throw new NotFoundException(__('Invalid account'));
		}
		$options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
                $tokens = $this->getUserTokens();
                if( ! $tokens['isRoot'] )
                {
                  $options['conditions']['Account.company_id'] = $this->getCompanyId();
                }
		$this->set('account', $this->Account->find('first', $options));
		$this->set('total', $this->getTotal($id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Account->create();
                        $tokens = $this->getUserTokens();
                        $conditions = array();
                        if( ! $tokens['isRoot'] )
                        {
                          $this->request->data['Account']['company_id'] = $this->getCompanyId();
                        }
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		}
		$companies = $this->Account->Company->find('list');
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
		if (!$this->Account->exists($id)) {
			throw new NotFoundException(__('Invalid account'));
		}
		if ($this->request->is(array('post', 'put'))) {
                        $tokens = $this->getUserTokens();
                        if( ! $tokens['isRoot'] )
                        {
                          $this->request->data['Account']['company_id'] = $this->getCompanyId();
                        }
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
			$this->request->data = $this->Account->find('first', $options);
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
		$this->Account->id = $id;
		if (!$this->Account->exists()) {
			throw new NotFoundException(__('Invalid account'));
		}
		$this->request->onlyAllow('post', 'delete');
		
                $tokens = $this->getUserTokens();
                $account = $this->Account->findById($id);
                if( ! $tokens['isRoot'] && $account['Account']['company_id'] != $this->getCompanyId() )
                {
                  throw new NotFoundException(__('Invalid account for this company'));
                }
		
		if ($this->Account->delete()) {
			$this->Session->setFlash(__('The account has been deleted.'));
		} else {
			$this->Session->setFlash(__('The account could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	
	
        public function getTotal($idAccount)
        {
          $total = $this->AccountEntry->find('first', 
            array('conditions'=> array('account_id'=>$idAccount),
              'fields' => array('SUM(value) as `total`')
              )
            );
            return $total[0]['total'];
        }
	
	}
	
