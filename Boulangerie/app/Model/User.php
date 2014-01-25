<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Media $Media
 * @property Media $Media
 */
class User extends AppModel {

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
    'id' => array(
//       'notempty' => array(
//         'rule' => array('notempty'),
//         'message' => 'User::id must be notempty',
//         //'allowEmpty' => false,
//         //'required' => false,
//         //'last' => false, // Stop validation after this rule
//         //'on' => 'create', // Limit validation to 'create' or 'update' operations
//       ),
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'User::id must be numeric',
        'allowEmpty' => true,
        'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'media_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'User::media_id must be numeric',
        'allowEmpty' => true,
        'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'email' => array(
      'email' => array(
        'rule' => array('email'),
        'message' => 'User::email must be email',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'User::email must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'password' => array(
      'alphanumeric' => array(
        'rule' => array('alphanumeric'),
        'message' => 'User::password must be alphanumeric',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'User::password must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'name' => array(
      'alphanumeric' => array(
        'rule' => array('alphanumeric'),
        'message' => 'User::name must be alphanumeric',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'User::name must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'created' => array(
      'datetime' => array(
        'rule' => array('datetime'),
        'message' => 'User::created must be datetime',
        'allowEmpty' => true,
        'required' => false,
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
    )
  );

/**
 * hasMany associations
 *
 * @var array
 */
  public $hasMany = array(
    'Media' => array(
      'className' => 'Media',
      'foreignKey' => 'user_id',
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

   public function beforeSave($options = array()) {
        if (!$this->id) {
			$count = $this->find('count');
			debug($count);
			if($count == 0)
			{
					$this->data[$this->alias]['isRoot'] = true;
			}
            //$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }
  
}
