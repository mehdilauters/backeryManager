<?php
App::uses('AccountManagementAppModel', 'AccountManagement.Model');
/**
 * Account Model
 *
 * @property AccountEntry $AccountEntry
 */
class Account extends AccountManagementAppModel {

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
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
                'company_id' => array(
                        'numeric' => array(
                                'rule' => array('numeric'),
                                'message' => 'company_id must be numeric',
                                //'allowEmpty' => false,
                                //'required' => false,
                                //'last' => false, // Stop validation after this rule
                                //'on' => 'create', // Limit validation to 'create' or 'update' operations
                        ),
                        'notempty' => array(
                                'rule' => array('notempty'),
                                'message' => 'company_id must be set',
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
		'AccountEntry' => array(
			'className' => 'AccountManagement.AccountEntry',
			'foreignKey' => 'account_id',
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

      public $belongsTo = array(
        'Company' => array(
          'className' => 'Company',
          'foreignKey' => 'company_id',
          'conditions' => '',
          'fields' => '',
          'order' => ''
      ),
    );
}
