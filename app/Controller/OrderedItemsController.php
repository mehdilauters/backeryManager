<?php
App::uses('AppController', 'Controller');
/**
 * OrderedItems Controller
 *
 * @property OrderedItem $OrderedItem
 */
class OrderedItemsController extends AppController {

//   var $memberActions = array('view');
  var $administratorActions = array('*');


/**
 * index method
 *
 * @return void
 */
	public function index() {
		  $this->OrderedItem->contain('Order.Shop', 'Product.ProductType');
		// contain with belongs to not working, using subquery
		$this->set('orderedItems', $this->paginate(
		      array('Order.shop_id in ( select C.'.$this->OrderedItem->Order->Shop->primaryKey.' from '.$this->OrderedItem->Order->Shop->table.' C where company_id = '.$this->getCompanyId().')')
		  ));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OrderedItem->exists($id)) {
			throw new NotFoundException(__('Invalid ordered item'));
		}

		$this->OrderedItem->contain('Order.Shop');
		$options = array('conditions' => array('OrderedItem.' . $this->OrderedItem->primaryKey => $id));
		$order = $this->OrderedItem->find('first', $options);
		if ($order['Order']['Shop']['company_id'] != $this->getCompanyId()) {
		    throw new NotFoundException(__('Invalid item for this company'));
			}



		$this->set('orderedItem', $order);
	}

/**
 * add method
 *
 * @return void
 */
	public function add($orderId) {
		$order = $this->OrderedItem->Order->findById($orderId);
		if ($order['Shop']['company_id'] != $this->getCompanyId()) {
		    throw new NotFoundException(__('Invalid item for this company'));
			}
		if ($this->request->is('post')) {
			$this->OrderedItem->create();
			$this->OrderedItem->Product->contain('ProductType');
			$product = $this->OrderedItem->Product->findById($this->request->data['OrderedItem']['product_id']);
			$this->request->data['OrderedItem']['order_id'] = $orderId;
			$this->request->data['OrderedItem']['tva'] = $product['ProductType']['tva'];
			$this->request->data['OrderedItem']['price'] = $product['Product']['price'];
			$this->request->data['OrderedItem']['unity'] = $product['Product']['unity'];
			$date = $this->Functions->viewDateToDateTime($this->request->data['OrderedItem']['created']);
			if($date != false )
			{
				$this->request->data['OrderedItem']['created'] = $date->format('Y-m-d H:i:s');	
			}


			if ($this->OrderedItem->save($this->request->data)) {
                            if ($this->request->is('ajax'))
                            {
                              $results = array('id'=>$this->OrderedItem->getInsertID(),'status'=>true);
                              $this->set(compact('results'));
                              $this->set('_serialize', array('results'));
                            }
                            else
                            {
                              $this->Session->setFlash(__('The ordered item has been saved'),'flash/ok');
                              $this->redirect(array('controller'=>'orders','action' => 'view', $orderId));
                            }
			} else {
				if ($this->request->is('ajax'))
				{
                                  $results = array('status'=>false);
                                  $this->set(compact('results'));
                                  $this->set('_serialize', array('results'));
                                }
                                else
                                {
                                  $this->Session->setFlash(__('The ordered item could not be saved. Please, try again.'),'flash/fail');
                                }
			}
		}
		$this->set('orderId', $orderId);
		$this->set('title_for_layout', 'Commande #'.$orderId);
		$order = $this->OrderedItem->Order->findById($orderId);
		$products = $this->OrderedItem->Product->find('list');
		$this->set(compact('order', 'products'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OrderedItem->exists($id)) {
			throw new NotFoundException(__('Invalid ordered item'));
		}
		$options = array('conditions' => array('OrderedItem.' . $this->OrderedItem->primaryKey => $id));
		$this->OrderedItem->contain('Order.Shop', 'Order.User');
		$orderedItem = $this->OrderedItem->find('first', $options);
		if ($orderedItem['Order']['Shop']['company_id'] != $this->getCompanyId()) {
		    throw new NotFoundException(__('Invalid item for this company'));
			}
		if ($this->request->is('post') || $this->request->is('put')) {
			$date = $this->Functions->viewDateToDateTime($this->request->data['OrderedItem']['created']);
			if($date != false )
			{
				$this->request->data['OrderedItem']['created'] = $date->format('Y-m-d H:i:s');	
			}
			if ($this->OrderedItem->save($this->request->data)) {
				if ($this->request->is('ajax'))
                                {
				
                                  $results = array('status'=>true);
                                  $this->set(compact('results'));
                                  $this->set('_serialize', array('results'));
                                }
                                else
                                {
                                  $this->Session->setFlash(__('The ordered item has been saved'),'flash/ok');
                                  $this->redirect(array('action' => 'index'));
                                }
			} else {
                                if ($this->request->is('ajax'))
                                {
                                  $results = array('status'=>false);
                                  $this->set(compact('results'));
                                  $this->set('_serialize', array('results'));
                                }
                                else
                                {
                                  $this->Session->setFlash(__('The ordered item could not be saved. Please, try again.'));
                                }
			}
		} else {
			
			$this->request->data = $orderedItem;
		}
		$orders = $this->OrderedItem->Order->find('list');
		$products = $this->OrderedItem->Product->find('list');
		$this->set(compact('orders', 'products'));
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
		$this->OrderedItem->id = $id;
		if (!$this->OrderedItem->exists()) {
			throw new NotFoundException(__('Invalid ordered item'));
		}
		$options = array('conditions' => array('OrderedItem.' . $this->OrderedItem->primaryKey => $id));
		$this->OrderedItem->contain('Order.Shop');
		$order = $this->OrderedItem->find('first', $options);
		if ($order['Order']['Shop']['company_id'] != $this->getCompanyId()) {
		    throw new NotFoundException(__('Invalid item for this company'));
			}
		$this->request->onlyAllow('post', 'delete');
		if ($this->OrderedItem->delete()) {
			$this->Session->setFlash(__('Ordered item deleted'),'flash/ok');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Ordered item was not deleted'),'flash/ok');
		$this->redirect(array('action' => 'index'));
	}
}
