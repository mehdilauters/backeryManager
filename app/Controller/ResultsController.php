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
  public function index($dateStart = NULL, $dateEnd = NULL, $fileName = NULL) {
    
    if($dateStart == NULL)
    {
      $dateStart = date('01/m/Y');
      if(isset($this->request->data['dateStart']))
      {
        $dateStart = $this->request->data['dateStart'];
      }
    }

    if($dateEnd == NULL)
    {    
      $dateEnd = date('d/m/Y');
      
      if(isset($this->request->data['dateEnd']))
      {
        $dateEnd = $this->request->data['dateEnd'];
      }
    }


    $this->Result->contain();
    $this->Result->contain('Shop','ResultsEntry');
    $data = $this->getData($dateStart, $dateEnd);

    $shops = $this->Result->Shop->find('list');
    $productTypes = $this->ProductType->find('list');
    if(isset($this->request->data['excelDownload']) || $fileName != NULL)
    {
      $this->set('fileName', $fileName);
      $this->layout = 'ajax'; 
      $this->view = 'excel';
//      $this->render('excel');
      //instantiate a new View class from the controller
      
    }
    $this->set(compact('data', 'dateStart', 'dateEnd', 'shops', 'productTypes', 'total'));


        if($fileName != NULL)
        {
    $view = new View($this);
    $viewdata = $view->render(null,'html','html');

        }
      else
      {
        ob_end_clean();
      }
  }

  public function stats($_conditions = array(), $group = array()) {

    $groupBy = array();
	$nbDaysByInterval = 1;
	
	if(count($group) == 0)
	{
		if(isset($this->request->data['group']))
		{
			$group = $this->request->data['group'];
		}
		else
		{
		  $group['time'] = 'month';
		  $group['shop'] = 'shop';
		  $group['productType'] = 'productType';
		}
	}
	
    if(count($group) != 0)
    {
		if(isset($group['time']))
		{
		  switch($group['time'])
		  {
			case 'weekday':
				  //$groupBy[] = 'YEARWEEK(Result.date, 1)';
				  $groupBy[] = 'DAYNAME(Result.date)';
			break;
			case 'day':
				  $groupBy[] = 'Result.date';
			break;
			case 'week':
				$groupBy[] = 'YEARWEEK(Result.date, 1)';
				$nbDaysByInterval = 7;
			break;
			case 'month':
				$groupBy[] = 'DATE_FORMAT(Result.date,"%Y-%m")';
				$nbDaysByInterval = 31;
			break;
			case 'year':
				$groupBy[] = 'YEAR(Result.date)';
				$nbDaysByInterval = 365;
			break;
			default:
			
			break;
		  }
		}
		

     if(isset($group['shop']))
		{
		  switch($group['shop'])
			  {
			case 'shop':
			 $groupBy[] = 'Result.shop_id';
			break;
			default:
		  //     $groupBy[] = 'Sale.shop_id';
			break;
		  }
		}
	}
    $groupByEntries = str_replace('Result', 'ResultsEntry',$groupBy);
      if(count($groupBy) == 0)
      {
		$groupBy[] = 'Result.date, Result.shop_id';
      }
	  
	  if(isset($group['productType']))
	  {
		switch($group['productType'])
		  {
			case 'productType':
			 $groupByEntries[] = 'ResultsEntry.product_types_id';
			break;
			default:
		       //$groupByEntries[] = 'ResultsEntry.product_types_id';
			break;
		  }
	  }

	  if(count($groupByEntries) == 0)
      {
        $groupByEntries[] = 'ResultsEntry.result_id';
		$groupByEntries[] = 'ResultsEntry.product_types_id';
      }

	  
	  $conditions = array('Result'=>array(),'ResultsEntry'=>array());
	  
	  if(count($_conditions) == 0)
	{
		if(isset($this->request->data['conditions']))
		{
			$_conditions = $this->request->data['conditions'];
		}
	}
	
    if(count($_conditions) != 0)
    {
		if(isset($_conditions['shop']) && $_conditions['shop'] != '')
		{
			$conditions['Result']['Result.shop_id'] =  $_conditions['shop'];
			$conditions['ResultsEntry']['ResultsEntry.shop_id'] =  $_conditions['shop'];
		}
		if(isset($_conditions['productType']) && $_conditions['productType'] != '')
		{
			$conditions['ResultsEntry']['ResultsEntry.product_types_id'] =  $_conditions['productType'];
		}
		
		if(isset($_conditions['ResultsEntry']))
		{
			$conditions['ResultsEntry'] += $_conditions['ResultsEntry'];
		}
	}
	  
    $this->Result->contain('Shop');
     $results = $this->Result->find('all', array('order'=>array('Result.date'),
              'group' => $groupBy,
			  'conditions' => $conditions['Result'],
              'fields' => array('SUM(Result.cash) as `cash`',
                    'SUM(Result.check) as `check`',
					'SUM(Result.card) as `card`',
                    'SUM(Result.check + Result.cash + Result.card) as `total`',
                    'Result.date',
                    'Result.comment',
                    'Result.shop_id',
					'Shop.name',
                    ),
              ));
	require_once(APP . 'Vendor' . DS . 'PolynomialRegression.php');
	bcscale( Configure::read('Approximation.bcscale') );
	$regressions = array();
	$initDate = array();
	
	// add data to regression
	$nbResults = count($results);
	for($i=0; $i< ($nbResults-1); $i++)
	{
		$res = $results[$i];
		if(!isset($regressions[$res['Result']['shop_id']]))
		{
			$regressions[$res['Result']['shop_id']] = new PolynomialRegression( Configure::read('Approximation.order') );
			$initDate[$res['Result']['shop_id']] = new DateTime($res['Result']['date']);
		}
		$curDate = new DateTime($res['Result']['date']);
		$dateDiff = $initDate[$res['Result']['shop_id']]->diff($curDate);
		$x = $dateDiff->days / $nbDaysByInterval;
		$y = $res[0]['total'];
		
		$regressions[$res['Result']['shop_id']]->addData( $x, $y );
	}
	
	
	// get equation parameters
	$approximations = array();
	
	foreach($regressions as $shopId => $regression)
	{
		$approximation[$shopId] = $regressions[$shopId]->getCoefficients();
	}
	$lastDate = NULL;
	// fill results
	foreach($results as &$res)
	{
		$curDate = new DateTime($res['Result']['date']);
		$dateDiff = $initDate[$res['Result']['shop_id']]->diff($curDate);
		$x = $dateDiff->days / $nbDaysByInterval;
		$y = $regressions[$shopId]->interpolate($approximation[$res['Result']['shop_id']],$x);
		if($y < 0)
		{
			$y =0;
		}
		$res[0]['approximation'] = $y;
		$lastDate = $curDate;
	}
	
	// extrapolate to future
	$maxX = Configure::read('Approximation.nbProjectionsPoint');
	for($i = 0; $i < $maxX; $i++)
	{
		$res = array(
			0 => array(
						'cash' => '',
						'check' => '',
						'card' => '',
						'total' => '',
						'approximation' => 0,
						),
			'Result' => array(
							'date' => '',
							'comment' => 'Approximation',
							'shop_id' => ''
							),
			'Shop' => array()
			);
		$lastDate->modify('+'.($nbDaysByInterval).' day');
		foreach($regressions as $shopId => $regression)
		{
			$dateDiff = $initDate[$shopId]->diff($lastDate);
			$x = $dateDiff->days / $nbDaysByInterval;
			$y = $regressions[$shopId]->interpolate($approximation[$shopId],$x);
			$res[0]['approximation'] = $y;
			$res['Result']['date']  = $lastDate->format('Y-m-d H:i:s');
			$res['Result']['shop_id']  = $shopId;
			$res['Shop']['id'] = $shopId;
			$res['Shop']['name'] = 'tmp_'.$shopId;
			$results[] = $res;
		}		
	}
	
	
     //debug($groupByEntries );
    $this->Result->ResultsEntry->contain('Shop', 'ProductTypes');
     $resultsEntries = $this->Result->ResultsEntry->find('all', array('order'=>array('ResultsEntry.date'),
              'group' => $groupByEntries,
			  'conditions' => $conditions['ResultsEntry'],
              'fields' => array('SUM(ResultsEntry.result) as `result`',
								'Shop.name',
								'ResultsEntry.date',
								'ProductTypes.name',
								),
			  'order' => 'ResultsEntry.date'
              ));
     // debug($resultsEntries ); 
	 
	 
	 	// add data to regression
	$nbResultsEntries = count($resultsEntries);
	$regressions = array();
	$initDate = array();
	for($i=0; $i< ($nbResultsEntries-1); $i++)
	{
		$res = $resultsEntries[$i];
		$productTypeId = $res['ProductTypes']['id'];
		$shopId = $res['Shop']['id'];
		// debug($shopId);
		// debug($productTypeId);
		if(!isset($regressions[$productTypeId]))
		{
			$regressions[$productTypeId] = array();
			$initDate[$productTypeId] = array();
		}
		if(!isset($regressions[$productTypeId][$shopId]))
		{
			$regressions[$productTypeId][$shopId] = new PolynomialRegression( Configure::read('Approximation.order') );
			$initDate[$productTypeId][$shopId] = new DateTime($res['ResultsEntry']['date']);
			// debug($regressions);
		}
		$curDate = new DateTime($res['ResultsEntry']['date']);
		$dateDiff = $initDate[$res['ProductTypes']['id']][$res['Shop']['id']]->diff($curDate);
		$x = $dateDiff->days / $nbDaysByInterval;
		$y = $res[0]['result'];
		
		$regressions[$res['ProductTypes']['id']][$res['Shop']['id']]->addData( $x, $y );
	}
	 
	 
	 // get equation parameters
	$approximations = array();
	
	foreach($regressions as $ProductTypeId => $regression)
	{
		foreach($regression as $shopId => $regressionProductType)
		{
			$approximation[$ProductTypeId][$shopId] = $regressions[$ProductTypeId][$shopId]->getCoefficients();
		}
	}
	$lastDate = NULL;
	// fill results
	foreach($resultsEntries as &$res)
	{
		$productTypeId = $res['ProductTypes']['id'];
		$shopId = $res['Shop']['id'];
		$curDate = new DateTime($res['ResultsEntry']['date']);
		$dateDiff = $initDate[$productTypeId][$shopId]->diff($curDate);
		$x = $dateDiff->days / $nbDaysByInterval;
		$y = $regressions[$ProductTypeId][$shopId]->interpolate($approximation[$productTypeId][$shopId],$x);
		if($y < 0)
		{
			$y =0;
		}
		$res[0]['approximation'] = $y;
		$lastDate = $curDate;
	}
	
	
	// extrapolate to future
	$maxX = Configure::read('Approximation.nbProjectionsPoint');
	for($i = 0; $i < $maxX; $i++)
	{
		$res = array(
			0 => array(
						'result' => '',
						'approximation' => 0,
						),
			'ResultsEntry' => array(
							'date' => '',
							'comment' => 'Approximation',
							'shop_id' => ''
							),
			'Shop' => array(
						'id' => '',
						'name'=>'',
							),
			'ProductTypes' => array(
						'id' => '',
						'name'=>'',
							)
			);
		$lastDate->modify('+'.($nbDaysByInterval).' day');
		foreach($regressions as $ProductTypeId => $regression)
		{
			foreach($regression as $shopId => $regressionProductType)
			{
				 $dateDiff = $initDate[$ProductTypeId][$shopId]->diff($lastDate);
				 $x = $dateDiff->days / $nbDaysByInterval;
				 $y = $regressions[$ProductTypeId][$shopId]->interpolate($approximation[$ProductTypeId][$shopId],$x);
				 $res[0]['approximation'] = $y;
				 $res['Result']['date']  = $lastDate->format('Y-m-d H:i:s');
				 $res['Result']['shop_id']  = $ProductTypeId;
				 $res['Shop']['id'] = $shopId;
				 $res['Shop']['name'] = 'tmp_'.$shopId;
				$resultsEntries[] = $res;
			}
		}	
	}
	 
	 
	// $products = Set::combine($products, '{n}.Product.id', '{n}');
    $shops = $this->Result->Shop->find('list');
    $productTypes = $this->ProductType->find('list');
	if (!empty($this->request->params['requested'])) {
            return compact('results', 'resultsEntries', 'dateStart', 'dateEnd', 'shops', 'productTypes');
        }
    $this->set(compact('results', 'resultsEntries', 'dateStart', 'dateEnd', 'shops', 'productTypes'));

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
    $dateStart = date('01/m/Y');
  }
  else
  {
    $dateStart = $this->Functions->viewDateToDateTime($dateStart);
  }
  
  if($dateEnd == '')
  {
    $dateEnd = date('d/m/Y');
  }
  else
  {
    $dateEnd = $this->Functions->viewDateToDateTime($dateEnd);
  }
  
  if($dateEnd < $dateStart)
  {
    throw new NotFoundException(__('Invalid dates '.$dateStart.'===='.$dateEnd));
  }

    $this->Result->contain();
    $this->Result->contain('ResultsEntry');

    App::uses('CakeTime', 'Utility');
    $dateSelect = CakeTime::daysAsSql($dateStart->format('Y-m-d H:i:s'),$dateEnd->format('Y-m-d H:i:s'), 'Result.date');

    $results = $this->Result->find('all', array( 'conditions'=>$dateSelect, 'order' => 'Result.date'));
    $data = array(
      'total' => array('cash'=>0, 'check'=> 0, 'card'=>0),
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
	'card' => $result['Result']['card'],
    'comment' => $result['Result']['comment'],
    'resultId' => $result['Result']['id'],
    'productTypes' => array()
  );
      
      if(!isset($data['entries'][$result['Result']['shop_id']]['total']))
      {
  $data['entries'][$result['Result']['shop_id']]['total'] = array();
//   debug($data['entries'][$result['Result']['shop_id']]);
  $data['entries'][$result['Result']['shop_id']]['total']['cash'] = 0;
  $data['entries'][$result['Result']['shop_id']]['total']['check'] = 0;
  $data['entries'][$result['Result']['shop_id']]['total']['card'] = 0;
      }

  
  $data['entries'][$result['Result']['shop_id']]['total']['cash'] += $result['Result']['cash'];
  $data['entries'][$result['Result']['shop_id']]['total']['check'] += $result['Result']['check'];
  $data['entries'][$result['Result']['shop_id']]['total']['card'] += $result['Result']['card'];

   $data['total']['cash'] += $result['Result']['cash'];
   $data['total']['check'] += $result['Result']['check'];
   $data['total']['card'] += $result['Result']['card'];
      
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
			 if($result['card'] != '')
            {
				$resultData['Result']['card'] = $result['card'];
            }
            if($result['resultId'] != '')
            {
        $resultData['Result']['id'] = $result['resultId'];
            }
        $resultData['Result']['comment'] = $result['comment'];
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
            'date' => $resultData['Result']['date'],
            'shop_id' => $resultData['Result']['shop_id'],
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
        $this->Session->setFlash(__('The result has been saved'),'flash/ok');
        $this->redirect(array('action' => 'add'));
      } else {
        $this->Session->setFlash(__('The result could not be saved. Please, try again.'),'flash/fail');
      }
    }
    $shops = $this->Result->Shop->find('list');
    $productTypes = $this->ProductType->find('list');
    $data = $this->getData($date, $date);
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
        $this->Session->setFlash(__('The result has been saved'),'flash/ok');
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The result could not be saved. Please, try again.'),'flash/fail');
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
      $this->Session->setFlash(__('Result deleted'),'flash/ok');
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Result was not deleted'),'flash/fail');
    $this->redirect(array('action' => 'index'));
  }
}
