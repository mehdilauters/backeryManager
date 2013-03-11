<?php
App::uses('AppModel', 'Model');
class GeventDate extends AppModel {
  public $useDbConfig = 'calendar';
  public $useTable = false;
  
  /**
   * Si nous voulons create() ou update(), nous avons besoin de spécifier la
   * disponibilité des champs. Nous utilisons le même tableau indicé comme nous le faisions avec CakeSchema, par exemple
   * fixtures et schema de migrations.
   */
  public $schema = array(
          'start' => array(
              'type' => 'datetime',
              'null' => false,
              'length' => 255,
          ),
          'stop' => array(
              'type' => 'datetime',
              'null' => false,
              'length' => 255,
          )
  
  );
  
  
  public $validate = array(
      'start' => array(
          'datetime' => array(
              'rule' => array('datetime'),
              'message' => 'GeventDate::start must be datetime',
//               'allowEmpty' => false,
              'required' => true,
              //'last' => false, // Stop validation after this rule
              //'on' => 'create', // Limit validation to 'create' or 'update' operations
          ),
      ),
      'stop' => array(
          'datetime' => array(
              'rule' => array('datetime'),
              'message' => 'GeventDate::stop must be datetime',
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
  
  function afterValidate($options = array()) {
    $ret = parent::beforeSave();
    if(! isset( $this->data[$this->alias]['stop'] ) )
    {
      $stop = '';
      if( preg_match ( '/(.*) .*$/' , $this->data[$this->alias]['start'], $matches ) != 0 )
        $stop = $matches[1];
      else
        $this->log('Could not set as a all day event','debug');
      $this->data[$this->alias]['start'] = $stop;
    }
    return $ret;
  }
  
  public function save($data = null, $validate = true, $fieldList = array())
  {
    return parent::save($data , $validate , $fieldList);
  }
  
  public $belongsTo = array(
      'GeventDate' => array(
          'className' => 'Gevent',
          'foreignKey' => '',
          'dependent' => true,
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