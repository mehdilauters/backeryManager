<?php
App::uses('AppController', 'Controller');
/**
 * Products Controller
 *
 * @property Product $Product
 */
class ProductsController extends AppController {
  var $publicActions = array('index','view');
  var $uses = array('Product');
  
/**
 * index method
 *
 * @return void
 */
  public function index() {
  

    $this->set('title_for_layout', 'Produits');

    $contain = array('ProductType.Media.Photo','Media');
    $this->Product->contain($contain);

    $conditions = array();

    if(!$this->Auth->user('isRoot'))
    {
      $conditions[] = 'Product.customer_display';
    }
	$products = $this->Product->find('all', array('conditions' => $conditions, 'order' => 'Product.product_types_id'));
	
	if ($this->RequestHandler->isRss() ) {
        return $this->set(compact('products'));
    }
	
	
    $this->set('products', $products);
 
  }



/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function view($id = null) {
    if (!$this->Product->exists($id)) {
      throw new NotFoundException(__('Invalid product'));
    }

    

    $conditions = array();
    $conditions = array('Product.' . $this->Product->primaryKey => $id);

    if(!$this->Auth->user('isRoot'))
    {
      $conditions[] = 'Product.customer_display';
    }

    
    $options = array('conditions' => $conditions);
    $contain = array('ProductType.Media.Photo','Media');

    $this->Product->contain($contain);
    $products = $this->Product->find('first', $options);
    $this->set('title_for_layout', $products['Product']['name']);
  
    $this->set('product', $products);





	if($this->Auth->user('isRoot'))
	{
		$res = $this->requestAction(array('controller'=>'sales', 'action'=>'stats'), array( 'pass'=>array('conditions'=>array('Sale.product_id'=>$products['Product']['id']), 'group' => array('time'=>'day', 'shop'=>'shop', 'product'=>'product'))));
		$this->set(compact('products','shops'));
		$this->set('sales',$res);
	}




  }

/**
 * add method
 *
 * @return void
 */
  public function add() {
    if ($this->request->is('post')) {
      $this->Product->create();
      debug($this->request->data);
      if($this->request->data['Product']['media_id'] == '')
      {
		unset($this->request->data['Product']['media_id']);
      }
      if ($this->Product->save($this->request->data)) {
        $this->Session->setFlash(__('The product has been saved'),'flash/ok');
         return $this->redirect(array('action' => 'add'));
      } else {
        $this->Session->setFlash(__('The product could not be saved. Please, try again.'),'flash/flash');
      }
    }
    $productTypes = $this->Product->ProductType->find('list');
    $media = array(''=>'') + $this->Product->Media->find('list');
    $this->set(compact('productTypes', 'media'));
  }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function edit($id = null) {
    if (!$this->Product->exists($id)) {
      throw new NotFoundException(__('Invalid product'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if($this->request->data['Product']['media_id'] == '')
      {
	unset($this->request->data['Product']['media_id']);
      }
      if ($this->Product->save($this->request->data)) {
        $this->Session->setFlash(__('The product has been saved'),'flash/ok');
        return $this->redirect(array('controller'=>'products', 'action' => 'index'));
      } else {
        $this->Session->setFlash(__('The product could not be saved. Please, try again.'),'flash/fail');
      }
    } else {
      $options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
      $this->request->data = $this->Product->find('first', $options);
    }
    $productTypes = $this->Product->ProductType->find('list');
    $media = array(''=>'') + $this->Product->Media->find('list');
    $this->set(compact('productTypes', 'media'));
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
    $this->Product->id = $id;
    if (!$this->Product->exists()) {
      throw new NotFoundException(__('Invalid product'));
    }
    $this->request->onlyAllow('post', 'delete');
    if ($this->Product->delete()) {
      $this->Session->setFlash(__('Product deleted'),'flash/ok');
      return $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Product was not deleted'),'flash/fail');
    return $this->redirect(array('action' => 'index'));
  }

  public function beforeRender()
  {
    $this->menu['Menu']['Produits']['active'] = true;
    parent::beforeRender();
  }
}
