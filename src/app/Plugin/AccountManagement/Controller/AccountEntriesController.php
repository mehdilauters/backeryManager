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
        var $administratorActions = array('*');


        
        public function check_account($id)
        {
          $tokens = $this->getUserTokens();
          if($tokens['isRoot'])
          {
            return true;
          }
          if(!$tokens['isAdmin'])
          {
            throw new NotFoundException(__('Invalid account entry'));
          }
          $accounts = $this->AccountEntry->Account->find('all', array('conditions'=>array('company_id'=>$this->getCompanyId())));

          $ok = false;
          foreach($accounts as $account)
          {
            if($account['Account']['id'] == $id)
            {
              return true;
            }
          }
          throw new NotFoundException(__('Invalid account entry'));
        }
        
        public function nameList()
        {
          if ($this->request->is('ajax'))
          {
            $this->check_account($this->request->data['account_id']);
            
            $data = $this->AccountEntry->find('all', array('conditions'=>array('AccountEntry.name like "%'.$this->request->data['query'].'%"', 'AccountEntry.account_id'=>$this->request->data['account_id'])));
            $results = array();
            foreach($data as $d)
            {
              $results[] = $d['AccountEntry']['name'];
            }
            $this->set(compact('results'));
            $this->set('_serialize', 'results');
          }
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
		$accountEntry = $this->AccountEntry->find('first', $options);
		$this->check_account($accountEntry['AccountEntry']['account_id']);
		$this->set('accountEntry', $accountEntry);
	}

/**
 * add method
 *
 * @return void
 */
	public function add($idAccount) {
                $this->check_account($idAccount);
		if ($this->request->is('post')) {
			$this->AccountEntry->create();
			$this->request->data['AccountEntry']['account_id'] = $idAccount;
			$this->request->data['AccountEntry']['date'] = $this->Functions->viewDateToDateTime($this->request->data['AccountEntry']['date'])->format('Y-m-d H:i:s');
			if ($this->request->is('ajax'))
			{
                          $this->request->data['AccountEntry']['checked'] = $this->request->data['AccountEntry']['checked'] == 'true';
                        }
			if ($this->AccountEntry->save($this->request->data)) {
                                if ($this->request->is('ajax'))
                                {
                                  $results = array('id'=>$this->AccountEntry->getInsertID(), 'total'=>$this->getTotal($idAccount),'status'=>true);
                                  $this->set(compact('results'));
                                  $this->set('_serialize', array('results'));
                                }
                                else
                                {
                                  $this->Session->setFlash(__('The account entry has been saved.'));
                                  return $this->redirect(array('controller'=>'accounts', 'action' => 'view', $idAccount));
                                }
			} else {
				$this->Session->setFlash(__('The account entry could not be saved. Please, try again.'));
				  $results = array('status'=>false);
                                  $this->set(compact('results'));
                                  $this->set('_serialize', array('results'));
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
		$accountEntry = $this->AccountEntry->findById($id);
		
		$this->check_account($accountEntry['AccountEntry']['account_id']);
		
		$idAccount = $accountEntry['AccountEntry']['account_id'];
		if ($this->request->is(array('post', 'put'))) {
                        $this->request->data['AccountEntry']['date'] = $this->Functions->viewDateToDateTime($this->request->data['AccountEntry']['date'])->format('Y-m-d H:i:s');
                        if ($this->request->is('ajax'))
                        {
                          $this->request->data['AccountEntry']['checked'] = $this->request->data['AccountEntry']['checked'] == 'true';
                        }
			if ($this->AccountEntry->save($this->request->data)) {
				$this->Session->setFlash(__('The account entry has been saved.'));
                                if ($this->request->is('ajax'))
                                {
                                  $results = array('id'=>$id, 'total'=>$this->getTotal($idAccount),'status'=>true);
                                  $this->set(compact('results'));
                                  $this->set('_serialize', array('results'));
                                }
                                else
                                {
                                  return $this->redirect(array('controller'=>'accounts', 'action' => 'view', $idAccount));
                                }
			} else {
				$this->Session->setFlash(__('The account entry could not be saved. Please, try again.'));
				if ($this->request->is('ajax'))
                                {
                                  $results = array('id'=>$id, 'status'=>false);
                                  $this->set(compact('results'));
                                  $this->set('_serialize', array('results'));
                                }
			}
		} else {
			$options = array('conditions' => array('AccountEntry.' . $this->AccountEntry->primaryKey => $id));
			$this->request->data = $this->AccountEntry->find('first', $options);
		}
		$accounts = $this->AccountEntry->Account->find('list');
		$date = new DateTime($accountEntry['AccountEntry']['date']);
		$this->request->data['AccountEntry']['date'] = $date->format('d/m/Y');
		$this->set(compact('accounts'));
	}

	public function getTotal($idAccount)
	{
            return $this->requestAction(array('controller'=>'accounts', 'action'=>'getTotal', $idAccount));
	}
	
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null, $idAccount=null) {
		$this->AccountEntry->id = $id;
		if (!$this->AccountEntry->exists()) {
			throw new NotFoundException(__('Invalid account entry'));
		}
                $accountEntry = $this->AccountEntry->findById($id);
                
                $this->check_account($accountEntry['AccountEntry']['account_id']);
                
                $idAccount = $accountEntry['AccountEntry']['account_id'];
		$this->request->onlyAllow('post', 'delete');
		if ($this->AccountEntry->delete()) {
                         $ok = true;
			$this->Session->setFlash(__('The account entry has been deleted.'));
		} else {
                        $ok = false;
			$this->Session->setFlash(__('The account entry could not be deleted. Please, try again.'));
		}
                if ($this->request->is('ajax'))
                {
                  $results = array('total'=>$this->getTotal($idAccount),'status'=>$ok);
                  $this->set(compact('results'));
                  $this->set('_serialize', array('results'));
                }
                else
                {
                  return $this->redirect(array('controller'=>'accounts', 'action' => 'view', $idAccount));
                }
	}}
