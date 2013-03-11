<?php
App::uses('AppModel', 'Model');
App::import('Model','Media'); 
/**
 * Video Model
 *
 */
class Video extends Media{

  public $actsAs = array( 'Inherit' ); 
/**
 * Display field
 *
 * @var string
 */
  public $displayField = 'id';

/**
 * Validation rules
 *
 * @var array
 */
  public $validate = array(
    'id' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        //'message' => 'Your custom message here',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
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
  
    /**
 * belongsTo associations
 *
 * @var array
 */
  public $belongsTo = array(
    'Media' => array(
      'className' => 'Media',
      'foreignKey' => 'id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    )
  );
}
