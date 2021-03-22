<?php
App::uses('AppModel', 'Model');
/**
 * DatabaseVersion Model
 *
 */
class DatabaseVersion extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'database_version';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'version';

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
	);
}
