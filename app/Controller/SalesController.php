<?php
App::uses('AppController', 'Controller');
/**
 * Sales Controller
 *
 * @property Sale $Sale
 */
class SalesController extends AppController {

  var $helpers = array('Time');
  var $components = array('Functions');
  var $uses = array('Sale', 'Product', 'Shop', 'ProductType');
/**
 * index method
 *
 * @return void
 */
  public function index() {
    $this->Sale->contain('Product.Media.Photo', 'Shop.Media.Photo');
    $this->set('sales', $this->paginate());
  }

  

	public function dashboard() {

		$this->set('title_for_layout', 'Tableau de bord');
		$now = new DateTime();
		
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array(), 'group' => array('time'=>'week', 'shop'=>'shop'))));
		$this->set('results',$res['results']);
		
		
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array(), 'group' => array('time'=>'week', 'productType'=>'productType'))));
		$this->set('resultsEntries',$res['resultsEntries']);
	/*	
		// select current days historic (ie Monday) lost/produced...
		$res = $this->requestAction(array('controller'=>'sales', 'action'=>'stats'), array( 'pass'=>array('conditions'=>
																										array(
																											'DAYNAME(Sale.date) = DAYNAME(\''.$now->format('Y-m-d H:i:s').'\')'
																											),
																											'group' => array('time'=>'week', 'shop'=>'shop', 'product'=>''))));
		$this->set(compact('products','shops'));
		$this->set('sales',$res['sales']);
		$this->set('products',$res['products']);
		$this->set('shops',$res['shops']);
	*/	
		// select current days historic (ie Monday) productTypes...
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array( 'ResultsEntry' => array(
																																	'DAYNAME(ResultsEntry.date) = DAYNAME(\''.$now->format('Y-m-d H:i:s').'\')'
																																)), 'group' => array('time'=>'day', 'productType'=>'productType'))));
		$this->set('dayStats',$res['resultsEntries']);
		
		
		
		// select current days historic (ie 21/03) productsTypes...
		$res = $this->requestAction(array('controller'=>'sales', 'action'=>'stats'), array( 'pass'=>array('conditions'=>
																										array(
																											'DATE_FORMAT(Sale.date,"%m-%d") = DATE_FORMAT(\''.$now->format('Y-m-d H:i:s').'\', "%m-%d")'
																											),
																											'group' => array('time'=>'day', 'shop'=>'shop', 'product'=>''))));
		$this->set(compact('products','shops'));
		$this->set('sales',$res['sales']);
		$this->set('products',$res['products']);
		$this->set('shops',$res['shops']);
	}


    public function stats($conditions = array(), $group = array()) {


    $groupBy = array();
	if (empty($this->request->params['requested'])) {
      $dateStart = date('01/m/Y');
      if(isset($this->request->data['dateStart']))
      {
        $dateStart = $this->request->data['dateStart'];
      }

      $dateEnd = date('d/m/Y');     
      if(isset($this->request->data['dateEnd']))
      {
        $dateEnd = $this->request->data['dateEnd'];
      }


      $dateStart = $this->Functions->viewDateToDateTime($dateStart);
      $dateEnd = $this->Functions->viewDateToDateTime($dateEnd);

  if($dateEnd < $dateStart)
  {
    throw new NotFoundException(__('Invalid dates '.$dateStart.'===='.$dateEnd));
  }


      App::uses('CakeTime', 'Utility');
      $dateSelect = CakeTime::daysAsSql($dateStart->format('Y-m-d H:i:s'),$dateEnd->format('Y-m-d H:i:s'), 'Sale.date');

      $conditions[] = $dateSelect;
	}
	if(count($group) == 0)
	{
		if(isset($this->request->data['group']))
		{
			$group = $this->request->data['group'];
		}
		else
		{
		    $group['time'] = 'day';
		    $group['shop'] = 'shop';
		    $group['product'] = 'product';
			$this->request->data['group'] = $group;
		}
	}
	$nbDaysByInterval = 1;

    if($group)
    {
      switch($group['time'])
      {
	case 'weekday':
	  $groupBy[] = 'DAYNAME(Sale.date)';
	break;
	case 'day':
	  $groupBy[] = 'Sale.date';
	break;
	case 'week':
		$nbDaysByInterval = 7;
	  $groupBy[] = 'YEARWEEK(Sale.date, 1)';
	break;
	case 'month':
		$nbDaysByInterval = 31;
	  $groupBy[] = 'DATE_FORMAT(Sale.date,"%Y-%m")';
	break;
	case 'year':
		$nbDaysByInterval = 365;
	  $groupBy[] = 'YEAR(Sale.date)';
	break;
	default:

	break;
      }

      switch($group['product'])
      {
	case 'product':
 	  $groupBy[] = 'Sale.product_id';
	break;
	default:
// 	  $groupBy[] = 'Sale.product_id';
	break;
      }
      switch($group['shop'])
      {
	case 'shop':
 	  $groupBy[] = 'Sale.shop_id';
	break;
	default:
// 	  $groupBy[] = 'Sale.shop_id';
	break;
      }
    }
      if(count($groupBy) == 0)
      {
	$groupBy[] = 'Sale.date, Sale.product_id, Sale.shop_id';
      }
    $this->Sale->contain('Product.ProductType');
    $sales = $this->Sale->find('all', array('order'=>array('Sale.date'),
					    'group' => $groupBy,
					    'conditions' => $conditions,
					    'fields' => array('SUM(Sale.produced) as `produced`', 
							      'SUM(Sale.lost) as `lost`',
							      'SUM(Sale.produced - Sale.lost) as `sold`',
							      'SUM((Sale.produced - Sale.lost) * Sale.price) as `totalPrice`',
							      'SUM(Sale.lost * Sale.price) as `totalLost`',
							      'Sale.date',
							      'Sale.comment',
							      'Sale.shop_id',
							      'Sale.product_id',
// 							      'Product.product_types_id'
							      )
					    ));
						
						
	require_once(APP . 'Vendor' . DS . 'PolynomialRegression.php');
	bcscale( Configure::read('Approximation.bcscale') );
	$regressions = array();
	$initDate = NULL;
	
	// add data to regression
	$nbsales = count($sales);
	for($i=0; $i< ($nbsales-1); $i++)
	{
		$res = $sales[$i];
		if(!isset($regressions['produced']))
		{
			$regressions['produced'] = new PolynomialRegression( Configure::read('Approximation.order') );
			$regressions['lost'] = new PolynomialRegression( Configure::read('Approximation.order') );
			$regressions['sold'] = new PolynomialRegression( Configure::read('Approximation.order') );
			$regressions['totalPrice'] = new PolynomialRegression( Configure::read('Approximation.order') );
			$regressions['totalLost'] = new PolynomialRegression( Configure::read('Approximation.order') );
			$initDate = new DateTime($res['Sale']['date']);
		}
		$curDate = new DateTime($res['Sale']['date']);
		$dateDiff = $initDate->diff($curDate);
		$x = $dateDiff->days / $nbDaysByInterval;
		
		foreach($regressions as $name => $regression)
		{
			$regressions[$name]->addData( $x, $res[0][$name] );
		}
		
	}
	

	// get equation parameters
	$approximations = array();
	
	foreach($regressions as $name => $regression)
	{
		$approximation[$name] = $regressions[$name]->getCoefficients();
	}
	
	$lastDate = NULL;
	// fill results
	foreach($sales as &$sale)
	{
		$curDate = new DateTime($sale['Sale']['date']);
		$dateDiff = $initDate->diff($curDate);
		$x = $dateDiff->days / $nbDaysByInterval;
		
		foreach($regressions as $name => $regression)
		{
			$y = $regressions[$name]->interpolate($approximation[$name],$x);
			if($y < 0)
			{
				$y =0;
			}
			$res[0][$name.'Approximation'] = $y;
		}
		$lastDate = $curDate;
	}	
 
 // extrapolate to future
	$maxX = Configure::read('Approximation.nbProjectionsPoint');
	for($i = 0; $i < $maxX; $i++)
	{
		$res = array(
			0 => array(
						'prodeuced' => '',
						'lost' => '',
						'sold' => '',
						'totalPrice' => '',
						'totalLost' => '',
						'producedApproximation' => 0,
						'lostApproximation' => 0,
						'soldApproximation' => 0,
						'totalPriceApproximation' => 0,
						'totalLostApproximation' => 0
						),
			'Sale' => array(
							'date' => '',
							'comment' => 'Approximation',
							'shop_id' => ''
							),
			'Product' => array(
					'id' => '',
					'ProductType' => array()
					)
			);
		$lastDate->modify('+'.($nbDaysByInterval).' day');
		foreach($regressions as $shopId => $regression)
		{
			$dateDiff = $initDate[$shopId]->diff($lastDate);
			$x = $dateDiff->days / $nbDaysByInterval;
			foreach($regressions as $name => $regression)
			{
				$y = $regressions[$name]->interpolate($approximation[$name],$x);
				if($y < 0)
				{
					$y =0;
				}
				$res[0][$name.'Approximation'] = $y;
			}
			$res['Sale']['date']  = $lastDate->format('Y-m-d H:i:s');
			$res['Product']['id']  = $shopId;
			$res['Sale']['shop_id'] = $shopId;
			$results[] = $res;
		}		
	}
 

    $this->Sale->Product->contain('ProductType');
    $products = $this->Sale->Product->find('all');
    $products = Set::combine($products, '{n}.Product.id', '{n}');
    $shops = $this->Sale->Shop->find('list');

    if (!empty($this->request->params['requested'])) {
            return compact('sales','products', 'shops');
        }
    $this->set('sales', $sales);

$dateStart = $dateStart->format('d/m/Y');
$dateEnd = $dateEnd->format('d/m/Y');
    $this->set(compact('products', 'shops','dateStart','dateEnd'));
  }
  
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function view($id = null) {
    if (!$this->Sale->exists($id)) {
      throw new NotFoundException(__('Invalid sale'));
    }
    $options = array('conditions' => array('Sale.' . $this->Sale->primaryKey => $id));
    $sale = $this->Sale->find('first', $options);
    //debug($sale);
	$this->set(array(
            'sale' => $sale,
            '_serialize' => array('sale')
        ));
  }


