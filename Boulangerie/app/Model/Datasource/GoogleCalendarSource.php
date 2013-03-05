<?php

set_include_path(get_include_path() . PATH_SEPARATOR . APP.'Vendor');
require_once 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_Calendar');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');

class GoogleCalendarSource extends DataSource {
  public $name = 'GoogleCalendarSource';
/**
 * An optional description of your datasource
 */
    public $description = 'Google calendar datasource';

    public $config = array(
        'calendarId' => '',
      'login' => '',
      'password' => '',
        
    );
    
    public $service = null;



/**
 * Créons notre HttpSocket et gérons any config tweaks.
 */
    public function __construct($config) {
        parent::__construct($config);
        $this->service = null;
        // Create an authenticated HTTP client
        $service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
        try {
          $client = Zend_Gdata_ClientLogin::getHttpClient($this->config['login'], $this->config['password'], $service);
          $this->service = new Zend_Gdata_Calendar($client);          
        } catch (Exception $e) {
          $this->log($e->getMessage(), 'debug');
        }

    }

/**
 * Since datasources normally connect to a database there are a few things
 * we must change to get them to work without a database.
 */

/**
 * listSources() is for caching. You'll likely want to implement caching in
 * your own way with a custom datasource. So just ``return null``.
 */
//     public function listSources() {
//         return null;
//     }

/**
 * describe() tells the model your schema for ``Model::save()``.
 *
 * You may want a different schema for each model but still use a single
 * datasource. If this is your case then set a ``schema`` property on your
 * models and simply return ``$Model->schema`` here instead.
 */
    public function describe($model) {
        return $this->$model->_schema[$model->alias];
    }

/**
 * calculate() is for determining how we will count the records and is
 * required to get ``update()`` and ``delete()`` to work.
 *
 * We don't count the records here but return a string to be passed to
 * ``read()`` which will do the actual counting. The easiest way is to just
 * return the string 'COUNT' and check for it in ``read()`` where
 * ``$data['fields'] == 'COUNT'``.
 */
    public function calculate(Model $Model, $func, $params = array()) {
        return 'COUNT';
    }

    public function delete(Model $Model, $conditions = null) {
     debug('delete not implemented');
    }
    
    public function update(Model $Model, $fields = null, $values = null, $conditions = null) {
      debug('update not implemented');
//       return $this->create($Model, $fields, $values);
    }
    
    public function create(Model $model, $fields = array(), $values = array())
    {
      if($this->service == null)
      {
        $this->log($this->name.'::create => not available', 'debug');
        return false;
      }
      debug(parent::create($model,$fields, $values));

      $data = $model->data;
      $event= $this->service->newEventEntry();
      $event->title = $this->service->newTitle($data[$model->alias]['title']);
      $event->where = array($this->service->newWhere("Mountain View, California"));
      $event->content = $this->service->newContent("Cakephp Event");
//     debug($model->data);
      // Set the date using RFC 3339 format.
      
      $when = $this->service->newWhen();
      $when->startTime = $data['GeventDate'][0]['start'];
      $when->endTime = $data['GeventDate'][0]['stop'];
      $event->when = array($when);
      
      // Upload the event to the calendar server
      // A copy of the event as it is recorded on the server is returned
      $newEvent = $this->service->insertEvent($event, 'http://www.google.com/calendar/feeds/'.$this->config['calendarId'].'/private/full');
//       debug($newEvent);
      $id = $newEvent->id->text;
      if( preg_match ( '/.*\/(.*)$/' , $id, $matches ) != 0 )
        $id = $matches[1];
      $data['id'] = $id; 
      $model->setInsertID($id);
      $model->id = $id; 
      debug($data['id']);
      return true;
    }
    
/**
 * Implement the R in CRUD. Calls to ``Model::find()`` arrive here.
 */
    public function read(Model $Model, $data = array(), $recursive = NULL) {
//         debug($data);
        /**
         * Here we do the actual count as instructed by our calculate()
         * method above. We could either check the remote source or some
         * other way to get the record count. Here we'll simply return 1 so
         * ``update()`` and ``delete()`` will assume the record exists.
         */
        if ($data['fields'] == 'COUNT') {
            return array(array(array('count' => 1)));
        }
      if($this->service == null)
      {
        return array();
      }
        $eventFeed = array();
        $query = $this->service->newEventQuery();
        
        $query->setUser($this->config['calendarId']);
        $query->setVisibility('public');
//         $query->setProjection('full');

    if( isset($data['conditions']['id']))
    {
//       debug($data['conditions']['id']);          
      $query->setEvent( $data['conditions']['id'] );
      
          try {
            $eventFeed[] = $this->service->getCalendarEventEntry($query);
          }
          catch (Zend_Gdata_App_Exception $e) {
            $this->log($e->getMessage(), 'debug');
          }
      
      
    }
        else
        {
          if( !isset($data['conditions']['start >=']) )
          {
            $data['conditions']['start >='] = time()-1*60*60;
          }
          
          if( !isset($data['conditions']['start <=']) )
          {
            $data['conditions']['start <='] = time()+1*60*60*24*7;
          }
          
          
          $dateStart = new DateTime();
          $dateStart->setTimestamp($data['conditions']['start >=']);
          
          $dateEnd = new DateTime();
          $dateEnd->setTimestamp($data['conditions']['start <=']);
          
          $startDate=$dateStart->format(DateTime::RFC3339);
          $endDate=$dateEnd->format(DateTime::RFC3339);
          
          $query->setStartMin($startDate);
          $query->setStartMax($endDate);
          
          $query->setParam('singleevents','true');
          $query->setProjection('composite');
          
          try { $eventFeed = $this->service->getCalendarEventFeed($query);
          }
          catch (Zend_Gdata_App_Exception $e) {
            $this->log($e->getMessage(), 'debug');
          }
        }
        $res = array();
        $eventsKey = $Model->alias;
//         debug($eventFeed->title->text);
        foreach ($eventFeed as $event) {
          $gevent = array();
//           debug($event);
          
          if( isset($data['conditions']['id']) )
          {
            $gevent[$eventsKey]['id'] = $data['conditions']['id']; 
          }
          else
          {
            if(isset($event->originalEvent))
              $gevent[$eventsKey]['id'] = $event->originalEvent->id;
            else
              $gevent[$eventsKey]['id'] = $event->id;
          }
          

          $gevent[$eventsKey]['title'] = $event->title->text;
//           $gevent[$eventsKey]['originalEvent'] = ;
//           $gevent[$eventsKey]['calendar'] = $this->service->summary;
          foreach ($event->when as $when) {

            $gevent['GeventDate'][] = array('start' => $when->startTime, 'end' => $when->endTime);
          }
          $res[]=$gevent;
        }
        return $res;
    }
}
?>