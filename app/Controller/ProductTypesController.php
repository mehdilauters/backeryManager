<?php
App::uses('AppController', 'Controller');
/**
 * ProductTypes Controller
 *
 * @property ProductType $ProductType
 */
class ProductTypesController extends AppController {
  var $publicActions = array('index','view');
/**
 * index method
 *
 * @return void
 */
  public function index() {
    $this->set('title_for_layout', 'Catégories de Produits');
    
    $conditions = array();
    $conditions['ProductType'] = '';
    $conditions['Product'] = '';

    if(!$this->Auth->user('isRoot'))
    {
		$conditions['ProductType'] = 'ProductType.customer_display';
		$conditions['Product'] = 'Product.customer_display';
    }
	else
	{
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array(), 'group' => array('time'=>'week', 'productType'=>'productType'))));
		$this->set('resultsEntries',$res);
	}
    


    $isCalendarAvailable = false;//$this->requestAction(array('controller'=>'events', 'action'=>'eventsAvailable'));
    $this->set('isCalendarAvailable', $isCalendarAvailable);

    $contain = array('Media.Photo'
		,'Product'=> array('conditions'=>$conditions['Product']), 'Product.Media',
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
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array('productType'=>$id), 'group' => array('time'=>'week', 'shop'=>'shop'))));
		$this->set('resultsEntries',$res);
	}

    $contain = array('Media.Photo','Product'=> array('conditions'=>$conditions['Product']), 'Product.Media');


    $this->ProductType->contain();
    $this->ProductType->contain($contain);

    $productTypes = $this->ProductType->find('first', array('conditions'=>$conditions['ProductType']));

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
      if ($this->ProductType->save($this->request->data)) {
        $this->Session->setFlash(__('The product type has been saved'),'flash/ok');
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The product type could not be saved. Please, try again.'),'flash/fail');
      }
    }
    $media = array(''=>'')  + $this->ProductType->Media->find('list');
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
    $media = array(''=>'') + $this->ProductType->Media->find('list');
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