public function results()
{
    $dateStart = date('d/m/Y');
    $dateEnd = date('d/m/Y');
    if(isset($this->request->data['dateStart']))
    {
  $dateStart = $this->request->data['dateStart'];
    }
    if(isset($this->request->data['dateEnd']))
    {
  $dateEnd = $this->request->data['dateEnd'];
    }
    
    App::uses('CakeTime', 'Utility');
    $dateSelectSale = CakeTime::daysAsSql($this->Functions->viewDateToDateTime($dateStart)->format('Y-m-d H:i:s'), $this->Functions->viewDateToDateTime($dateEnd)->format('Y-m-d H:i:s'), 'Sale.date');
    
    
    
    $shopData = array();
    $shops = $this->Shop->find('list');
    $productTypes = $this->ProductType->find('list');

    foreach($shops as $shopId => $shop)
    {
    $shopData[$shopId] = array();
          foreach($productTypes as $typeId => $productType)
    {
      $shopData[$shopId][$typeId] = array();
      $this->Sale->contain();
      $shopData[$shopId][$typeId]['Sales'] = $this->Sale->find('all', array('conditions'=>array('('.$dateSelectSale.')',
                    'Sale.product_id in (select P.id from products P where P.product_types_id = '.$typeId.')',
                    'Sale.shop_id' => $shopId
                  ),
                   //'group' =>'Date(Sale.date)',
                    'fields' => array(
                      //'Sale.date as date',
                      'SUM(Sale.price * Sale.sold) as price')
                   ));
     //$shopData[$shopId][$typeId]['results'] = array();
    }
    }
  $this->set('data', $shopData);
  $this->set(compact('shops','productTypes', 'dateStart', 'dateEnd'));
    
}

