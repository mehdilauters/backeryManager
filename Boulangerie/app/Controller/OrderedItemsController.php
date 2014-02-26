<?php
App::uses('AppController', 'Controller');
/**
 * OrderedItems Controller
 *
 * @property OrderedItem $OrderedItem
 */
class OrderedItemsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->OrderedItem->recursive = 0;
		$this->set('orderedItems', $this->paginate());
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
		$options = array('conditions' => array('OrderedItem.' . $this->OrderedItem->primaryKey => $id));
		$this->set('orderedItem', $this->OrderedItem->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($orderId) {
		if ($this->request->is('post')) {
			$this->OrderedItem->create();
			$this->OrderedItem->Product->contain('ProductType');
			$product = $this->OrderedItem->Product->findById($this->request->data['OrderedItem']['product_id']);
			$this->request->data['OrderedItem']['order_id'] = $orderId;
			$this->request->data['OrderedItem']['tva'] = $product['ProductType']['tva'];
			$this->request->data['OrderedItem']['price'] = $product['Product']['price'];
			$this->request->data['OrderedItem']['unity'] = $product['Product']['unity'];
			if ($this->OrderedItem->save($this->request->data)) {
				$this->Session->setFlash(__('The ordered item has been saved'));
				$this->redirect(array('controller'=>'orders','action' => 'view', $orderId));
			} else {
				$this->Session->setFlash(__('The ordered item could not be saved. Please, try again.'));
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
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->OrderedItem->save($this->request->data)) {
				$this->Session->setFlash(__('The ordered item has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ordered item could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OrderedItem.' . $this->OrderedItem->primaryKey => $id));
			$this->request->data = $this->OrderedItem->find('first', $options);
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
		$this->request->onlyAllow('post', 'delete');
		if ($this->OrderedItem->delete()) {
			$this->Session->setFlash(__('Ordered item deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Ordered item was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
