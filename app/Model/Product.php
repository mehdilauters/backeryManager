<?php
App::uses('AppModel', 'Model');
/**
 * Product Model
 *
 * @property ProductTypes $ProductTypes
 * @property Media $Media
 * @property Event $Event
 */
class Product extends AppModel {

/**
 * Display field
 *
 * @var string
 */
  public $displayField = 'name';
  public $actsAs = array('Containable');
  public $virtualFields = array(
      'produced_today' => 'SELECT sum( produced )  FROM `sales` WHERE product_id = Product.id and date = CURDATE()'
  );

/**
 * Validation rules
 *
 * @var array
 */
  public $validate = array(
    'id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Product::id must be numeric',
        'allowEmpty' => true,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'product_types_id' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Product::product_type_id must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Product::product_type_id must be numeric',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'media_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Product::media_id must be numeric',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'name' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Product::name must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'description' => array(
//       'notempty' => array(
//         'rule' => array('notempty'),
//         'message' => 'Product::description must be notempty',
//         //'allowEmpty' => false,
//         //'required' => false,
//         //'last' => false, // Stop validation after this rule
//         //'on' => 'create', // Limit validation to 'create' or 'update' operations
//       ),
    ),
    'created' => array(
      'datetime' => array(
        'rule' => array('datetime'),
        'message' => 'Product::created must be datetime',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
     'price' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Product::price must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Product::price must be numeric',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
      'unity' => array(
      'boolean' => array(
        'rule' => array('boolean'),
        'message' => 'Product::unity be boolean',
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
    'ProductType' => array(
      'className' => 'ProductType',
      'foreignKey' => 'product_types_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'Media' => array(
      'className' => 'Media',
      'foreignKey' => 'media_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
  );

/**
 * hasMany associations
 *
 * @var array
 */
  public $hasMany = array(
    'Sale' => array(
      'className' => 'Sale',
      'foreignKey' => 'product_id',
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
