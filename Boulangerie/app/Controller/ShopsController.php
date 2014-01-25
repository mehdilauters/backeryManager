<?php
App::uses('AppController', 'Controller');
/**
 * Shops Controller
 *
 * @property Shop $Shop
 */
class ShopsController extends AppController {
var $publicActions = array('index','view','isOpened');
var $uses = array('Shop', 'EventType');
var $helpers = array('Time');
/**
 * index method
 *
 * @return void
 */
  public function index() {
    $this->Shop->recursive = 0;
    $isCalendarAvailable = $this->requestAction(array('controller'=>'events', 'action'=>'eventsAvailable'));
    $this->set('isCalendarAvailable', $isCalendarAvailable);
    $this->set('shops', $this->paginate());
  }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function view($id = null) {
    if (!$this->Shop->exists($id)) {
      throw new NotFoundException(__('Invalid shop'));
    }
    
    
    $options = array('conditions' => array('Shop.' . $this->Shop->primaryKey => $id));
    $this->Shop->recursive = 3;
    $shop = $this->Shop->find('first', $options);
    
    $this->menu['Menu']['Nos Magasins']['active'] = true;

    $isCalendarAvailable = $this->requestAction(array('controller'=>'events', 'action'=>'eventsAvailable'));
    $this->set('isCalendarAvailable', $isCalendarAvailable);
    $this->set('title_for_layout', $shop['Shop']['name']);
    $this->set('isOpened', $this->isOpened($shop));
    $this->set('shop', $shop);
  }
  
  
  public function isOpened($data)
  {
    $open = false;
    foreach ($data['EventType']['Event'] as $event)
    {
      foreach ($event['Gevent']['GeventDate'] as $date) {
        $now = new DateTime('now');
        $dateStart = new DateTime($date['start']);
        $dateEnd = new DateTime($date['end']);
        if($dateStart <= $now && $now <= $dateEnd)
        {
          $open = true;
          break;
        }
      }
      if($open)
      {
        break;
      }
    }
    return $open;
  }

/**
 * add method
 *
 * @return void
 */
  public function add() {
    if ($this->request->is('post')) {
      $this->Shop->create();
      if ($this->Shop->save($this->request->data)) {
        $this->Session->setFlash(__('The shop has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The shop could not be saved. Please, try again.'));
      }
    }
    $media = $this->Shop->Media->find('list');
    $eventTypes = $this->Shop->EventType->find('list');
    $this->set(compact('media','eventTypes'));
  }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function edit($id = null) {
    if (!$this->Shop->exists($id)) {
      throw new NotFoundException(__('Invalid shop'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->Shop->save($this->request->data)) {
        $this->Session->setFlash(__('The shop has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The shop could not be saved. Please, try again.'));
      }
    } else {
      $options = array('conditions' => array('Shop.' . $this->Shop->primaryKey => $id));
      $this->request->data = $this->Shop->find('first', $options);
    }
    $media = $this->Shop->Media->find('list');
    $eventTypes = $this->Shop->EventType->find('list');
    $this->set(compact('media','eventTypes'));
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
    $this->Shop->id = $id;
    if (!$this->Shop->exists()) {
      throw new NotFoundException(__('Invalid shop'));
    }
    $this->request->onlyAllow('post', 'delete');
    if ($this->Shop->delete()) {
      $this->Session->setFlash(__('Shop deleted'));
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Shop was not deleted'));
    $this->redirect(array('action' => 'index'));
  }
}
