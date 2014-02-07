<?php

set_include_path(get_include_path() . PATH_SEPARATOR . APP.'Vendor');
require_once 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_Calendar');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');

  class GoogleCalendarSource extends DataSource 
  {
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
    
    private $m_isReady = false;
    

 

/**
 * Créons notre HttpSocket et gérons any config tweaks.
 */
    public function __construct($config) {
        parent::__construct($config);
        $this->service = null;
      $this->connect();

    }
  
    public function name()
    {
      return $this->name;
    }
    
  public function connect()
  {
   // Create an authenticated HTTP client
        $service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
        try {
          $client = Zend_Gdata_ClientLogin::getHttpClient($this->config['login'], $this->config['password'], $service);
          $this->service = new Zend_Gdata_Calendar($client);          
	  $this->m_isReady = true;
        } catch (Exception $e) {
	  $this->m_isReady = false;
          $this->log($e->getMessage(), 'debug');
        } 
  }

public function isReady()
{
    return $this->m_isReady;
}

/**
 * Since datasources normally connect to a database there are a few things
 * we must change to get them to work without a database.
 */

/**
 * listSources() isUndo for caching. You'll likely want to implement caching in
 * your own way with a custom datasource. So just ``return null``.
 */
     public function listSources($data = null) {
       //parent::listSources($data);
       // return array('gevents');
       return null;
     }

/**
 * describe() tells the model your schema for ``Model::save()``.
 *
 * You may want a different schema for each model but still use a single
 * datasource. If this is your case then set a ``schema`` property on your
 * models and simply return ``$Model->schema`` here instead.
 */
       public function describe($model) {
         debug($model);
         return $model->_schema;
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
     $query = $this->service->newEventQuery();
     $query->setUser($Model->data['EventType']['calendar_id']);
     $query->setVisibility('private');
     
     $query->setEvent( $conditions[$Model->alias.'.id']);
     debug($conditions[$Model->alias.'.id']);
     try {
       $event = $this->service->getCalendarEventEntry($query);
       $event->delete();
       return true;
     }
     catch (Zend_Gdata_App_Exception $e) {
       debug($e);
       $this->log($e->getMessage(), 'debug');
     }
     
     return false;
    }
    
    public function update(Model $Model, $fields = null, $values = null, $conditions = null) {
      debug('update not implemented');
      return false;
//       return $this->create($Model, $fields, $values);
    }
    
    public function create(Model $model, $fields = array(), $values = array())
    {
      debug('creaaate');
      if($this->service == null)
      {
        $this->log($this->name.'::create => not available', 'debug');
        return false;
      }
      $calendarId = $model->data['EventType']['calendar_id'];
//       $res = parent::create($model,$fields, $values);
//       debug('');
//       debug($res);

      $data = $model->data;
      $event= $this->service->newEventEntry();
      $event->title = $this->service->newTitle($data[$model->alias]['title']);
      if(isset($data[$model->alias]['place']))
      {
	$event->where = array($this->service->newWhere($data[$model->alias]['place']));
      }
      $event->content = $this->service->newContent($data[$model->alias]['description']);


      // Set the date using RFC 3339 format.
      $when = $this->service->newWhen();
      $when->startTime = $data['GeventDate']['start'];
      if( isset($data['GeventDate']['stop']) )
      {
        $when->endTime = $data['GeventDate']['stop'];
      }
      $event->when = array($when);
      
      // Upload the event to the calendar server
      // A copy of the event as it is recorded on the server is returned
      $newEvent = $this->service->insertEvent($event, 'http://www.google.com/calendar/feeds/'.$calendarId.'/private/full');
//       debug($newEvent);
      $id = $newEvent->id->text;
      if( preg_match ( '/.*\/(.*)$/' , $id, $matches ) != 0 )
        $id = $matches[1];
      else
        $this->log('Could not extract event ID','debug');
      $data['id'] = $id; 
      $model->setInsertID($id);
      $model->id = $id; 
      // debug($data['id']);
      return true;
    }
  /*
   public function query($method, $params, $Model) {
     //     debug($method);
     //   debug($params);
     //debug($Model);
     
     if( strpos($method, 'findBy') === 0 || strpos($method, 'findAllBy') === 0)
     {
        if (substr($method, 0, 6) === 'findBy')
        {
          $all = false;
          $field = Inflector::underscore(substr($method, 6));
        }
       else 
       {
          $all = true;
          $field = Inflector::underscore(substr($method, 9));
      }
       $options = array();
       $options['conditions'] = array(
            $field => $params[0]
          );
       if($all)
       {
         return $Model->find('all', $options);
       }
       else
       {
         return $Model->find('first', $options);
       }
       
     }
   }
  */
  public function getId($uri)
  {
   $id = $uri;
    //http://www.google.com/calendar/feeds/default/aajqdzeaqsfdzedezkf6ch2e4jcupadzel631c%40group.calendar.google.com
    if( preg_match ( '/.*\/(.*)$/' , $uri, $matches ) != 0 )
      $id = $matches[1];
    else
      $this->log('Could not extract calendar ID','debug');
   return $id; 
  }
  
    public function readCalendars(Model $Model, $data = array(), $recursive = NULL) {
      //  debug('readCalendars');
      // debug($data);
      if ($data['fields'] == 'COUNT') {
            return array(array(array('count' => 1)));
        }

      try {
        $calendars = $this->service->getCalendarListFeed();
      } catch (Zend_Gdata_App_Exception $e) {
        $this->log($e,'debug');
      }
      $calendarsData = array();
      foreach ($calendars as $calendar) {
        $dataCal = array();
        $dataCal['Gcalendar']['title'] = $calendar->title->text;
        $dataCal['Gcalendar']['description'] = '';
        if( $calendar->summary != null )
        {
            $dataCal['Gcalendar']['description'] = $calendar->summary->text;
        }


        $dataCal['Gcalendar']['id'] = $this->getId( $calendar->id->text );
        //debug('calendarId=>'.$id);
         $calendarsData[] = $dataCal;
        
        // if search by ID
         if( isset($data['conditions']['id']) && $data['conditions']['id'] != $dataCal['Gcalendar']['id'] )
         {
           continue;
         }
     
      }
      // debug($calendarsData);
      return $calendarsData;
    }
  
  
  
/**
 * Implement the R in CRUD. Calls to ``Model::find()`` arrive here.
 */
    public function read(Model $Model, $data = array(), $recursive = NULL) {
//             debug('reaaad '.$Model->alias);
        /**
         * Here we do the actual count as instructed by our calculate()
         * method above. We could either check the remote source or some
         * other way to get the record count. Here we'll simply return 1 so
         * ``update()`` and ``delete()`` will assume the record exists.
         */

      if($this->service == null)
      {
        return array();
      }
      
      if( $Model->alias == 'Gcalendar' )
      {
        return $this->readCalendars($Model, $data, $recursive);
      }
      
      if ($data['fields'] == 'COUNT') {
            return array(array(array('count' => 1)));
        }
      
         $calendarId = $data['conditions']['calendar_id'];
        $eventFeed = array();
        $query = $this->service->newEventQuery();
        
      // debug($Model->alias);
      $query->setUser($calendarId);
        $query->setVisibility('public');
//         $query->setProjection('full');
	$day = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
        if( !isset($data['conditions']['start >=']) )
        {
          $data['conditions']['start >='] = $day-1*60*60*24;
        }
        
        if( !isset($data['conditions']['start <=']) )
        {
          $data['conditions']['start <='] = $day+1*60*60*24*7;
        }
      
      //TODO cache management
      $cacheFolder = CACHE.'gcalendar/';
      $res = $this->requestAction(
        array('controller' => 'config', 'action' => 'deleteGcalCache')
      );

	$cacheFileName = $cacheFolder.$data['conditions']['start >='].'_'.md5(serialize($data)).'.gcal.tmp';
	
	
	if(!file_exists($cacheFileName))
	{
	  $dateStart = new DateTime();
	  $dateStart->setTimestamp($data['conditions']['start >=']);
	  
	  $dateEnd = new DateTime();
	  $dateEnd->setTimestamp($data['conditions']['start <=']);
	  
	  $startDate=$dateStart->format(DateTime::RFC3339);
	  $endDate=$dateEnd->format(DateTime::RFC3339);
	  
	  $query->setStartMin($startDate);
	  $query->setStartMax($endDate);
			//debug($data);  
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
	  
	  
	    $query->setParam('singleevents','true');
	    $query->setProjection('composite');
	    
	    try { 
			$eventFeed = $this->service->getCalendarEventFeed($query);
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
	    
	    if( isset($data['conditions']['id']) )
	    {
	      $gevent[$eventsKey]['id'] = $data['conditions']['id']; 
	    }
	    else
	    {
	      if(isset($event->originalEvent))
		$gevent[$eventsKey]['id'] =  $this->getId( $event->originalEvent->id );
	      else
		$gevent[$eventsKey]['id'] = $this->getId( $event->id );
	    }
	    

	    $gevent[$eventsKey]['title'] = $event->title->text;
	    $gevent[$eventsKey]['description'] = $event->content->text;
  //           $gevent[$eventsKey]['originalEvent'] = ;
	    //$gevent[$eventsKey]['calendar'] = $this->service->summary;
  //           debug($event->when);
	    foreach ($event->when as $when) {
	      $dateStart = new DateTime($when->startTime);
	      $dateEnd = new DateTime($when->endTime);
	      $gevent['Gevent']['GeventDate'][] = array('start' => $dateStart->format('Y-m-d H:i:s'), 'end' => $dateEnd->format('Y-m-d H:i:s'));
	    }
	    $res[]=$gevent;
  //           debug($gevent);
	  }
	$handle = fopen($cacheFileName,"w");
	fwrite($handle,'<?php $res = '.var_export($res,true).';');
	fclose($handle);
      }
      else
      {
		include($cacheFileName);
      }
        

        return $res;
    }
    
    public function queryAssociation(Model $model, &$linkModel, $type, $association, $assocData, &$queryData, $external, &$resultSet, $recursive, $stack)
    {
//       debug($model->alias.'=====>'.$linkModel->alias.' ( '.$type.' )');
      $data = array();
      if($type == 'belongsTo')
      {
        //debug($resultSet);
        //debug($assocData);
        foreach ($resultSet as $id=>$result)
        {
		// debug($result);
          if($linkModel->alias == 'Gcalendar')
          {
            if( isset( $result[$model->alias] ))
            {
               $data = $linkModel->find('first', array(
                 'conditions' => array($linkModel->primaryKey => $result[$model->alias][$assocData['foreignKey']] )
                 ));
            }
          }
          else
          {
            
            $data = $linkModel->find('first', array(
                'conditions' => array(
                      $linkModel->primaryKey => $result[$model->alias][$assocData['foreignKey']],
                      'calendar_id' => $result['EventType']['calendar_id'] )
            ));
//             debug($data);
          }
          if( isset( $data[$linkModel->alias] ) )
          {
            $resultSet[$id][$linkModel->alias] = $data[$linkModel->alias];
          }
        }
//         debug($resultSet);
// debug($data[$linkModel->alias]);
      }
      else
      {
       debug($type.' not known');
      }
      
      return array('claaaaudyyy');
        
    }
}
?>