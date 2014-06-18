<?php
/*
 * Controller/EventsController.php
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */

class EventsController extends FullCalendarAppController {
// 	var $uses = array('FullCalendar.Event');
	var $name = 'Events';

        var $paginate = array(
            'limit' => 15
        );

        function index($eventTypeId = null) {
		$this->Event->recursive = 1;

		if($eventTypeId != null)
		{
			$this->paginate['conditions']['Event.event_type_id'] = $eventTypeId;
		}

		$this->set('events', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid event', true));
			$this->redirect(array('action' => 'index'));
		}
// 		$this->Event->bindModel(
// 			array ('belongsTo' => array ('EventType'))
// 		);
		$this->set('event', $this->Event->read(null, $id));
	}


	private function viewDateToDateTime($dateString)
	  {
	    $dateTime = false; //new DateTime($dateString);
	    if($dateTime == false)
	    {
	      //->format('Y-m-d H:i')
	      $dateTime = DateTime::createFromFormat ( 'd/m/Y H:i' , $dateString );
	      if($dateTime == false)
	      {
		  $dateTime = DateTime::createFromFormat ( 'd/m/Y H:i' , $dateString.' 00:00' );
	      }
	    }
	    return  $dateTime;
	  }


	function add($eventTypeId = null) {
		if (!empty($this->data)) {
			debug($this->data);
			$this->Event->create();
			$event = $this->data;
			if($this->data['Event']['recursive'] != '')
			{
			  $startDate = $this->viewDateToDateTime($this->data['Event']['recursive_start'] . ' 00:00');
			  $endDate = $this->viewDateToDateTime($this->data['Event']['recursive_end'] . ' 23:59');
			  if($startDate != false &&  $endDate !=false )
			  {
			    $event['Event']['recursive_start'] = $startDate->format('Y-m-d H:i:s');
			    $event['Event']['recursive_end'] = $endDate->format('Y-m-d H:i:s'); 
			  }
			}

			  $startDate = $this->viewDateToDateTime($this->data['Event']['start']);
			  $endDate = $this->viewDateToDateTime($this->data['Event']['end']);
			  if($startDate != false &&  $endDate !=false )
			  {
 			    $event['Event']['start'] = $startDate->format('Y-m-d H:i:s');
 			    $event['Event']['end'] = $endDate->format('Y-m-d H:i:s');
			  }

			  if($eventTypeId != null)
			  {
				$event['Event']['event_type_id'] = $eventTypeId;
				}
			  
			if ($this->Event->save($event)) {
				$this->Session->setFlash(__('The event has been saved', true));
				if($eventTypeId != null)
				{
				  $this->redirect(array('controller'=>'eventTypes', 'action' => 'view', $eventTypeId));
				}
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.', true));
			}
		}
// 		$this->Event->bindModel(
// 			array ('belongsTo' => array ('EventType'))
// 		);
	if($eventTypeId != null)
	{
		$this->set('eventType', $this->Event->EventType->findById($eventTypeId));
	}
	
		$this->set('eventTypes', $this->Event->EventType->find('list'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid event', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$event = $this->data;
			if($this->data['Event']['recursive'] != '')
			{
			  $startDate = $this->viewDateToDateTime($this->data['Event']['recursive_start']);
			  $endDate = $this->viewDateToDateTime($this->data['Event']['recursive_end']);
			  if($startDate != false &&  $endDate !=false )
			  {
			    $event['Event']['recursive_start'] = $startDate->format('Y-m-d H:i:s');
			    $event['Event']['recursive_end'] = $endDate->format('Y-m-d H:i:s');
			  }
			  
			}

			  $startDate = $this->viewDateToDateTime($this->data['Event']['start']);
			  $endDate = $this->viewDateToDateTime($this->data['Event']['end']);
			  if($startDate != false &&  $endDate !=false )
			  {
			    $event['Event']['start'] = $startDate->format('Y-m-d H:i:s');
			    $event['Event']['end'] = $endDate->format('Y-m-d H:i:s');
			  }
			if ($this->Event->save($event)) {
				$this->Session->setFlash(__('The event has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Event->read(null, $id);
		}
// 		$this->Event->bindModel(
// 			array ('belongsTo' => array ('EventType'))
// 		);
		$this->set('eventTypes', $this->Event->EventType->find('list'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for event', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Event->delete($id)) {
			$this->Session->setFlash(__('Event deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Event was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	
	
        // The feed action is called from "webroot/js/ready.js" to get the list of events (JSON)
	function feed($idType = null, $start = null, $end = null) {
		$this->layout = "ajax";
		$vars = $this->params['url'];
		if($start == null)
		{
		  $start = $vars['start'];
		}

		if($end == null)
		{
		  $end = $vars['end'];
		}
		
		$data = array();
		
		
		$conditions = array('conditions' => array(
				'UNIX_TIMESTAMP(end) >=' => $start,
				'UNIX_TIMESTAMP(start) <=' => $end,
				'recursive is null'
				));
		if($idType != null)
		{
			$conditions['conditions']['Event.event_type_id'] = $idType;
		}
		
		$events = $this->Event->find('all', $conditions);
		foreach($events as $event) {
			if($event['Event']['all_day'] == 1) {
				$allday = true;
				$endEvent = $event['Event']['start'];
			} else {
				$allday = false;
				$endEvent = $event['Event']['end'];
			}
			$data[] = array(
					'id' => $event['Event']['id'],
					'title'=>$event['Event']['title'],
					'start'=>$event['Event']['start'],
					'end' => $endEvent,
					'allDay' => $allday,
					'url' => '/boulangerie/full_calendar/events/view/'.$event['Event']['id'],
					'details' => $event['Event']['details'],
					// 'className' => $event['EventType']['color']
			);
		}
		
		$recursiveConditions = array('conditions' => 
				array(
					 'UNIX_TIMESTAMP(recursive_start) <=' => $end,
					 'UNIX_TIMESTAMP(recursive_end) >=' => $start,
					 'recursive is not null'
					)
			);
		if($idType != null)
		{
			$recursiveConditions['conditions']['Event.event_type_id'] = $idType;
		}
		$recursiveEvents = $this->Event->find('all', $recursiveConditions);
		$dateStart = new DateTime();
		$dateStart->setTimestamp($start);
		
		$dateStop = new DateTime();
		$dateStop->setTimestamp($end);
		foreach($recursiveEvents as $event) {
			if($event['Event']['all_day'] == 1) {
				$allday = true;
				$endEvent = $event['Event']['start'];
			} else {
				$allday = false;
				$endEvent = $event['Event']['end'];
			}
			
			$eventStart = new DateTime($event['Event']['start']);
			$eventStop = new DateTime($event['Event']['end']);
			
			$recursiveEventStart = new DateTime($event['Event']['recursive_start']);
			$recursiveEventStop = new DateTime($event['Event']['recursive_end']);
			switch($event['Event']['recursive'])
			{
			// ('day', 'week', 'month', 'year'
				case 'day':
					$nbDays = -$dateStart->diff($eventStart)->format('%R%a');
					$nbEvents = $dateStop->diff($dateStart)->format('%R%a');

					for($i = 0; $i< abs($nbEvents); $i++)
					{

						$newDateStart = strtotime(($nbDays+$i)." day", $eventStart->getTimestamp());
						$newDateStop = strtotime(($nbDays+$i)." day", $eventStop->getTimestamp());
						
						$start = new DateTime();
						$start->setTimestamp($newDateStart);

						
						if($start >= $recursiveEventStop)
						{
							break;
						}
						
						$event['Event']['start'] = date('Y-m-d H:i:s', $newDateStart);
						$event['Event']['end'] = date('Y-m-d H:i:s', $newDateStop);
						$data[] = array(
							'id' => $event['Event']['id'].$newDateStart,
							'title'=>$event['Event']['title'],
							'start'=>$event['Event']['start'],
							'end' => $event['Event']['end'],
							'allDay' => $allday,
							'url' => '/boulangerie/full_calendar/events/view/'.$event['Event']['id'],
							'details' => $event['Event']['details'],
							//'className' => $event['EventType']['color']
					);
					}
				break;
				case 'week':
					//debug(week);
					$nbWeeks = round( - ( $dateStart->diff($eventStart)->format('%R%a') ) / 7 );
					
					$nbEvents = round( abs( $dateStop->diff($dateStart)->format('%R%a') ) / 7 );
					for($i = 0; $i< abs($nbEvents); $i++)
					{

						$newDateStart = strtotime(($nbWeeks+$i)." week", $eventStart->getTimestamp());
						$newDateStop = strtotime(($nbWeeks+$i)." week", $eventStop->getTimestamp());
						
						$start = new DateTime();
						$start->setTimestamp($newDateStart);

						
						if($start >= $recursiveEventStop)
						{
							break;
						}
						
						$event['Event']['start'] = date('Y-m-d H:i:s', $newDateStart);
						$event['Event']['end'] = date('Y-m-d H:i:s', $newDateStop);
						$data[] = array(
							'id' => $event['Event']['id'].$newDateStart,
							'title'=>$event['Event']['title'],
							'start'=>$event['Event']['start'],
							'end' => $event['Event']['end'],
							'allDay' => $allday,
							'url' => '/boulangerie/full_calendar/events/view/'.$event['Event']['id'],
							'details' => $event['Event']['details'],
							//'className' => $event['EventType']['color']
					);
					}
				break;
				case 'month':
					$nbMonth = round( - ( $dateStart->diff($eventStart)->format('%R%a') ) / 30 +1) ;
					
					$nbEvents = round( ( $dateStop->diff($dateStart)->format('%R%a') ) / 30 );

					for($i = 0; $i< abs($nbEvents); $i++)
					{

						$newDateStart = strtotime(($nbMonth+$i)." month", $eventStart->getTimestamp());
						$newDateStop = strtotime(($nbMonth+$i)." month", $eventStop->getTimestamp());
						
						$start = new DateTime();
						$start->setTimestamp($newDateStart);

						
						if($start >= $recursiveEventStop)
						{
							break;
						}
						
						$event['Event']['start'] = date('Y-m-d H:i:s', $newDateStart);
						$event['Event']['end'] = date('Y-m-d H:i:s', $newDateStop);
						$data[] = array(
							'id' => $event['Event']['id'].$newDateStart,
							'title'=>$event['Event']['title'],
							'start'=>$event['Event']['start'],
							'end' => $event['Event']['end'],
							'allDay' => $allday,
							'url' => '/boulangerie/full_calendar/events/view/'.$event['Event']['id'],
							'details' => $event['Event']['details'],
							//'className' => $event['EventType']['color']
					);
					}
				break;
				case 'year':
					$nbYear = round( - ( $dateStart->diff($eventStart)->format('%R%a') ) / 365 +1);
					$nbEvents = round( ( $dateStop->diff($dateStart)->format('%R%a') ) / 365 ) + 1;

					
					for($i = 0; $i< abs($nbEvents); $i++)
					{

						$newDateStart = strtotime(($nbYear+$i)." year", $eventStart->getTimestamp());
						$newDateStop = strtotime(($nbYear+$i)." year", $eventStop->getTimestamp());
						
						$start = new DateTime();
						$start->setTimestamp($newDateStart);

						
						if($start >= $recursiveEventStop)
						{
							break;
						}
						
						$event['Event']['start'] = date('Y-m-d H:i:s', $newDateStart);
						$event['Event']['end'] = date('Y-m-d H:i:s', $newDateStop);
						$data[] = array(
							'id' => $event['Event']['id'].$newDateStart,
							'title'=>$event['Event']['title'],
							'start'=>$event['Event']['start'],
							'end' => $event['Event']['end'],
							'allDay' => $allday,
							'url' => '/boulangerie/full_calendar/events/view/'.$event['Event']['id'],
							'details' => $event['Event']['details'],
							//'className' => $event['EventType']['color']
					);
					}
				break;
				default:
				break;
			}
			
			
		}
		if (!empty($this->request->params['requested'])) {
			    return $data;
			}
		$this->set("json", json_encode($data));
	}

        // The update action is called from "webroot/js/ready.js" to update date/time when an event is dragged or resized
	function update() {
		$vars = $this->params['url'];
		$this->Event->id = $vars['id'];
		$this->Event->saveField('start', $vars['start']);
		$this->Event->saveField('end', $vars['end']);
		$this->Event->saveField('all_day', $vars['allday']);
	}

}
?>
