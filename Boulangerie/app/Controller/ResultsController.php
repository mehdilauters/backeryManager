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
  var $helpers = array('PhpExcel.PhpExcel');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$dateStart = date('d/m/Y');
		if(isset($this->request->data['dateStart']))
		{
		  $dateStart = $this->request->data['dateStart'];
		}
		
		$dateEnd = $dateStart;
		
		if(isset($this->request->data['dateEnd']))
		{
		  $dateEnd = $this->request->data['dateEnd'];
		}
		$this->Result->contain();
		$this->Result->contain('Shop','ResultsEntry');
		$data = $this->getData($dateStart, $dateEnd);

		$shops = $this->Result->Shop->find('list');
		$productTypes = $this->ProductType->find('list');

		if(isset($this->request->data['excelDownload']))
		{
 		 $this->layout = 'ajax'; 
 		 $this->view = 'excel';
// 		 $this->render('excel');
		 ob_end_clean();
		}
		$this->set(compact('data', 'dateStart', 'dateEnd', 'shops', 'productTypes', 'total'));

	}

	public function stats() {

		$this->Result->contain();
		$this->Result->contain('ResultsEntry', 'Shop');

		$results = $this->Result->find('all', array( 'order' => 'Result.date'));

		$shops = $this->Result->Shop->find('list');
		$productTypes = $this->ProductType->find('list');
		$this->set(compact('results', 'dateStart', 'dateEnd', 'shops', 'productTypes'));

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

public function getData($dateStart = '', $dateEnd = '')
{
  if($dateStart == '')
  {
    $dateStart = date('d/m/Y');
  }
  
  if($dateEnd == '')
  {
    $dateEnd = $dateStart;
  }
  
  if($dateEnd < $dateStart)
  {
    throw new NotFoundException(__('Invalid dates'));
  }

    $this->Result->contain();
    $this->Result->contain('ResultsEntry');

    App::uses('CakeTime', 'Utility');
    $dateSelect = CakeTime::daysAsSql($this->Functions->viewDateToDateTime($dateStart)->format('Y-m-d H:i:s'),$this->Functions->viewDateToDateTime($dateEnd)->format('Y-m-d H:i:s'), 'Result.date');

    $results = $this->Result->find('all', array( 'conditions'=>$dateSelect, 'order' => 'Result.date'));
    $data = array(
		  'total' => array('cash'=>0, 'check'=> 0),
		  );

    $ids = array();
    foreach($results as $result)
    {
      if(!isset($ids[$result['Result']['shop_id']]))
      {
	$ids[$result['Result']['shop_id']] = 0;
      }
      else
      {
	 $ids[$result['Result']['shop_id']] ++;
      }
      $i = $ids[$result['Result']['shop_id']];
      $data['entries'][$result['Result']['shop_id']]['entries'][$i] = array(
	  'cash' => $result['Result']['cash'],
	  'date' => $result['Result']['date'],
	  'check' => $result['Result']['check'],
	  'resultId' => $result['Result']['id'],
	  'productTypes' => array()
	);
      
      if(!isset($data['entries'][$result['Result']['shop_id']]['total']))
      {
	$data['entries'][$result['Result']['shop_id']]['total'] = array();
// 	debug($data['entries'][$result['Result']['shop_id']]);
	$data['entries'][$result['Result']['shop_id']]['total']['cash'] = 0;
	$data['entries'][$result['Result']['shop_id']]['total']['check'] = 0;
      }

	
	$data['entries'][$result['Result']['shop_id']]['total']['cash'] += $result['Result']['cash'];
	$data['entries'][$result['Result']['shop_id']]['total']['check'] += $result['Result']['check'];

	 $data['total']['cash'] += $result['Result']['cash'];
	 $data['total']['check'] += $result['Result']['check'];
      
      foreach($result['ResultsEntry'] as $resultEntry)
      {
	if(!isset($data['entries'][$result['Result']['shop_id']]['total'][$resultEntry['product_types_id']]))
	{
	  //debug($data['entries'][$result['Result']['shop_id']]);
	  $data['entries'][$result['Result']['shop_id']]['total'][$resultEntry['product_types_id']] = 0;
	}

	
	$data['entries'][$result['Result']['shop_id']]['total'][$resultEntry['product_types_id']] += $resultEntry['result'];

	if(!isset($data['total'][$resultEntry['product_types_id']]))
	{
	  $data['total'][$resultEntry['product_types_id']] = 0;
	}
	$data['total'][$resultEntry['product_types_id']] += $resultEntry['result'];


	$data['entries'][$result['Result']['shop_id']]['entries'][$i]['productTypes'][$resultEntry['product_types_id']]['result'] = $resultEntry['result'];
	$data['entries'][$result['Result']['shop_id']]['entries'][$i]['productTypes'][$resultEntry['product_types_id']]['resultEntryId'] = $resultEntry['id'];
      }
      $i++;
    }
    return $data;
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
			      );
			      if($result['cash'] != '')
			      {
				  $resultData['Result']['cash'] = $result['cash'];
			      }

			      if($result['check'] != '')
			      {
				  $resultData['Result']['check'] = $result['check'];
			      }
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
			    if($resultEntry['result'] != '')
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
			}


			if ($errors == 0 ) {
				$this->Session->setFlash(__('The result has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The result could not be saved. Please, try again.'));
			}
		}
		$shops = $this->Result->Shop->find('list');
		$productTypes = $this->ProductType->find('list');
		$data = $this->getData($date);
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
