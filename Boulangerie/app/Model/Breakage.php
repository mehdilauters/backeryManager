<?php
App::uses('AppModel', 'Model');
/**
 * Breakage Model
 *
 * @property Shop $Shop
 * @property ProductTypes $ProductTypes
 */
class Breakage extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'breakage';
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
		'date' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'shop_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'product_types_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'breakage' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Shop' => array(
			'className' => 'Shop',
			'foreignKey' => 'shop_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ProductTypes' => array(
			'className' => 'ProductTypes',
			'foreignKey' => 'product_types_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
/*
      public $hasMany = array(
	    'Sale' => array(
	      'className' => 'Sale',
	      'foreignKey' => '',
	      'dependent' => false,
//	      'conditions' => 'Sales.date = Breakage.date AND Sales.product_types_id = Breakage.product_types_id AND Sales.shop_id = 				Breakage.shop_id',
	      'fields' => '',
	      'order' => '',
	      'limit' => '',
	      'offset' => '',
	      'exclusive' => '',
	      'finderQuery' => '',
	      'counterQuery' => ''
	    ),
	);
*/
}
