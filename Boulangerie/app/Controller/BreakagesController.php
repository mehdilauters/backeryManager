<?php
App::uses('AppController', 'Controller');
/**
 * Breakages Controller
 *
 * @property Breakage $Breakage
 */
class BreakagesController extends AppController {

  var $uses = array('Breakage', 'Sale', 'ProductType');
/**
 * index method
 *
 * @return void
 */
	public function index() {
		//$this->Breakage->contain();
		/*$breakages = $this->Breakage->find('all', array('order' => 'shop_id'));
		foreach($breakages as $i => $breakage)
		{
		  $this->Sale->contain();
		  $breakages[$i]['Sales'] = $this->Sale->find('all',array(
		    	      'conditions' => array(
						      'Sale.date' => $breakage['Breakage']['date'],
						      'Sale.product_id in (select P.id from products P where P.product_types_id = '.$breakage['Breakage']['product_types_id'].')',
						      'Sale.shop_id' => $breakage['Breakage']['shop_id']
						    ),
		   ));
		}
 		//debug($breakages);
		$productTypes = $this->ProductType->find('list');
debug($productTypes);
		$this->set('productTypes', $productTypes);
		$this->set('breakages', $breakages);
  */
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Breakage->exists($id)) {
			throw new NotFoundException(__('Invalid breakage'));
		}
		$options = array('conditions' => array('Breakage.' . $this->Breakage->primaryKey => $id));
		$this->set('breakage', $this->Breakage->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Breakage->create();
			if ($this->Breakage->save($this->request->data)) {
				$this->Session->setFlash(__('The breakage has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The breakage could not be saved. Please, try again.'));
			}
		}
		$shops = $this->Breakage->Shop->find('list');
		$productTypes = $this->Breakage->ProductType->find('list');
		$this->set(compact('shops', 'productTypes'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Breakage->exists($id)) {
			throw new NotFoundException(__('Invalid breakage'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Breakage->save($this->request->data)) {
				$this->Session->setFlash(__('The breakage has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The breakage could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Breakage.' . $this->Breakage->primaryKey => $id));
			$this->request->data = $this->Breakage->find('first', $options);
		}
		$shops = $this->Breakage->Shop->find('list');
		$productTypes = $this->Breakage->ProductType->find('list');
		$this->set(compact('shops', 'productTypes'));
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
		$this->Breakage->id = $id;
		if (!$this->Breakage->exists()) {
			throw new NotFoundException(__('Invalid breakage'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Breakage->delete()) {
			$this->Session->setFlash(__('Breakage deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Breakage was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
