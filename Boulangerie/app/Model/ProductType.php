<?php
App::uses('AppModel', 'Model');
/**
 * ProductsType Model
 *
 * @property Media $Media
 * @property etyetyhet $etyetyhet
 */
class ProductType extends AppModel {
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
        'message' => 'ProductType::id must be numeric',
        'allowEmpty' => true,
        //'required' => true,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'media_id' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'ProductType::media_id must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'ProductType::media_id must be numeric',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'name' => array(
      'alphanumeric' => array(
        'rule' => array('alphanumeric'),
        'message' => 'ProductType::name must be alphanumeric',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'ProductType::name must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'description' => array(
//       'notempty' => array(
//         'rule' => array('notempty'),
//         'message' => 'ProductType::description must be notempty',
//         //'allowEmpty' => false,
//         //'required' => false,
//         //'last' => false, // Stop validation after this rule
//         //'on' => 'create', // Limit validation to 'create' or 'update' operations
//       ),
    ),
    'created' => array(
      'datetime' => array(
        'rule' => array('datetime'),
        'message' => 'ProductType::created must be datetime',
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
  );
  
  /**
 * hasMany associations
 *
 * @var array
  */
  public $hasMany = array(
    'Product' => array(
      'className' => 'Product',
      'foreignKey' => 'product_types_id',
      'dependent' => false,
      'conditions' => '',
      'fields' => '',
      'order' => '',
      'limit' => '',
      'offset' => '',
      'exclusive' => '',
      'finderQuery' => '',
      'counterQuery' => ''
    ),
  );
}
