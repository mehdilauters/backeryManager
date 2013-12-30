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
  var $uses = array('Sale', 'Product', 'Breakage', 'Shop', 'ProductType');
/**
 * index method
 *
 * @return void
 */
  public function index() {
    $this->Sale->contain('Product.Media.Photo', 'Shop.Media.Photo');
    $this->set('sales', $this->paginate());
  }

  
    public function stats() {
    $this->Sale->recursive = 0;
    $this->set('sales', $this->Sale->find('all'));
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
    debug($sale);
    $this->set('sale', $sale);
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
    
    $dateSelectBreakage = CakeTime::daysAsSql($this->Functions->viewDateToDateTime($dateStart)->format('Y-m-d H:i:s'), $this->Functions->viewDateToDateTime($dateEnd)->format('Y-m-d H:i:s'), 'Breakage.date');
    
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
	   $this->Breakage->contain();
	    $shopData[$shopId][$typeId]['Breakages'] =  $this->Breakage->find('all',
									array('conditions'=>array('('.$dateSelectBreakage.')',
									  'product_types_id = '.$typeId,
									  'Breakage.shop_id' => $shopId
									),
									  'fields' => array(
										//'Breakage.date as date',
										'SUM(Breakage.breakage) as breakage')
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
	if($product['produced'] != '' || $product['sold'] != '' )
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
			'sold' => $product['sold'],
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
    foreach($this->request->data['breakage'] as $shopId => $shop)
    {
      foreach($shop as $productTypeId => $value)
      {
	  if($value['breakage'] != '' )
	  {
	    $data = array();
	    $data['Breakage'] = array(
				      'date' => $this->Functions->viewDateToDateTime($date)->format('Y-m-d H:i:s'),
				      'shop_id' => $shopId,
				      'product_types_id' => $productTypeId,
				      'breakage' => $value['breakage']
				      );
	    //debug($value);
	    if($value['breakageId'] != '')
	    {
	      $data['Breakage']['id'] = $value['breakageId'];
	    }
	    if (!$this->Breakage->save($data)) {
	      $error ++;
	    }
	  }
      }
    }
    if($error == 0)
    {
	$this->Session->setFlash(__('The sale has been saved'));
        $this->redirect(array('action' => 'index'));
    }
    else
    {
      $this->Session->setFlash(__('The sale could not be saved. Please, try again.'));
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
    $this->Sale->Product->contain(array('ProductType'=>array(),'Sale'=>array('conditions'=>'Sale.date = \''.$date.'\'')));
    $products = $this->Sale->Product->find('all', array('order'=>'Product.product_types_id'));
    //debug($products);
    $shops = $this->Sale->Shop->find('all');

    $this->Breakage->contain();
    $breakages = $this->Breakage->find('all', array('conditions'=>'Breakage.date = \''.$date.'\''));
    $this->set(compact('products', 'shops', 'breakages'));
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
        $this->Session->setFlash(__('The sale has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The sale could not be saved. Please, try again.'));
      }
    } else {
      $options = array('conditions' => array('Sale.' . $this->Sale->primaryKey => $id));
      $this->request->data = $this->Sale->find('first', $options);
    }
    $products = $this->Sale->Product->find('list');
    $shops = $this->Sale->Shop->find('list');
    $this->set(compact('products', 'shops'));
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
      $this->Session->setFlash(__('Sale deleted'));
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Sale was not deleted'));
    $this->redirect(array('action' => 'index'));
  }
}
