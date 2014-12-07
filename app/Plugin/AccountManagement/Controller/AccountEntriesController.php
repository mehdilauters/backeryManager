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
			if ($this->request->is('ajax'))
			{
                          $this->request->data['AccountEntry']['checked'] = $this->request->data['AccountEntry']['checked'] == 'true';
                        }
			if ($this->AccountEntry->save($this->request->data)) {
                                if ($this->request->is('ajax'))
                                {
                                  echo json_encode( array('id'=>$this->AccountEntry->getInsertID(), 'total'=>$this->getTotal($idAccount)));
                                  return;
                                }
				$this->Session->setFlash(__('The account entry has been saved.'));
				return $this->redirect(array('controller'=>'accounts', 'action' => 'view', $idAccount));
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
		$accountEntry = $this->AccountEntry->findById($id);
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
                                  echo json_encode( array('id'=>$id, 'total'=>$this->getTotal($idAccount)));
                                  return;
                                }
 				return $this->redirect(array('controller'=>'accounts', 'action' => 'view', $idAccount));
			} else {
				$this->Session->setFlash(__('The account entry could not be saved. Please, try again.'));
				if ($this->request->is('ajax'))
                                {
                                  echo -1;
                                  return;
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
                  echo json_encode( array('status'=>$ok, 'total'=>$this->getTotal($idAccount)));
                  return;
                }
		return $this->redirect(array('controller'=>'accounts', 'action' => 'view', $idAccount));
	}}
