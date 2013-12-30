<?php
App::uses('AppModel', 'Model');
/**
 * Shop Model
 *
 * @property Media $Media
 */
class Shop extends AppModel {

/**
 * Display field
 *
 * @var string
 */
  public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
  public $validate = array(
    'id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Shop::id must be numeric',
        'allowEmpty' => true,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'media_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Shop::media_id must be numeric',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'name' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Shop::name must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'description' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Shop::description must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'address' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Shop::address must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'phone' => array(
      'notempty' => array(
        'rule' => array('numeric'),
        'message' => 'Shop::phone must be numeric',
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
    'EventType' => array(
      'className' => 'EventType',
      'foreignKey' => 'event_type_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
  );

  public function afterFind($results, $primary = false)
  {
    foreach($results as $id => $shop)
    {
      if( strlen($results[$id]['Shop']['phone']) == 9)
      {
	
	$results[$id]['Shop']['phone'] = '0'.$results[$id]['Shop']['phone'];
      }
    }
    return $results;
  }
}