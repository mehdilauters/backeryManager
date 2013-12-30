<?php
App::uses('AppModel', 'Model');
/**
 * Event Model
 *
 * @property Media $Media
 * @property Product $Product
 */
class Event extends AppModel {

/**
 * Display field
 *
 * @var string
 */
  public $displayField = 'id';

/**
 * Validation rules
 *
 * @var array
 */
  public $validate = array(
    'id' => array(
      'alphanumeric' => array(
        'rule' => array('alphanumeric'),
        'message' => 'Event::id mus be alphanumeric',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Event::id must be notempty',
        //'allowEmpty' => false,
        'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'media_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Event::media_id must be numeric',
        'allowEmpty' => true,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'product_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Event::product_id must be numeric',
        'allowEmpty' => true,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      /*  'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Event::product_id mus be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),*/
    ),
    'gevent_id' => array(
        'notempty' => array(
            'rule' => array('notempty'),
            'message' => 'Event::gevent_id must be set',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
        ),
    ),
    'event_type_id' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Event::event_type_id must be set',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
  );

  //The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
  public $belongsTo = array(
    'Media' => array(
      'className' => 'Media',
      'foreignKey' => 'media_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'Product' => array(
      'className' => 'Product',
      'foreignKey' => 'product_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'EventType' => array(
      'className' => 'EventType',
      'foreignKey' => 'event_type_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),

    'Gevent' => array(
      'className' => 'Gevent',
      'foreignKey' => 'gevent_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
  );
  
  function beforeDelete($options = array())
  {
    $res = parent::beforeDelete($options);
    $this->data = $this->findById($this->id);
    $gevent = new Gevent();
    $gevent->set($this->data);
    if( $res )
    {
      $res = $gevent->delete();
      if(!$res)
      {
        $this->log('could not delete gevent '.$this->data['Event']['gevent_id'], 'debug');
        debug('Gevent not deleted');
        $res = true;
      }
    }
    return $res;
  }
  
  function beforeSave($options = array())
  {
    $ret = parent::beforeSave($options);
    
    if (!$this->id && !isset($this->data[$this->alias][$this->primaryKey]))
    { //insert
      if(isset($this->data['Event']['gevent_id']))
      {
        return $ret;
      }

      $gevent = new GEvent();
      $gevent->create();
      $res = $gevent->save($this->data);
      if($res)
      {
       $this->data['Event']['gevent_id'] = $gevent->id;
      }
      else 
      {
        $this->log('could not insert gevent','debug');
      }
      return $res;
    }
    else
    { //update
      
    }
    
    return $ret;
  }
  
  function beforeValidate($options = array()) {
    $ret = parent::beforeValidate();
    if(isset($this->data['Event']['gevent_id']))
    {
     return $ret; 
    }
    
    App::import('Model', 'Gevent');
    $gevent = new GEvent();
    $gevent->set($this->data);
    $this->data = array_merge($this->data, $gevent->data);
    $ret = $ret && $gevent->validates($options);
    $this->validationErrors = array_merge($this->validationErrors, $gevent->validationErrors);
    return $ret; 
    
    
//     if (!$this->id && !isset($this->data[$this->alias][$this->primaryKey])) {
//       // insert
//       $event = array();
//       $event['Gevent']['title'] = $this->data['Event']['title'];
//       $geventDate = array();
//       $datetime= DateTime::createFromFormat("d/m/Y", $this->data['Event']['start']);
//       if($datetime == false)
//       {
//         $this->invalidateField('start');
//         return false;
//       }
//       else
//       {
//         $geventDate['start'] = $datetime->format(DateTime::RFC3339) ;
//         $geventDate['stop'] = $datetime->format(DateTime::RFC3339) ;
//         $event['GeventDate'][] = $geventDate;
//         App::import('Model', 'Gevent');
//         $gevent = new GEvent();
//         $gevent->create();
//         $res = $gevent->save($event);
//         if(!$res)
//         {
//           debug('could not create gevent');
//           debug($event);
//           return false;
//         }
//         debug($res);
//         $this->data['Event']['gevent_id'] = $res['Gevent']['id'];
//          debug($this->data);
//       }
//     } else {
//       // edit
//     }
//     return true;
  }
  
  
  
  
  function afterFind($results, $primary = false) {
    parent::afterFind($results, $primary);
    
    App::import('Model', 'Gevent');
    foreach($results as $key => $val){
      if( ! empty( $val['Event'] ) )
      {
        if( ! isset($val['EventType']) )
        {
          $this->EventType->recursive = -1;
          
          $eventTypeData = $this->EventType->findById($val['Event']['event_type_id']);
          $results[$key]['EventType'] = $eventTypeData['EventType'];
        }
        
        $gevent = new GEvent();
        $gevent = $gevent->find('first',
                                array(
                                  'conditions'=>array(
                                    'id' => $results[$key]['Event']['gevent_id'],
                                    'calendar_id' => $results[$key]['EventType']['calendar_id']
                                                                                 )
                                )
                               );
        //       debug($gevent);
        if( count($gevent) != 0 )
        {
          $results[$key]['Gevent'] = $gevent['Gevent'];
        }
        else
        {
          debug('Gevent not found');
        }
      }
      
//        debug($val);
      
    }
    //debug($results);
    return $results;
  }
  
}
