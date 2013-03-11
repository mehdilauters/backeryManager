<?php
App::uses('AppModel', 'Model');
class Gcalendar extends AppModel {
  public $useDbConfig = 'calendar';
  public $useTable = false;
  public $displayField = 'title';
  /**
   * Si nous voulons create() ou update(), nous avons besoin de spécifier la
   * disponibilité des champs. Nous utilisons le même tableau indicé comme nous le faisions avec CakeSchema, par exemple
   * fixtures et schema de migrations.
   */
  public $schema = array(
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
          'id' => array(
              'type' => 'string',
              'null' => false,
              'length' => 255,
          )
  
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
  
   function beforeSave($options = array())
  {
    $ret = parent::beforeSave($options);
    
    if (!$this->id && !isset($this->data[$this->alias][$this->primaryKey]))
    { //insert
      $this->log($this->alias.' create not available','debug');
      return false;
    }
    else
    { //update
      
    }
    
    return $ret;
  }
  
  public $hasMany = array(
      'Gevent' => array(
          'className' => 'Gevent',
          'foreignKey' => '',
          'dependent' => '',
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