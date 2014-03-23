<?php
App::uses('AppModel', 'Model');
/**
 * Media Model
 *
 * @property User $User
 * @property Event $Event
 * @property Product $Product
 * @property ProductsType $ProductsType
 * @property User $User
 */
class Media extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
  public $useTable = 'medias';

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
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Media::id must be numeric',
        'allowEmpty' => true,
        'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Media::id must be notempty',
        //'allowEmpty' => false,
        'required' => false,
        //'last' => false, // Stop validation after this rule
        'on' => 'update', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'user_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Media::user_id must be numeric',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Media::name must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    'description' => array(
//       'notempty' => array(
//         'rule' => array('notempty'),
//         'message' => 'Media::description must be notempty',
//         //'allowEmpty' => false,
//         //'required' => false,
//         //'last' => false, // Stop validation after this rule
//         //'on' => 'create', // Limit validation to 'create' or 'update' operations
//       ),
    ),
    'path' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Media::path must be notempty',
        //'allowEmpty' => false,
        //'required' => false,
        //'last' => false, // Stop validation after this rule
        //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
    ),
    'created' => array(
      'datetime' => array(
        'rule' => array('datetime'),
        'message' => 'Media::created must be datetime',
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
    'User' => array(
      'className' => 'User',
      'foreignKey' => 'user_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'Photo' => array(
      'className' => 'Photo',
      'foreignKey' => 'id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
  );

/**
 * hasMany associations
 *
 * @var array
 */
  public $hasMany = array(
    'Product' => array(
      'className' => 'Product',
      'foreignKey' => 'media_id',
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
    'ProductType' => array(
      'className' => 'ProductType',
      'foreignKey' => 'media_id',
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
    'User' => array(
      'className' => 'User',
      'foreignKey' => 'media_id',
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
  
  public function afterFind($results, $primary = false)
  {
    foreach($results as $id => $data)
     {
       if( isset( $data['Media'] ) && count($data['Media']) != 0  ) // not 'count'
       {
       App::import('Model', 'Photo');
       $photo = new Photo();
       
       $photoData = $photo->find('first', array(
         
         'conditions' => array('Photo.id'=>$data['Media']['id']),
       
         
         'callbacks' => false
       ));
       if(isset($photoData['Photo']))
       {
         $results[$id]['Media']['Photo'] = $photoData['Photo'];
       }   
       }
       
     }
    return $results;
  }

}
