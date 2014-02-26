<?php
/*
 * Model/Event.php
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */
 
class Event extends FullCalendarAppModel {
	var $name = 'Event';
	var $displayField = 'title';
	var $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'start' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
			'dateRespect' => array(
				'rule' => array('dateRespect'),
				'message' => 'Start must be before end'
			),
// 		'datetime' => array(
// 			'rule' => array('datetime'),
// 			'message' => 'invalid date',
// 			'allowEmpty' => false,
// 			'required' => false,
// 			//'last' => false, // Stop validation after this rule
// 			//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 		      ),
		),
		 'end' => array(
		    'notempty' => array(
			    'rule' => array('notempty'),
		    ),
// 		    'datetime' => array(
// 			'rule' => array('datetime'),
// 			'message' => 'invalid date',
// 			'allowEmpty' => false,
// 			'required' => false,
// 			//'last' => false, // Stop validation after this rule
// 			//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 		      ),
		),
		'recursive' => array(
			'inList' => array(
				'rule' => array('inList', array('','day', 'week', 'month', 'day')),
			),
		),
		'recursive_start' => array(
			'dateRespect' => array(
				'rule' => array('dateRespect'),
				'message' => 'Start must be before end'
			),
// 		  'datetime' => array(
// 			'rule' => array('datetime'),
// 			'message' => 'invalid date',
// 			'allowEmpty' => true,
// 			'required' => false,
// 			//'last' => false, // Stop validation after this rule
// 			//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 		      ),
		),
		 'recursive_end' => array(
// 		    'datetime' => array(
// 			'rule' => array('datetime'),
// 			'message' => 'invalid date',
// 			'allowEmpty' => true,
// 			'required' => false,
// 			//'last' => false, // Stop validation after this rule
// 			//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 		      ),
		),
	);

	public $belongsTo = array(
		'EventType' => array(
			'className' => 'FullCalendar.EventType',
			'foreignKey' => 'event_type_id'
		)
	);

      public function dateRespect()
      {
	$start = $this->data['Event']['start'];
	$end = $this->data['Event']['end'];
	return ($start < $end);
      }

      public function beforeSave($options = array())
      {
	if( $this->data['Event']['recursive'] == '' )
	{
	  if(isset($this->data['Event']['recursive_start']))
	  {
	    $this->data['Event']['recursive_start'] = '';
	    $this->data['Event']['recursive_start'] = '';
	  }
	}
      }

	
//       public function beforeFind($queryData){
// 	echo 'hooooooooooooooooooo';
//         debug($queryData);
//         return false;//$queryData;
// 	}
// 	
// 	public function afterFind($results, $primary = false)
// 	{
// 		debug($results);
// 	}

}
?>
