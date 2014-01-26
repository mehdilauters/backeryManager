<?php
App::uses('AppController', 'Controller');
/**
 * Events Controller
 *
 * @property Event $Event
 */
class EventsController extends AppController {
	var $publicActions = array('eventsAvailable','isEvent', 'isToday');

  var $uses = array('Event', 'Gevent', 'EventType');


  public function calendar()
  {
    $this->set('title_for_layout', 'Horaires');
    $this->menu['Menu']['Horaires']['active'] = true;
    $events = $this->Event->find('all');
    $this->set('events', $events);
  }

/**
 * index method
 *
 * @return void
 */
  public function index() {
    $this->set('title_for_layout', 'Horaires');
    $this->menu['Menu']['Horaires']['active'] = true;

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

public function eventsAvailable()
{
  return $this->Gevent->getDataSource()->isReady();
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
	if( isset( $this->request->named['idProduct'] ) )
        {
// 	    $this->request->data['Gevent']['description'].= "\n".Router::url('/', true).'/products/view/'.$this->request->named['idProduct'];
	    // TODO howto... take the good one
	    $this->request->data['Event']['event_type_id'] = 3;
	}
	
      
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
        if( isset( $this->request->named['idProduct'] ) )
        {
          $this->redirect(array('controller'=>'products','action' => 'view', $this->request->named['idProduct']));          
        }
        else
        {
          $this->redirect(array('action' => 'index'));
        }
      } else {
        $this->Session->setFlash(__('The event could not be saved. Please, try again.'));
      }
    }
    if( $eventTypeId != null )
    {
       $this->request->data['Event']['event_type_id'] = $eventTypeId;
      $this->set('geventId',$geventId);
      
    }
    
    $media = array_merge(array(''=>''), $this->Event->Media->find('list'));
    
    if(isset($this->request->named['idProduct']))
    {
      $products = $this->Event->Product->find('list', array('conditions'=>array('id'=>$this->request->named['idProduct'])));
      $values = array_values($products);
      $this->request->data['Event']['title'] = array_shift( $values );

      $this->request->data['Event']['description'] = 'Fabrication';
    }
    else
    {
      $products = $this->Event->Product->find('list');
      $products[''] = '';
    }
    $now = new DateTime();
    $this->request->data['Event']['start'] = $now->format('d/m/Y');
    
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
    $media = array_merge(array(''=>''), $this->Event->Media->find('list'));
    
    $products = $this->Event->Product->find('list');
    $products[''] = '';
    
    $eventTypes = $this->Event->EventType->find('list');
    $this->set(compact('media', 'products', 'eventTypes'));
  }



public function isToday($event)
{
    $isToday = false;
    foreach($event['Events'] as $event_i)
    {
      //public function isEvent($gevent, $when = 'now')
      $isToday = $this->isEvent($event_i);
      if($isToday)
      {
       break; 
      }

     }
  return $isToday;
}
  
  /**
  * @params : Gevent containing
  * @ params : string (for DateTime
  *
  */
  public function isEvent($gevent, $when = 'now')
  {
    $yes = false;
      $when = new DateTime( $when );
      if(isset($gevent['Gevent']['GeventDate']))
      {
	foreach($gevent['Gevent']['GeventDate'] as $geventDate)
	{
	  $startDate = new DateTime($geventDate['start']);
	  $endDate = new DateTime($geventDate['end']);
	if($startDate <= $when && $when <= $endDate)
	  {
	    $yes = true;
	    break;
	  }
	}
      }
      return $yes;
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
