<?php
App::uses('AppModel', 'Model');
class GeventDate extends AppModel {
	public $useDbConfig = 'calendar';
	public $useTable = false;
	public $_schema = array(
					'start' => array(
							'notempty' => array(
									'rule' => array('notempty'),
									'message' => 'The begining date should not be empty',
									'allowEmpty' => false,
									'required' => true,
									//'last' => false, // Stop validation after this rule
									//'on' => 'create', // Limit validation to 'create' or 'update' operations
							),
							'datetime' => array(
									'rule' => array('datetime'),
									'message' => 'La date de début n\'est pas au bon format',
									'allowEmpty' => false,
									'required' => true,
									//'last' => false, // Stop validation after this rule
									//'on' => 'create', // Limit validation to 'create' or 'update' operations
							),
					),
	
					'end' => array(
							'notempty' => array(
									'rule' => array('notempty'),
									'message' => 'The begining date should not be empty',
									'allowEmpty' => false,
									'required' => true,
									//'last' => false, // Stop validation after this rule
									//'on' => 'create', // Limit validation to 'create' or 'update' operations
							),
							'datetime' => array(
									'rule' => array('datetime'),
									'message' => 'La date de fin n\'est pas au bon format',
									'allowEmpty' => false,
									'required' => true,
									//'last' => false, // Stop validation after this rule
									//'on' => 'create', // Limit validation to 'create' or 'update' operations
							),
	
					),

	
	);
}
?>