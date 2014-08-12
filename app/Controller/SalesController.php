<?php
App::uses('AppController', 'Controller');
/**
 * Sales Controller
 *
 * @property Sale $Sale
 */
class SalesController extends AppController {

  var $helpers = array('Time', 'PhpExcel.PhpExcel');
  var $components = array('Functions');
  var $uses = array('Sale', 'Product', 'Shop', 'ProductType');

  var $administratorActions = array('*');

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
		$this->set('results',$res);
		
		
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array(), 'group' => array('time'=>'week', 'productType'=>'productType'))));
		$this->set('resultsEntries',$res);
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
				)),
				'group' => array('time'=>'day', 'productType'=>'productType'))));
		$this->set('dayStats',$res);
		
		
		
		// select current days historic (ie 21/03) productsTypes...
		$res = $this->requestAction(array('controller'=>'sales', 'action'=>'stats'), array( 'pass'=>array('conditions'=>
					array(
						'DATE_FORMAT(Sale.date,"%m-%d") = DATE_FORMAT(\''.$now->format('Y-m-d H:i:s').'\', "%m-%d")'
						),
						'group' => array('time'=>'day', 'shop'=>'shop', 'product'=>''))));
		$this->set('sales',$res);
	}


    public function stats($conditions = array(), $group = array()) {
    $this->set('title_for_layout', 'Statistiques de ventes/production');
    $groupBy = array();
    if (empty($this->request->params['requested'])) {
      $dateStart = new DateTime();
      $dateStart->modify('-15 day');
      $dateStart = $dateStart->format('d/m/Y');
      if(isset($this->request->data['conditions']['dateStart']) && $this->request->data['conditions']['dateStart'] != '')
      {
        $dateStart = $this->request->data['conditions']['dateStart'];
      }
      else
      {
	  $this->request->data['conditions']['dateStart'] = $dateStart;
      }

      $dateEnd = date('d/m/Y');     
      if(isset($this->request->data['conditions']['dateEnd']) && $this->request->data['conditions']['dateEnd'] != '')
      {
        $dateEnd = $this->request->data['conditions']['dateEnd'];
      }
      else
      {
	  $this->request->data['conditions']['dateStop'] = $dateEnd;
      }


      $dateStart = $this->Functions->viewDateToDateTime($dateStart);
      $dateEnd = $this->Functions->viewDateToDateTime($dateEnd);

      if($dateEnd < $dateStart)
      {
	$this->Session->setFlash(__('Dates invalides'),'flash/fail');
	$this->redirect(array('action' => 'stats'));
      }


      App::uses('CakeTime', 'Utility');
      $dateSelect = CakeTime::daysAsSql($dateStart->format('Y-m-d H:i:s'),$dateEnd->format('Y-m-d H:i:s'), 'Sale.date');

      $conditions[] = $dateSelect;
      $conditions[] = 'Sale.shop_id in ( select C.'.$this->Shop->primaryKey.' from '.$this->Shop->table.' C where company_id = '.$this->getCompanyId().')';

      if(isset($this->request->data['conditions']['shop']) && $this->request->data['conditions']['shop'] != '')
      {
        $conditions['Sale.shop_id'] = $this->request->data['conditions']['shop'];
      }

      if(isset($this->request->data['conditions']['product']) && $this->request->data['conditions']['product'] != '')
      {
        $conditions['Sale.product_id'] = $this->request->data['conditions']['product'];
      }

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
		    $group['shop'] = 1;
		    $group['product'] = 1;
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

      if($group['product'])
      {
 	  $groupBy[] = 'Sale.product_id';
      }
      if($group['shop'])
      {
 	  $groupBy[] = 'Sale.shop_id';
      }
    }
      if(count($groupBy) == 0)
      {
	$groupBy[] = 'Sale.date, Sale.product_id, Sale.shop_id';
      }
	  // SELECT SUM(IF(myColumn IS NULL, 0, myColumn))
    $this->Sale->contain('Product.ProductType'/*, 'Shop'*/);
    $sales = $this->Sale->find('all', array('order'=>array('Sale.date', 'Sale.product_id', 'Sale.shop_id'),
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
	bcscale( Configure::read('Settings.Approximation.bcscale') );
	$regressions = array();
	$initDate = NULL;

      $order = Configure::read('Settings.Approximation.order');
      if(isset($this->request->data['approximation']['order']))
      {
        $order = $this->request->data['approximation']['order'];
      }
      else
      {
	$order = $this->request->data['approximation']['order'] = $order;
      }
	// add data to regression
	$nbSales = count($sales);

	for($i=0; $i< $nbSales; $i++)
	{
		$res = $sales[$i];
		if(!isset($regressions[$res['Product']['id']][$res['Sale']['shop_id']]['produced']))
		{
			$regressions[$res['Product']['id']][$res['Sale']['shop_id']]['produced'] = new PolynomialRegression( $order );
			$regressions[$res['Product']['id']][$res['Sale']['shop_id']]['lost'] = new PolynomialRegression( $order );
			$regressions[$res['Product']['id']][$res['Sale']['shop_id']]['sold'] = new PolynomialRegression( $order );
			$regressions[$res['Product']['id']][$res['Sale']['shop_id']]['totalPrice'] = new PolynomialRegression( $order );
			$regressions[$res['Product']['id']][$res['Sale']['shop_id']]['totalLost'] = new PolynomialRegression( $order );
			$initDate[$res['Product']['id']][$res['Sale']['shop_id']] = new DateTime($res['Sale']['date']);
		}
		$curDate = new DateTime($res['Sale']['date']);
		$dateDiff = $initDate[$res['Product']['id']][$res['Sale']['shop_id']]->diff($curDate);
		$x = $dateDiff->days / $nbDaysByInterval;
		if($i < ($nbSales -1) )
		{
			foreach($regressions[$res['Product']['id']][$res['Sale']['shop_id']] as $name => &$regression)
			{
				$regressions[$res['Product']['id']][$res['Sale']['shop_id']][$name] ->addData( $x, $res[0][$name] ); 
				// $regression->addData( $x, $res[0][$name] ); 
			}
		}
	}
	
	
	// get equation parameters
	$approximations = array();
	foreach($regressions as $productId => $regressionProduct)
	{
	    foreach($regressionProduct as $shopId => $regressionProductShop)
	    {
		  foreach($regressionProductShop as $name => $reg)
		  {
			// debug('product #'.$productId.' shop #'.$shopId.' '.$name, 'debug');
		    $coeffs = $reg->getCoefficients();
		    $approximations[$productId][$shopId][$name] = $coeffs;
		    $this->log('product #'.$productId.' shop #'.$shopId.' '.$name.' '.$this->getFunctionText($coeffs), 'debug');
		  }
	    }
	}
	
	$lastDate = NULL;
	// fill results
	foreach($sales as &$res)
	{
		$curDate = new DateTime($res['Sale']['date']);
		$dateDiff = $initDate[$res['Product']['id']][$res['Sale']['shop_id']]->diff($curDate);
		$x = $dateDiff->days / $nbDaysByInterval;
		
		foreach($regressions[$res['Product']['id']][$res['Sale']['shop_id']] as $name => $regression)
		{
			$coeffs = $approximations[$res['Product']['id']][$res['Sale']['shop_id']][$name];
			if( $coeffs === false )
			{
				$y = $res[0][$name];
			}
			else
			{
				$y = $regressions[$res['Product']['id']][$res['Sale']['shop_id']][$name]->interpolate($approximations[$res['Product']['id']][$res['Sale']['shop_id']][$name],$x);
			}
			if($y < 0)
			{
				$y =0;
			}
			$res[0][$name.'Approximation'] = $y;
		}
		$lastDate = $curDate;
	}	
 // extrapolate to future
	$maxX = Configure::read('Settings.Approximation.nbProjectionsPoint');
	for($i = 0; $i < $maxX; $i++)
	{
		$res = array(
			0 => array(
						'produced' => '',
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
		foreach($regressions as $productId => $regressionsData)
		{
		    foreach($regressions[$productId] as $shopId => $regressionData1)
		    {
		      $dateDiff = $initDate[$productId][$shopId]->diff($lastDate);
		      $x = $dateDiff->days / $nbDaysByInterval;
		      foreach($regressions[$productId][$shopId] as $name => $regression)
		      {
			$y = $regressions[$productId][$shopId][$name]->interpolate($approximations[$productId][$shopId][$name],$x);
			  if($y < 0)
			  {
				  $y =0;
			  }
			  $res[0][$name.'Approximation'] = $y;
		      }
		      $res['Sale']['date']  = $lastDate->format('Y-m-d H:i:s');
		      $res['Sale']['product_id']  = $productId;
		      $res['Product']['id']  = $productId;
		      $res['Sale']['shop_id'] = $shopId;
		      $sales[] = $res;
		    }
		}
	}
 

    $this->Sale->Product->contain('ProductType');
    $products = $this->Sale->Product->find('all', array('conditions'=>array('ProductType.company_id'=>$this->getCompanyId())));
    $products = Set::combine($products, '{n}.Product.id', '{n}');
    $this->Sale->Product->contain('ProductType');
    $productsList = $this->Sale->Product->find('list', array('conditions'=>array('ProductType.company_id'=>$this->getCompanyId())));


    $shops = $this->Sale->Shop->find('list', array('conditions'=>array('Shop.company_id'=>$this->getCompanyId())));

    if (!empty($this->request->params['requested'])) {
            return compact('sales','products', 'shops');
        }
    $this->set('sales', compact('sales', 'products', 'shops', 'productsList'));

$dateStart = $dateStart->format('d/m/Y');
$dateEnd = $dateEnd->format('d/m/Y');
    $this->set(compact('dateStart','dateEnd'));
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
    $shops = $this->Shop->find('list',array('conditions'=>array('Shop.company_id'=>$this->getCompanyId())));
    $productTypes = $this->ProductType->find('list',array('conditions'=>array('ProductType.company_id'=>$this->getCompanyId())));

    foreach($shops as $shopId => $shop)
    {
    $shopData[$shopId] = array();
          foreach($productTypes as $typeId => $productType)
    {
      $shopData[$shopId][$typeId] = array();
      $this->Sale->contain('Shop');
      $shopData[$shopId][$typeId]['Sales'] = $this->Sale->find('all', array('conditions'=>array('('.$dateSelectSale.')',
                    'Sale.product_id in (select P.id from products P where P.product_types_id = '.$typeId.')',
                    'Sale.shop_id' => $shopId,
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

    if(isset($this->request->data['Sale']['upload'])) // add results from excel file
    {
	  //TODO factorize
	$alphabet =   $alphabet = $this->Functions->getAlphabet();
	try{
	    $imported = 0;
	    $notUpdated = 0;
	    App::import('Vendor', 'PhpExcel.PHPExcel');
	    // load uploaded document
	    $excel = PHPExcel_IOFactory::load($this->request->data['Sale']['upload']['tmp_name']);

	    $nbSheets = $excel->getSheetCount();
	    
	    $dataSheetId = 1;
	    $excel->setActiveSheetIndex($dataSheetId);
	    $maxColumn = $alphabet[7];
	    // foreach data row of the sheet
	      for($j=2; $j< Configure::read('Settings.Excel.maxNbRow'); $j++)
	      {
		// get data
		$range = 'A'.$j.':'.$maxColumn.$j;
		$row = $excel->getActiveSheet()->rangeToArray($range);
		if($row[0][0] == NULL) // if date is not set, empty row
		{
		  break;
		}


		$shopName = $row[0][1];
		preg_match('/#(\d+) /', $shopName, $matches); // extract shop id
		if(count($matches) == 2)
		{
		    $shopId = $matches[1];
		}
		else
		{
		  $this->log('Could not detect shopId', 'debug');
		  $error ++;
		  continue;
		}

		$productName = $row[0][2];
		preg_match('/#(\d+) /', $productName, $matches); // extract shop id
		if(count($matches) == 2)
		{
		    $productId = $matches[1];
		}
		else
		{
		  $error ++;
		  $this->log('Could not detect productId', 'debug');
		  continue;
		}

		$this->Product->contain('ProductType');
		$productModel = $this->Product->findById($productId);
		$this->Sale->create();
		$data = array();
		    $data['Sale'] = array(
		      'date' => $this->Functions->viewDateToDateTime($row[0][0], false)->format('Y-m-d H:i:s'),
		      'product_id' => $productId,
		      'price' => $productModel['Product']['price'],
		      'unity' => $productModel['Product']['unity'] == 1,
		      'shop_id' => $shopId,
		      'produced' => $row[0][4],
		      'comment' => $row[0][6],
		      'lost' => $row[0][5],
		      );

		    $tmpRes = $this->Sale->find('count', array('conditions'=>array('date'=>$data['Sale']['date'], 'shop_id' => $data['Sale']['shop_id'], 'product_id'=> $data['Sale']['product_id'],
		    'Shop.company_id'=>$this->getCompanyId()
		    )));
		  // if yes, goto next row
		  if($tmpRes == 1)
		  {
		      $this->log('Could not save '.$data['Sale']['date'].' for shop '.$data['Sale']['shop_id'].' product '.$data['Sale']['product_id'].' already set ', 'debug');
		      $notUpdated ++;
		      continue;
		  }

		  if (!$this->Sale->save($data)) {
			$this->log('Could not save '.$data['Sale']['date'].' for shop '.$data['Sale']['shop_id'].' product '.$data['Sale']['product_id'], 'debug');
			$error ++;
		      }
		    else
		   {
		      $imported ++;
		    }
	      }
	      if($error == 0)
	      {
		  $this->Session->setFlash(__($imported.' enregistrements sauvegardés, '.$notUpdated.' enregistrements ignorés'),'flash/ok');
		  $this->redirect(array('action' => 'add'));
	      }
	      else
	      {
		$this->Session->setFlash(__($error.' erreurs, '.$imported.' enregistrements sauvegardés, '.$notUpdated.' enregistrements ignorés'),'flash/fail');
	      }
	}
	catch(Exception $e)
	{
	    $this->Session->setFlash(__('Veuillez vérifier le format de votre fichier Excel'),'flash/fail');
	    debug($e);
	    $this->log('Excel import issue '.$e, 'debug');
	}
    } // endif upload
    else
    {






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
      'unity' => $productModel['Product']['unity'] == 1,
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

    $products = $this->Sale->Product->find('all', array('order'=>'Product.product_types_id', 'conditions'=>array('Product.production_display', 'ProductType.company_id'=>$this->getCompanyId())));
    $shops = $this->Sale->Shop->find('all',array('conditions'=>array('Shop.company_id'=>$this->getCompanyId())));

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
//     if (!$this->Sale->exists($id)) {
//       throw new NotFoundException(__('Invalid sale'));
//     }
//     if ($this->request->is('post') || $this->request->is('put')) {
//       if ($this->Sale->save($this->request->data)) {
//         $this->Session->setFlash(__('The sale has been saved'),'flash/ok');
//         $this->redirect(array('action' => 'index'));
//       } else {
//         $this->Session->setFlash(__('The sale could not be saved. Please, try again.'),'flash/flash');
//       }
//     } else {
//       $options = array('conditions' => array('Sale.' . $this->Sale->primaryKey => $id));
//       $this->request->data = $this->Sale->find('first', $options);
//     }
//     $products = $this->Sale->Product->find('list');
//     $shops = $this->Sale->Shop->find('list');
//     $this->set(compact('products', 'shops'));
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
//     $this->Sale->id = $id;
//     if (!$this->Sale->exists()) {
//       throw new NotFoundException(__('Invalid sale'));
//     }
//     $this->request->onlyAllow('post', 'delete');
//     if ($this->Sale->delete()) {
//       $this->Session->setFlash(__('Sale deleted'),'flash/ok');
//       $this->redirect(array('action' => 'index'));
//     }
//     $this->Session->setFlash(__('Sale was not deleted'),'flash/fail');
//     $this->redirect(array('action' => 'index'));
  }


public function beforeFilter() {
	parent::beforeFilter();
        // $this->Security->requirePost('stats');
    }

}
