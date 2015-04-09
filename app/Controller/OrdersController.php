<?php
App::uses('AppController', 'Controller');
/**
 * Orders Controller
 *
 * @property Order $Order
 */
class OrdersController extends AppController {
	var $uses = array('Order', 'Company');

  var $administratorActions = array('*');
  var $memberActions = array('index', 'view');

/**
 * index method
 *
 * @return void
 */
	public function index($status = 'reserved' ) {
	    $conditions = array('Shop.company_id' => $this->getCompanyId());
	  // limit to current user if not admin
	  $tokens = $this->getUserTokens();
	  if(!$tokens['isAdmin'])
	  {
	    $conditions['user_id'] = $this->Auth->user('id');
	  }

	    if(isset($this->request->data['Order']['statusSelect']))
	    {
	     $this->redirect('/orders/index/'.$this->request->data['Order']['statusSelect']); 
	    }


	    if($status != 'all')
	    {
	      $conditions['Order.status'] = $status;
	    }
		$this->request->data['Order']['statusSelect'] = $status;
		$this->Order->contain('Shop', 'User');
		$this->set('orders', $this->paginate($conditions));
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
		
                $tokens = $this->getUserTokens();
                if(!$tokens['isAdmin'])
                {
                  $options['conditions']['Order.user_id'] = $this->Auth->user('id');
                }
		
		$order = $this->Order->find('first', $options);
		if($order['Shop']['company_id'] != $this->getCompanyId() )
		{
		  throw new NotFoundException(__('Invalid order'));
		}
		$company = $this->Company->findById($this->getCompanyId());
		
		$total = array('tva' => array(), 'total');
		$tmp = array(
		'HT' => 0,
		'TTC' => 0,
		'tva_total' => 0
		);
		$total['total'] = $tmp;
		foreach($order['OrderedItem'] as &$item)
		{
		      if(!isset($total['tva'][$item['tva']]))
		      {
			$total['tva'][$item['tva']] = $tmp;
		      }
		      $item['discount_HT'] = $item['without_taxes'] - (($order['Order']['discount'] * $item['without_taxes']) / 100 );
		      $item['discount_TTC'] = $item['price'] - (($order['Order']['discount'] * $item['price']) / 100 );
		      $item['total_HT'] =  $item['quantity'] * $item['discount_HT'];
		      $item['total_TTC'] =  $item['quantity'] * $item['discount_TTC'];


			$total['tva'][$item['tva']]['HT'] += $item['total_HT'];
			$total['tva'][$item['tva']]['TTC'] += $item['total_TTC'];
			$total['tva'][$item['tva']]['tva_total'] += ($item['total_TTC'] - $item['total_HT']);
		}

		foreach($total['tva'] as $data)
		{
		   $total['total']['HT'] += $data['HT'];
		   $total['total']['TTC'] += $data['TTC'];
		   $total['total']['tva_total'] += $data['tva_total'];
		}

		if (!empty($this->request->params['requested'])) {
			return compact('company','order', 'total');
		    }

		$this->set('title_for_layout', 'Commande #'.$order['Order']['id']);
		$this->set('company', $company);
		$this->set('order', $order);
		$this->set('total', $total);
		
		$products = $this->Order->OrderedItem->Product->find('list');
		$this->set('products', $products);

	}

