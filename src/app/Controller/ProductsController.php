<?php
App::uses('AppController', 'Controller');
/**
 * Products Controller
 *
 * @property Product $Product
 */
class ProductsController extends AppController {
  var $publicActions = array('index','view');
  var $administratorActions = array('*');
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

    $conditions = array('ProductType.company_id' => $this->getCompanyId());
    $tokens = $this->getUserTokens();
    if(!$tokens['isAdmin'])
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
    $tokens = $this->getUserTokens();
    if(!$tokens['isAdmin'])
    {
      $conditions[] = 'Product.customer_display';
    }

    
    $options = array('conditions' => $conditions);
    $contain = array('ProductType.Media.Photo','Media');

    $this->Product->contain($contain);
    $products = $this->Product->find('first', $options);
    
  if ($products['ProductType']['company_id'] != $this->getCompanyId()) {
      throw new NotFoundException(__('Invalid product for this company'));
    }

    $this->set('title_for_layout', $products['Product']['name']);
  
    $this->set('product', $products);




	$tokens = $this->getUserTokens();
	if($tokens['isAdmin'])
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
    $productTypeCount = $this->Product->ProductType->find('count', array('conditions' => array('ProductType.company_id' => $this->getCompanyId())));
    if($productTypeCount == 0)
    {
         $this->Session->setFlash('Veuillez d\'abord rentrer une catégorie de produit','flash/fail');
         return $this->redirect(array('controller'=>'productTypes', 'action' => 'add'));
    }
    if ($this->request->is('post')) {
      $this->Product->create();
      $productType = $this->Product->ProductType->findById($this->request->data['Product']['product_types_id']);
      if ($productType ['ProductType']['company_id'] != $this->getCompanyId()) {
	throw new NotFoundException(__('Invalid productType for this company'));
      }

      $media = $this->Product->Media->findById($this->request->data['Product']['media_id']);
      if (!empty($media) && $media['User']['company_id'] != $this->getCompanyId()) {
	throw new NotFoundException(__('Invalid user for this company'));
      }

      if($this->request->data['Product']['media_id'] == '')
      {
		unset($this->request->data['Product']['media_id']);
      }
      if ($this->Product->save($this->request->data)) {
        $this->Session->setFlash(__('The product has been saved'),'flash/ok');
         return $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The product could not be saved. Please, try again.'),'flash/flash');
      }
    }
    $productTypes = $this->Product->ProductType->find('list', array('conditions'=>array('ProductType.company_id' => $this->getCompanyId())));
    $this->Product->Media->contain('User');
    $media = array(''=>'') + $this->Product->Media->find('list', array('conditions'=>array('User.company_id' => $this->getCompanyId())));
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
    $product = $this->Product->findById($id);
    if ($product['ProductType']['company_id'] != $this->getCompanyId()) {
      throw new NotFoundException(__('Invalid product for this company'));
    }

    if ($this->request->is('post') || $this->request->is('put')) {
      $productType = $this->Product->ProductType->findById($this->request->data['Product']['product_types_id']);
      if ($productType ['ProductType']['company_id'] != $this->getCompanyId()) {
	throw new NotFoundException(__('Invalid productType for this company'));
      }

      $media = $this->Product->Media->findById($this->request->data['Product']['media_id']);
      if (!empty($media) && $media['User']['company_id'] != $this->getCompanyId()) {
	throw new NotFoundException(__('Invalid media for this company'));
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
    $productTypes = $this->Product->ProductType->find('list', array('conditions'=>array('ProductType.company_id' => $this->getCompanyId())));
    $this->Product->Media->contain('User');
    $media = array(''=>'') + $this->Product->Media->find('list', array('conditions'=>array('User.company_id' => $this->getCompanyId())));
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
    $product = $this->Product->findById($id);
    if ($product['ProductType']['company_id'] != $this->getCompanyId()) {
      throw new NotFoundException(__('Invalid product for this company'));
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
