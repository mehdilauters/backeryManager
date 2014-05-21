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
    $this->Shop->contain();
    $this->Shop->contain('Media.Photo', 'EventType');
    $shop = $this->Shop->find('first', $options);

    $this->set('title_for_layout', $shop['Shop']['name']);
	// function feed($idType = null, $start = null, $end = null) {
	// $shop['EventType']['Event'] = $this->requestAction(array(	'plugin'=>'full_calendar',
						  // 'controller'=>'events',
						  // 'action'=>'feed'
					  // ),
					  // array( 
						  // 'pass'=>array(
									  // 'idType'=>2,//$shop['EventType']['id'],
									  // 'start' => time(),
									  // 'end'=>(time() + 60*24*7)
									  // )
							  // )
							  // ,array('return')
									      // );
										  
	$shop['EventType']['Event'] = $this->requestAction('/full_calendar/events/feed/'.$shop['EventType']['id'].'/'.time().'/'.(time() + 60*60*24*7));
    $this->set('isOpened', $this->isOpened($shop));
    $this->set('shop', $shop);
	
	
	
	if($this->Auth->user('isRoot'))
	{
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array('shop'=>$id), 'group' => array('time'=>'week', 'shop'=>'shop', 'productType'=>'productType'))));
		$this->set('resultsEntries',$res);
	}
  }
  
  
  public function isOpened($data)
  {
    $open = false;
    foreach ($data['EventType']['Event'] as $event)
    {
		$now = new DateTime('now');
		$dateStart = new DateTime($event['start']);
		$dateEnd = new DateTime($event['end']);
		if($dateStart <= $now && $now <= $dateEnd)
		{
		  $open = true;
		  break;
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
		$this->EventType->create();
		$eventType = array( 'EventType' =>array( 'name' => $this->request->data['Shop']['name']));
		$this->EventType->save($eventType);
		$this->request->data['Shop']['event_type_id'] = $this->EventType->id;
		
		if ($this->Shop->save($this->request->data)) {
			        $this->Session->setFlash(__('The shop has been saved'),'flash/ok');
					$this->redirect(array('action' => 'index'));
		}
		else
		{
			$this->Session->setFlash(__('The shop has been saved but eventType not created'),'flash/warning');
			$this->redirect(array('plugin'=>'full_calendar', 'controller'=>'eventTypes', 'action' => 'add'));
		}
		
      } else {
        $this->Session->setFlash(__('The shop could not be saved. Please, try again.'),'flash/error');
      }
    }
    $media = array(''=>'')  + $this->Shop->Media->find('list');
    $eventTypes = array_merge(array(''),$this->Shop->EventType->find('list'));
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
        $this->Session->setFlash(__('The shop has been saved'),'flash/ok');
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The shop could not be saved. Please, try again.'),'flash/fail');
      }
    } else {
      $options = array('conditions' => array('Shop.' . $this->Shop->primaryKey => $id));
      $this->request->data = $this->Shop->find('first', $options);
    }
    $media = array(''=>'') + $this->Shop->Media->find('list');
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
      $this->Session->setFlash(__('Shop deleted'),'flash/ok');
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Shop was not deleted'),'flash/fail');
    $this->redirect(array('action' => 'index'));
  }


  public function beforeRender()
  {
    $this->menu['Menu']['Magasins']['active'] = true;
    parent::beforeRender();
  }

}
