<?php
App::uses('AppController', 'Controller');
/**
 * EventTypes Controller
 *
 * @property EventType $EventType
 */
class EventTypesController extends AppController {
  var $uses = array('EventType', 'Gcalendar', 'Gevent', 'Event');
/**
 * index method
 *
 * @return void
 */
  public function index() {
    $this->EventType->recursive = 0;
    $this->set('eventTypes', $this->paginate());
  }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function view($id = null) {
    if (!$this->EventType->exists($id)) {
      throw new NotFoundException(__('Invalid event type'));
    }
    $options = array('conditions' => array('EventType.' . $this->EventType->primaryKey => $id));
    $this->set('eventType', $this->EventType->find('first', $options));
  }

/**
 * add method
 *
 * @return void
 */
  public function add() {
    if ($this->request->is('post')) {
      $this->EventType->create();
      if ($this->EventType->save($this->request->data)) {
        $this->Session->setFlash(__('The event type has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The event type could not be saved. Please, try again.'));
      }
    }
      $calendars = $this->Gcalendar->find('list');
    debug( $calendars);
      $this->set(compact('calendars'));
  }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function edit($id = null) {
    if (!$this->EventType->exists($id)) {
      throw new NotFoundException(__('Invalid event type'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->EventType->save($this->request->data)) {
        $this->Session->setFlash(__('The event type has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The event type could not be saved. Please, try again.'));
      }
    } else {
      $options = array('conditions' => array('EventType.' . $this->EventType->primaryKey => $id));
      $this->request->data = $this->EventType->find('first', $options);

      $calendars = $this->Gcalendar->find('list');
      $this->set(compact('calendars'));
    }
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
    $this->EventType->id = $id;
    if (!$this->EventType->exists()) {
      throw new NotFoundException(__('Invalid event type'));
    }
    $this->request->onlyAllow('post', 'delete');
    if ($this->EventType->delete()) {
      $this->Session->setFlash(__('Event type deleted'));
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Event type was not deleted'));
    $this->redirect(array('action' => 'index'));
  }
  
  public function listUnknown() {
    $notKnownEvents = array();
    $this->EventType->recursive = -1;
    $eventTypes = $this->EventType->find('all');
    foreach($eventTypes as $eventType)
    {
      //debug($eventType['EventType']['name']);
      $notKnownEvents[$eventType['EventType']['id']]['events'] = array();
      $notKnownEvents[$eventType['EventType']['id']]['name'] = $eventType['EventType']['name'];
      $gevents = $this->Gevent->find('all', array('conditions'=>array('calendar_id'=>$eventType['EventType']['calendar_id'])));
      foreach($gevents as $gevent)
      {
        //debug($gevent);
        $event = $this->Event->findByGeventId($gevent['Gevent']['id']);
        if( count( $event ) == 0 )
        {
          $notKnownEvents[$eventType['EventType']['id']]['events'][$gevent['Gevent']['id']] = $gevent;
        }
        else
        {
          debug('event '.$eventType['EventType']['name'].'['.$gevent['Gevent']['id'].'] =>'.$gevent['Gevent']['title'].' already known');
        }
        //debug($title); */
      }
      $this->set('notKnownEvents', $notKnownEvents);
//       debug($eventType);
    }
  }
}
