<?php
App::uses('AppModel', 'Model');
class Gevent extends AppModel {
  public $useDbConfig = 'calendar';
  public $displayField = 'title';
  
  /**
   * Si nous voulons create() ou update(), nous avons besoin de spécifier la
   * disponibilité des champs. Nous utilisons le même tableau indicé comme nous le faisions avec CakeSchema, par exemple
   * fixtures et schema de migrations.
   */
  public $schema = array(
    //'Gevent' => array(
          'title' => array(
              'type' => 'string',
              'null' => false,
              'length' => 255,
          ),
          'description' => array(
              'type' => 'string',
              'null' => false,
              'length' => 255,
          ),
          'place' => array(
              'type' => 'string',
              'null' => true,
              'length' => 255,
          ),
          'id' => array(
              'type' => 'string',
              'null' => false,
              'length' => 255,
          )
      //  )
  
  );
  
  
  public $validate = array(
      'id' => array(
          'notempty' => array(
              'rule' => array('notempty'),
              'message' => 'Gevent::id must be notempty',
              //'allowEmpty' => false,
              //'required' => false,
              //'last' => false, // Stop validation after this rule
              //'on' => 'create', // Limit validation to 'create' or 'update' operations
          ),
      ),
      'title' => array(
          'notempty' => array(
              'rule' => array('notempty'),
              'message' => 'Gevent::title must be notempty',
              //'allowEmpty' => false,
              //'required' => false,
              //'last' => false, // Stop validation after this rule
              //'on' => 'create', // Limit validation to 'create' or 'update' operations
          ),
        ),
      'description' => array(
          'notempty' => array(
              'rule' => array('notempty'),
              'message' => 'Gevent::description must be notempty',
              //'allowEmpty' => false,
              //'required' => false,
              //'last' => false, // Stop validation after this rule
              //'on' => 'create', // Limit validation to 'create' or 'update' operations
          ),
      ),
  );

  
  public function schema($field = false)
  {
    return $this->userDefinedSchema($field);
  }
  
  function beforeValidate($options = array()) {
    $ret = parent::beforeValidate();
     
    App::import('Model', 'GeventDate');
    $geventdate = new GeventDate();
    $geventdate->set($this->data);
    $ret = $ret && $geventdate->validates($options);
    $this->data = array_merge($this->data, $geventdate->data);
    $this->validationErrors = array_merge($this->validationErrors, $geventdate->validationErrors);
    return $ret ;
  }
  /*  public function beforeFind($options = array())
  {
    debug($options['conditions']);
    $res = parent::beforeFind($options);
    if( ! isset( $this->data['Gcalendar'] ) )
    {
      App::import('Model', 'EventType');
      $eventType = new EventType();
      $eventTypeData = $eventType->findById($options['conditions']['calendar_id']);
      if( ! isset( $eventTypeData['EventType'] ) )
      {
        $this->log('could not find eventType['.$this->data['Event']['event_type_id'].']', 'debug');
        return false;
      }
      $this->data['EventType'] = $eventTypeData['EventType'];
    }
    return $res;
  }*/
  
  public function beforeSave($options = array())
  {
    $res = parent::beforeSave($options);
    if( ! isset( $this->data['Gcalendar'] ) )
    {
      App::import('Model', 'EventType');
      $eventType = new EventType();
      $eventTypeData = $eventType->findById($this->data['Event']['event_type_id']);
      if( ! isset( $eventTypeData['EventType'] ) )
      {
        $this->log('could not find eventType['.$this->data['Event']['event_type_id'].']', 'debug');
        return false;
      }
      $this->data['EventType'] = $eventTypeData['EventType'];
    }
    return $res;
  }
  
  public function save($data = null, $validate = true, $fieldList = array())
  {
  	$res = parent::save($data , $validate , $fieldList);
    return $res;
  }
  
  public $hasMany = array(
      'GeventDate' => array(
          'className' => 'GeventDate',
          'foreignKey' => '',
          'dependent' => false,
          'conditions' => '',
          'fields' => '',
          'order' => '',
          'limit' => '',
          'offset' => '',
          'exclusive' => '',
          'finderQuery' => '',
          'counterQuery' => ''
      )
      );
}
?>