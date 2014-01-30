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
    $this->set('title_for_layout', 'Produits');
    $this->menu['Menu']['Produits']['active'] = true;
    
    $conditions = array();
    $conditions['ProductType'] = '';
    $conditions['Products'] = '';

    if(!$this->Auth->user('isRoot'))
    {
		$conditions['ProductType'] = 'ProductType.customer_display';
		$conditions['Products'] = 'Products.customer_display';
    }
	else
	{
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array(), 'group' => array('time'=>'week', 'productType'=>'productType'))));
		$this->set('resultsEntries',$res['resultsEntries']);
	}
    


    $isCalendarAvailable = $this->requestAction(array('controller'=>'events', 'action'=>'eventsAvailable'));
    $this->set('isCalendarAvailable', $isCalendarAvailable);

    $contain = array('Media.Photo','Products'=> array('conditions'=>$conditions['Products']), 'Products.Media', 'Products.Events.EventType');

    if($isCalendarAvailable)
    {
      $contain[] = 'Products.Events.Gevent.GeventDate';
    }

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
    
    

    $isCalendarAvailable = $this->requestAction(array('controller'=>'events', 'action'=>'eventsAvailable'));
    $this->set('isCalendarAvailable', $isCalendarAvailable);
    
    $conditions = array();
    $conditions['ProductType'] = array('ProductType.' . $this->ProductType->primaryKey => $id);
    $conditions['Products'] = array();

    if(!$this->Auth->user('isRoot'))
    {
      $conditions['ProductType'][] = 'ProductType.customer_display';
      $conditions['Products'][] = 'Products.customer_display';
    }
	else
	{
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array('productType'=>$id), 'group' => array('time'=>'week', 'shop'=>'shop'))));
		$this->set('resultsEntries',$res['resultsEntries']);
	}

    $contain = array('Media.Photo','Products'=> array('conditions'=>$conditions['Products']), 'Products.Media', 'Products.Events.EventType');

    if($isCalendarAvailable)
    {
      $contain[] = 'Products.Events.Gevent.GeventDate';
    }

    $this->ProductType->contain();
    $this->ProductType->contain($contain);

    $productTypes = $this->ProductType->find('first', array('conditions'=>$conditions['ProductType']));


    $this->set('productType', $productTypes);
    $this->set('isCalendarAvailable', $isCalendarAvailable);
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
        $this->Session->setFlash(__('The product type has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The product type could not be saved. Please, try again.'));
      }
    }
    $media = array_merge(array(''=>''), $this->ProductType->Media->find('list'));
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
        $this->Session->setFlash(__('The product type has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The product type could not be saved. Please, try again.'));
      }
    } else {
      $options = array('conditions' => array('ProductType.' . $this->ProductType->primaryKey => $id));
      $this->request->data = $this->ProductType->find('first', $options);
    }
    $media = array_merge(array(''=>''), $this->ProductType->Media->find('list'));
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
      $this->Session->setFlash(__('Product type deleted'));
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Product type was not deleted'));
    $this->redirect(array('action' => 'index'));
  }
}
