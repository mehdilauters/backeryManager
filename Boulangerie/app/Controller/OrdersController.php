<?php
App::uses('AppController', 'Controller');
/**
 * Orders Controller
 *
 * @property Order $Order
 */
class OrdersController extends AppController {
	var $uses = array('Order', 'Company');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('orders', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Order->exists($id)) {
			throw new NotFoundException(__('Invalid order'));
		}
		
		
		$this->Order->contain('OrderedItem.Product', 'Shop', 'User');
		$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
		
		$order = $this->Order->find('first', $options);
		
		$company = $this->Company->find('first');
		if(count($company) == 0)
		{
			debug('company does not exists');
		}
		$total = array(
		'HT' => 0,
		'TTC' => 0,
		'tva_percent' => 0,
		'tva_total' => 0
		);
		foreach($order['OrderedItem'] as &$item)
		{
			$item['total_HT'] =  $item['without_taxes'] * $item['quantity'];
			$total['HT'] += $item['total_HT'];
			$total['TTC'] += $item['quantity'] * $item['price'];
			$total['tva_total'] += $total['TTC'] - $total['HT'];
		}
		$this->set('title_for_layout', 'Commande #'.$order['Order']['id']);
		$this->set('company', $company);
		$this->set('order', $order);
		$this->set('total', $total);
		//$this->request->is('pdf');
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Order->create();
			$this->request->data['Order']['status'] = 'reserved';
			
			$delivery = $this->Functions->viewDateToDateTime($this->request->data['Order']['delivery_date']);
			if($delivery != false )
			{
				$this->request->data['Order']['delivery_date'] = $delivery->format('Y-m-d H:i:s');	
			}
			
			
			if ($this->Order->save($this->request->data)) {
				$this->Session->setFlash(__('The order has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The order could not be saved. Please, try again.'));
			}
		}
		$shops = $this->Order->Shop->find('list');
		$users = $this->Order->User->find('list');
		$this->set(compact('shops', 'users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Order->exists($id)) {
			throw new NotFoundException(__('Invalid order'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Order']['delivery_date'] = $this->Functions->viewDateToDateTime($this->request->data['Order']['delivery_date'])->format('Y-m-d H:i:s');
			if ($this->Order->save($this->request->data)) {
				$this->Session->setFlash(__('The order has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The order could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
			$this->request->data = $this->Order->find('first', $options);
		}
		$shops = $this->Order->Shop->find('list');
		$users = $this->Order->User->find('list');
		$this->set(compact('shops', 'users'));
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
		$this->Order->id = $id;
		if (!$this->Order->exists()) {
			throw new NotFoundException(__('Invalid order'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Order->delete()) {
			$this->Session->setFlash(__('Order deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Order was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
