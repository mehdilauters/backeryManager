<?php
App::uses('AppModel', 'Model');
/**
 * Result Model
 *
 * @property Shop $Shop
 */
class Result extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'date';
	public $actsAs = array('Containable');
  public $virtualFields = array(
    'total' => 'Result.cash + Result.check'
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
				'message' => 'id must be numeric',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'shop_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'shop_id must be numeric',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'shop_id must be set',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'date' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				'message' => 'date must be a datetime',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Date must be set',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
// 		'cash' => array(
// 			'numeric' => array(
// 				'rule' => array('numeric'),
// 				//'message' => 'Your custom message here',
// 				//'allowEmpty' => false,
// 				//'required' => false,
// 				//'last' => false, // Stop validation after this rule
// 				//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 			),
// 			'notempty' => array(
// 				'rule' => array('notempty'),
// 				//'message' => 'Your custom message here',
// 				'allowEmpty' => false,
// 				//'required' => false,
// 				//'last' => false, // Stop validation after this rule
// 				//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 			),
// 		),
// 		'check' => array(
// 			'numeric' => array(
// 				'rule' => array('numeric'),
// 				//'message' => 'Your custom message here',
// 				//'allowEmpty' => false,
// 				//'required' => false,
// 				//'last' => false, // Stop validation after this rule
// 				//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 			),
// 			'notempty' => array(
// 				'rule' => array('notempty'),
// 				//'message' => 'Your custom message here',
// 				'allowEmpty' => false,
// 				//'required' => false,
// 				//'last' => false, // Stop validation after this rule
// 				//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 			),
// 		),
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
		)
	);

  public $hasMany = array(
      'ResultsEntry' => array(
	'className' => 'ResultsEntry',
	'foreignKey' => 'result_id',
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