/**
 * add method
 *
 * @return void
 */
  public function add() {
    if ($this->request->is('post') && !isset($this->request->data['dateSelect'])) {
    $error = 0;
    $date = $this->request->data['date'];
    foreach($this->request->data['Sale'] as $shopId => $shop)
    {
      foreach($shop as $productId => $product)
      {
  if($product['produced'] != '' || $product['lost'] != '' )
  {
    $productModel = $this->Product->findById($productId);
    $this->Sale->create();
    $data = array();
    $data['Sale'] = array(
      'date' => $this->Functions->viewDateToDateTime($date)->format('Y-m-d H:i:s'),
      'product_id' => $productId,
      'price' => $productModel['Product']['price'],
      'unity' => $productModel['Product']['unity'],
      'shop_id' => $shopId,
      'produced' => $product['produced'],
      'comment' => $product['comment'],
      'lost' => $product['lost'],
      );
    if($product['saleId'] != '')
    {
      $data['Sale']['id'] = $product['saleId'];
    }
    if (!$this->Sale->save($data)) {
       $error ++;
    }
  }
      }
    }
    if($error == 0)
    {
  $this->Session->setFlash(__('The sale has been saved'),'flash/ok');
        $this->redirect(array('action' => 'add'));
    }
    else
    {
      $this->Session->setFlash(__('The sale could not be saved. Please, try again.'),'flash/fail');
    }
  }
    if(!isset($this->request->data['date']))
    {
      $date = date('d/m/Y');
    }
    else
    {
      $date = $this->request->data['date'];
    }
    $this->set('date', $date);
    $date = $this->Functions->viewDateToDateTime($date)->format('Y-m-d H:i:s');
    $this->Sale->Product->contain();
    $this->Sale->Product->contain(array('ProductType'=>array(),'Sale'=>array('conditions'=>'Sale.date = \''.$date.'\''), 'Media.Photo'=>array()));

    $products = $this->Sale->Product->find('all', array('order'=>'Product.product_types_id', 'conditions'=>array('Product.production_display')));
    //debug($products);
    $shops = $this->Sale->Shop->find('all');

    $this->set(compact('products', 'shops'));
  }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function edit($id = null) {
    if (!$this->Sale->exists($id)) {
      throw new NotFoundException(__('Invalid sale'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->Sale->save($this->request->data)) {
        $this->Session->setFlash(__('The sale has been saved'),'flash/ok');
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The sale could not be saved. Please, try again.'),'flash/flash');
      }
    } else {
      $options = array('conditions' => array('Sale.' . $this->Sale->primaryKey => $id));
      $this->request->data = $this->Sale->find('first', $options);
    }
    $products = $this->Sale->Product->find('list');
    $shops = $this->Sale->Shop->find('list');
    $this->set(compact('products', 'shops'));
  }

  
  public function graph()
  {
  /*
  	$includes = include APP.'Vendor/zetacomponents/Base/src/base_autoload.php';
	foreach($includes as $class => $file)
	{
		$path = str_replace('Base/', 'Base/src/', $file);
		require APP.'Vendor/zetacomponents/'.$path;
	}
  
	$includes = include APP.'Vendor/zetacomponents/Graph/src/graph_autoload.php';
	foreach($includes as $class => $file)
	{
		$path = str_replace('Graph/', 'Graph/src/', $file);
		require APP.'Vendor/zetacomponents/'.$path;
	}
*/
	$this->layout = 'ajax'; 
	$graph = new ezcGraphBarChart();
/*	require APP.'Vendor/zetacomponents/Graph/src/charts/bar.php';
	require APP.'Vendor/zetacomponents/Graph/src/charts/line.php';
	$graph = new ezcGraphBarChart();*/
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
    $this->Sale->id = $id;
    if (!$this->Sale->exists()) {
      throw new NotFoundException(__('Invalid sale'));
    }
    $this->request->onlyAllow('post', 'delete');
    if ($this->Sale->delete()) {
      $this->Session->setFlash(__('Sale deleted'),'flash/ok');
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Sale was not deleted'),'flash/fail');
    $this->redirect(array('action' => 'index'));
  }
}
