<?php
App::uses('AppModel', 'Model');
/**
 * EventType Model
 *
 * @property Event $Event
 */
class EventType extends AppModel {

/**
 * Display field
 *
 * @var string
 */
  public $displayField = 'name';
  public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
  public $validate = array(
    'id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'name' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
     ),
      'calendar_id' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
     ),
  );

  //The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
  public $hasMany = array(
    'Event' => array(
      'className' => 'Event',
      'foreignKey' => 'event_type_id',
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
  
    public $belongsTo = array(
    'Gcalendar' => array(
      'className' => '',
      'foreignKey' => 'calendar_id',
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
  
  
    function beforeValidate($options = array()) {
    $ret = parent::beforeValidate();
    
    App::import('Model', 'Gcalendar');
    $gcal = new Gcalendar();
    $gcal->set($this->data);
    $ret = $ret && $gcal->validates($options);
    $this->validationErrors = array_merge($this->validationErrors, $gcal->validationErrors);
    return $ret; 
    }

}
