<?php
App::uses('AppController', 'Controller');
/**
 * Events Controller
 *
 * @property Event $Event
 */
class EventsController extends AppController {
  var $uses = array('Event', 'Gevent', 'EventType');

/**
 * index method
 *
 * @return void
 */
  public function index() {
    $events = $this->Event->find('all');


    //debug($events);
    $this->set('events', $events);
//     debug($this->Gevent->find('all'));
//     $event = array();
//     $event['Gevent']['title'] = 'NAZDAAAR';
// //     $event['Gevent']['id'] = '78uhhuossc8dv12cqmo3tcc6t8';
// //     $geventDate = array();
//     $geventDate['start'] = '2013-03-28T13:00:00.000+01:00';
//     $geventDate['stop'] = '2013-03-29T13:00:00.000+01:00'; 
//     $event['GeventDate'][] = $geventDate;
//     $this->Gevent->create();
//     debug($this->Gevent->save($event)); 
//     debug($event);
  }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function view($id = null) {
    if (!$this->Event->exists($id)) {
      throw new NotFoundException(__('Invalid event'));
    }
    $options = array('conditions' => array('Event.' . $this->Event->primaryKey => $id));
    $this->set('event', $this->Event->find('first', $options));
  }

/**
 * add method
 *
 * @return void
 */
  public function add($eventTypeId = null, $geventId = null) {
    if ($this->request->is('post')) {
      $this->Event->create();
      if(!isset($this->request->data['Event']['gevent_id']))
      {
        $this->request->data['Gevent']['title'] = $this->request->data['Event']['title'];
        $this->request->data['Gevent']['description'] = $this->request->data['Event']['description'];
      
        $datetime= DateTime::createFromFormat("d/m/Y", $this->request->data['Event']['start']);
        if($datetime == false)
        {
          $this->Event->invalidateField('start');
        }
        else
        {
          $this->request->data['GeventDate']['start'] = $datetime->format('Y-m-d H:i:s');
        }
       }
      
      if ($this->Event->save($this->request->data)) {
        $this->Session->setFlash(__('The event has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The event could not be saved. Please, try again.'));
      }
    }
    if( $eventTypeId != null )
    {
       $this->request->data['Event']['event_type_id'] = $eventTypeId;
      $this->set('geventId',$geventId);
      
    }
    
    $media = $this->Event->Media->find('list');
    $products = $this->Event->Product->find('list');
    $eventTypes = $this->Event->EventType->find('list');
    $this->set(compact('media', 'products', 'eventTypes'));
  }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function edit($id = null) {
    if (!$this->Event->exists($id)) {
      throw new NotFoundException(__('Invalid event'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->Event->save($this->request->data)) {
        $this->Session->setFlash(__('The event has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The event could not be saved. Please, try again.'));
      }
    } else {
      $options = array('conditions' => array('Event.' . $this->Event->primaryKey => $id));
      $this->request->data = $this->Event->find('first', $options);
    }
    $media = $this->Event->Media->find('list');
    $products = $this->Event->Product->find('list');
    $eventTypes = $this->Event->EventType->find('list');
    $this->set(compact('media', 'products', 'eventTypes'));
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
    $this->Event->id = $id;
    if (!$this->Event->exists()) {
      throw new NotFoundException(__('Invalid event'));
    }
    $this->request->onlyAllow('post', 'delete');
    if ($this->Event->delete()) {
      $this->Session->setFlash(__('Event deleted'));
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Event was not deleted'));
    $this->redirect(array('action' => 'index'));
  }
}