	public function email($id = null) {
	
		
		$this->view($id);
		$this->layout = 'pdf/default';
		$pdf = $this->render('pdf/view');
		$this->output = '';

		$res = $this->requestAction(array('action'=>'view',$id));

		$mail = array('user'=>$res['order'],
						'view' => 'order',
						'data' => $res,
						'subject' => 'Facture #'.$id,
						'message' => '',
						'attachment' => array('facture.pdf' => array( 'data' => $pdf ))
					);
		$this->sendMail($mail);
		$order = $this->Order->findById($id);
		$order['Order']['status'] = 'emailed';
		$this->Order->save($order);
	      if (!empty($this->request->params['requested']))
	      {
		return;
	      }
		return $this->redirect('/orders/view/'.$id);
	}
	
/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {

			

			$shop = $this->Order->Shop->findById($this->request->data['Order']['shop_id']);
			$discount = '';
			if($this->request->data['Order']['user_id'] != '')
			{
                          $user = $this->Order->User->findById($this->request->data['Order']['user_id']);
                          if($user['User']['company_id'] != $this->getCompanyId() )
                          {
                            throw new NotFoundException(__('Invalid user for this company'));
                          }
                          $discount = $user['User']['discount'];
                          unset($this->request->data['User']);
                        }
                        else
                        {
                          $discount = $this->request->data['User']['discount'];
                        }
			if($shop['Shop']['company_id'] != $this->getCompanyId() )
			{
			  throw new NotFoundException(__('Invalid shop for this company'));
			}


			$this->Order->create();
			$this->request->data['Order']['status'] = 'reserved';
			$delivery = $this->Functions->viewDateToDateTime($this->request->data['Order']['delivery_date']);
			if($delivery != false )
			{
				$this->request->data['Order']['delivery_date'] = $delivery->format('Y-m-d H:i:s');	
			}
			$this->request->data['Order']['discount'] = $discount;
			if($this->request->data['Order']['user_id'] == '')
                        {
                          $this->request->data['User']['company_id'] = $this->getCompanyId();
                          $this->request->data['User']['regular'] = false;
                          $this->Order->User->save($this->request->data);
                          $this->request->data['Order']['user_id'] = $this->Order->User->getInsertID();
                        }
			
			if ($this->Order->save($this->request->data)) {
				$this->Session->setFlash(__('The order has been saved'),'flash/ok');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The order could not be saved. Please, try again.'),'flash/fail');
			}
		}
		$shops = $this->Order->Shop->find('list', array('conditions'=>array('company_id' => $this->getCompanyId())));
		$users = $this->Order->User->find('list', array('conditions'=>array('company_id' => $this->getCompanyId())));
		$users = array(''=>'-') + $users;
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
		$order = $this->Order->findById($id);
		if($order['Shop']['company_id'] != $this->getCompanyId() )
		{
		  throw new NotFoundException(__('Invalid order for this company'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
		
			$shop = $this->Order->Shop->findById($this->request->data['Order']['shop_id']);
			if($shop['Shop']['company_id'] != $this->getCompanyId() )
			{
			  throw new NotFoundException(__('Invalid shop for this company'));
			}
                        
                        if($this->request->data['Order']['user_id'] != '')
                        {
                          $user = $this->Order->User->findById($this->request->data['Order']['user_id']);
                          if($user['User']['company_id'] != $this->getCompanyId() )
                          {
                            throw new NotFoundException(__('Invalid user for this company'));
                          }
                          unset($this->request->data['User']);
                        }
                        else
                        {

                        }

			$delivery = $this->Functions->viewDateToDateTime($this->request->data['Order']['delivery_date']);
			if($delivery != false )
			{
				$this->request->data['Order']['delivery_date'] = $delivery->format('Y-m-d H:i:s');	
			}
			
                        if($this->request->data['Order']['user_id'] == '')
                        {
                          $this->request->data['User']['discount'] = $this->request->data['Order']['discount'];
                          $this->request->data['User']['company_id'] = $this->getCompanyId();
                          $this->request->data['User']['regular'] = false;
                          $this->Order->User->save($this->request->data);
                          $this->request->data['Order']['user_id'] = $this->Order->User->getInsertID();
                        }
			
			if ($this->Order->save($this->request->data)) {
				$this->Session->setFlash(__('The order has been saved'),'flash/ok');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The order could not be saved. Please, try again.'), 'flash/fail');
			}
		} else {
			$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
			$this->request->data = $this->Order->find('first', $options);
		}
		$shops = $this->Order->Shop->find('list', array('conditions'=>array('company_id' => $this->getCompanyId())));
		$users = $this->Order->User->find('list', array('conditions'=>array('company_id' => $this->getCompanyId())));
		$users = array(''=>'-') + $users;
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
		
		$order = $this->Order->findById($id);
		if($order['Shop']['company_id'] != $this->getCompanyId() )
		{
		  throw new NotFoundException(__('Invalid order for this company'));
		}
		
		$this->request->onlyAllow('post', 'delete');
		if ($this->Order->delete()) {
			$this->Session->setFlash(__('Order deleted'),'flash/ok');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Order was not deleted'),'flash/fail');
		$this->redirect(array('action' => 'index'));
	}
}
