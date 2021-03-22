<?php
App::uses('AppController', 'Controller');
/**
 * ProductTypes Controller
 *
 * @property ProductType $ProductType
 */
class ProductTypesController extends AppController {
  var $publicActions = array('index','view');
  var $administratorActions = array('*');
/**
 * index method
 *
 * @return void
 */
  public function index() {
    $this->set('title_for_layout', 'Catégories de Produits');
    
    $conditions = array();
    $conditions['ProductType'] = array('company_id' => $this->getCompanyId());
    $conditions['Product'] = array();

    if(!$this->Auth->user('isRoot'))
    {
		$conditions['ProductType'][] = 'ProductType.customer_display';
		$conditions['Product'][] = 'Product.customer_display';
    }
	else
	{
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array(), 'group' => array('time'=>'week', 'productType'=>true, 'shop'=>false))));
		$this->set('resultsEntries',$res);
	}
    


    $isCalendarAvailable = false;//$this->requestAction(array('controller'=>'events', 'action'=>'eventsAvailable'));
    $this->set('isCalendarAvailable', $isCalendarAvailable);

    $contain = array('Media.Photo',
		  'Product.ProductType',
		    'Product'=> array('conditions'=>$conditions['Product']),
		  'Product.Media'
	);


    $this->ProductType->contain();
    $this->ProductType->contain($contain);

    $productTypes = $this->ProductType->find('all', array('conditions'=>$conditions['ProductType']));
    $this->set('productTypes', $productTypes);
  }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function view($id = null) {
    if (!$this->ProductType->exists($id)) {
      throw new NotFoundException(__('Invalid product type'));
    }
    
 
    
    $conditions = array();
    $conditions['ProductType'] = array('ProductType.' . $this->ProductType->primaryKey => $id);
    $conditions['Product'] = array();

    if(!$this->Auth->user('isRoot'))
    {
      $conditions['ProductType'][] = 'ProductType.customer_display';
      $conditions['Product'][] = 'Product.customer_display';
    }
	else
	{
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array('productType'=>$id), 'group' => array('time'=>'week', 'shop'=>true, 'productType'=>false))));
		$this->set('resultsEntries',$res);
	}

    $contain = array('Media.Photo','Product'=> array('conditions'=>$conditions['Product']), 'Product.Media.Photo');


    $this->ProductType->contain();
    $this->ProductType->contain($contain);

    $productTypes = $this->ProductType->find('first', array('conditions'=>$conditions['ProductType']));
    if ($productTypes['ProductType']['company_id'] != $this->getCompanyId()) {
      throw new NotFoundException(__('Invalid product type for this company'));
    }
	$this->set('title_for_layout', $productTypes['ProductType']['name'] );

    $this->set('productType', $productTypes);
  }

/**
 * add method
 *
 * @return void
 */
  public function add() {
    if ($this->request->is('post')) {
      $this->ProductType->create();
	  $this->request->data['ProductType']['company_id'] = $this->getCompanyId();
      $media = $this->ProductType->Media->findById($this->request->data['ProductType']['media_id']);
      if (!empty($media) && $media['User']['company_id'] != $this->getCompanyId()) {
	throw new NotFoundException(__('Invalid media for this company'));
      }
      if ($this->ProductType->save($this->request->data)) {
        $this->Session->setFlash(__('The product type has been saved'),'flash/ok');
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The product type could not be saved. Please, try again.'),'flash/fail');
      }
    }
    $this->ProductType->Media->contain('User');
    $media = array(''=>'') + $this->ProductType->Media->find('list', array('conditions'=>array('User.company_id' => $this->getCompanyId())));
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
    if (!$this->ProductType->exists($id)) {
      throw new NotFoundException(__('Invalid product type'));
    }
    $res = $this->ProductType->findById($id);
	  if($res['ProductType']['company_id'] != $this->getCompanyId())
	  {
	      throw new NotFoundException(__('Invalid productType this company'));
	  }

      $media = $this->ProductType->Media->findById($this->request->data['Product']['media_id']);
      if (!empty($media) && $media['User']['company_id'] != $this->getCompanyId()) {
	throw new NotFoundException(__('Invalid media for this company'));
      }

    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->ProductType->save($this->request->data)) {
        $this->Session->setFlash(__('The product type has been saved'),'flash/ok');
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The product type could not be saved. Please, try again.'),'flash/fail');
      }
    } else {
      $options = array('conditions' => array('ProductType.' . $this->ProductType->primaryKey => $id));
      $this->request->data = $this->ProductType->find('first', $options);
    }
    $media = array(''=>'') + $this->ProductType->Media->find('list', array('conditions'=>array('User.company_id' => $this->getCompanyId())));
    $this->set(compact('media'));
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
    $this->ProductType->id = $id;
    if (!$this->ProductType->exists()) {
      throw new NotFoundException(__('Invalid product type'));
    }

      $res = $this->ProductType->findById($id);
      if($res['ProductType']['company_id'] != $this->getCompanyId())
      {
	  throw new NotFoundException(__('Invalid productType this company'));
      }

    $this->request->onlyAllow('post', 'delete');
    if ($this->ProductType->delete()) {
      $this->Session->setFlash(__('Product type deleted'),'flash/ok');
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Product type was not deleted'),'flash/fail');
    $this->redirect(array('action' => 'index'));
  }


      public function beforeRender()
	{
	  $this->menu['Menu']['Categories']['active'] = true;
	  parent::beforeRender();
	}

}
