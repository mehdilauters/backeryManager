<?php
App::uses('AppModel', 'Model');
/**
 * Company Model
 *
 * @property Media $Media
 */
class Company extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'rib' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'address' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'phone' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'capital' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'siret' => array(
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
 * hasOne associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Media' => array(
			'className' => 'Media',
			'foreignKey' => 'rib',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	      'EventType' => array(
		'className' => 'FullCalendar.EventType',
		'foreignKey' => 'event_type_id',
		'conditions' => '',
		'fields' => '',
		'order' => '',
		    'dependent'=> true,
	      ),
	);


        public $hasMany = array(
	'User' => array(
	  'className' => 'User',
	  'foreignKey' => 'company_id',
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



  public function afterFind($results, $primary = false)
    {
      $results = parent::afterFind($results, $primary);
      foreach($results as $id => $shop)
      {
	if(isset($results[$id]['Company']['phone']))
	{
	  if( strlen($results[$id]['Company']['phone']) == 9)
	  {
	    
	    $results[$id]['Company']['phone'] = '0'.$results[$id]['Company']['phone'];
	  }
	}
      }
      return $results;
    }
}
