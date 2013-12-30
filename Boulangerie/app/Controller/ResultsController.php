<?php
App::uses('AppController', 'Controller');
/**
 * Results Controller
 *
 * @property Result $Result
 */
class ResultsController extends AppController {
  var $uses = array('Result', 'ProductType', 'ResultsEntry');
  var $components = array('Functions');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Result->recursive = 0;
		$results = $this->Result->find('all');
		$this->set('results', $results);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Result->exists($id)) {
			throw new NotFoundException(__('Invalid result'));
		}
		$options = array('conditions' => array('Result.' . $this->Result->primaryKey => $id));
		$this->set('result', $this->Result->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$date = date('d/m/Y');
		if(isset($this->request->data['date']))
		{
		   $date = $this->request->data['date'];
		}
		if ($this->request->is('post') && !isset($this->request->data['dateSelect'])) {
			$errors = 0;
			



			foreach($this->request->data['Result'] as $shopId => $result)
			{
			    $this->Result->create();
			     $resultData = array();
			     $resultData['Result'] = array(
				'date' => $this->Functions->viewDateToDateTime($date)->format('Y-m-d H:i:s'),
				'shop_id'=> $shopId,
				'cash' => $result['cash'],
				'check' => $result['check'],
			      );
			      if($result['resultId'] != '')
			      {
				$resultData['Result']['id'] = $result['resultId'];
			      }
			  if (!$this->Result->save($resultData)) {
				$errors ++;  
			  }
			  if($result['resultId'] != '')
			  {
			    $resultId = $result['resultId'];
			  }
			  else
			  {
			    $resultId = $this->Result->getInsertID();
			  }
			  foreach($result['productTypes'] as $typeId => $resultEntry)
			  {
			      $this->ResultsEntry->create();
			      $resultEntryData = array();
			      $resultEntryData['ResultsEntry'] = array(
				  'result_id' => $resultId,
				  'product_types_id' => $typeId,
				  'result' => $resultEntry['result'],
				);
			      if($resultEntry['resultEntryId'] != '')
			      {
				$resultEntryData['ResultsEntry']['id'] = $resultEntry['resultEntryId'];
			      }
			    if (!$this->ResultsEntry->save($resultEntryData)) {
				  $errors ++;  
			    }
			  }
			}



			$this->Result->create();
			if ($this->Result->save($this->request->data)) {
				$this->Session->setFlash(__('The result has been saved'));
				//$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The result could not be saved. Please, try again.'));
			}
		}
		$shops = $this->Result->Shop->find('list');
		$productTypes = $this->ProductType->find('list');
		$this->Result->contain();
		$this->Result->contain('ResultsEntry');

		App::uses('CakeTime', 'Utility');
		$dateSelect = CakeTime::daysAsSql($this->Functions->viewDateToDateTime($date)->format('Y-m-d H:i:s'),$this->Functions->viewDateToDateTime($date)->format('Y-m-d H:i:s'), 'Result.date');

		$results = $this->Result->find('all', array( 'conditions'=>$dateSelect));
		$data = array();
		foreach($results as $result)
		{
		  $data[$result['Result']['shop_id']] = array(
		      'cash' => $result['Result']['cash'],
		      'check' => $result['Result']['check'],
		      'resultId' => $result['Result']['id'],
		      'productTypes' => array()
		    );
		  foreach($result['ResultsEntry'] as $resultEntry)
		  {
		    $data[$result['Result']['shop_id']]['productTypes'][$resultEntry['product_types_id']]['result'] = $resultEntry['result'];
		    $data[$result['Result']['shop_id']]['productTypes'][$resultEntry['product_types_id']]['resultEntryId'] = $resultEntry['id'];
		  }
		}
		$this->set(compact('shops','productTypes', 'date', 'data'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Result->exists($id)) {
			throw new NotFoundException(__('Invalid result'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Result->save($this->request->data)) {
				$this->Session->setFlash(__('The result has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The result could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Result.' . $this->Result->primaryKey => $id));
			$this->request->data = $this->Result->find('first', $options);
		}
		$shops = $this->Result->Shop->find('list');
		$this->set(compact('shops'));
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
		$this->Result->id = $id;
		if (!$this->Result->exists()) {
			throw new NotFoundException(__('Invalid result'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Result->delete()) {
			$this->Session->setFlash(__('Result deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Result was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
