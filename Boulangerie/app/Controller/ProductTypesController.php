<?php
App::uses('AppController', 'Controller');
/**
 * ProductTypes Controller
 *
 * @property ProductType $ProductType
 */
class ProductTypesController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->ProductType->recursive = 0;
		$this->set('productTypes', $this->paginate());
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
		$options = array('conditions' => array('ProductType.' . $this->ProductType->primaryKey => $id));
		$this->set('productType', $this->ProductType->find('first', $options));
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
		$media = $this->ProductType->Media->find('list');
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
		$media = $this->ProductType->Media->find('list');
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
