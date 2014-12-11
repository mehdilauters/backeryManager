<?php
App::uses('AccountManagementAppModel', 'AccountManagement.Model');
/**
 * AccountEntry Model
 *
 * @property Account $Account
 */
class AccountEntry extends AccountManagementAppModel {

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
		'account_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notempty' => array(
                                'rule' => array('notempty'),
                                'message' => 'account_id must be set',
                                //'allowEmpty' => false,
                                //'required' => false,
                                //'last' => false, // Stop validation after this rule
                                //'on' => 'create', // Limit validation to 'create' or 'update' operations
                        ),
		),
		   'date' => array(
                    'datetime' => array(
                      'rule' => array('datetime'),
                      'message' => 'datetime not valid',
                      'allowEmpty' => false,
                      'required' => true,
                      //'last' => false, // Stop validation after this rule
                      //'on' => 'create', // Limit validation to 'create' or 'update' operations
                    ),
                  ),
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
		'value' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required' => true,
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
		'Account' => array(
			'className' => 'AccountManagement.Account',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public function currentTotal($list)
	{
          $current_total = false;
          foreach($list as &$item)
          {
                  if($current_total == false)
                  {
                    $this->contain();
                    $tmp = $this->find('all',
                                              array(
                                              'order' => 'AccountEntry.date, AccountEntry.created',
                                              'conditions' => array('AccountEntry.account_id' => $item['account_id'],
                                                                    'AccountEntry.date <= "'.$item['date'].'"',
                                                                    'AccountEntry.created <= "'.$item['created'].'"',
                                                                    ),
                                              'fields' => array('SUM(AccountEntry.value) as `current_total`', 
                                                                )
                                              ));
                        $current_total = $tmp[0][0]['current_total'];
                    }
                    else
                    {
                      $current_total += $item['value'];
                    }
                    $item['current_total'] = $current_total;
          }
          return $list;
	}
	
	public function afterFind($results, $primary = false)
        {
          $results = parent::afterFind($results, $primary);
          $current_total = false;
          foreach($results as $id => $data)
          {
            if(isset($data[$this->alias]))
            {
                  if($current_total == false)
                  {
                    $this->contain();
                    $tmp = $this->find('all',
                                              array(
                                              'conditions' => array('AccountEntry.account_id' => $data[$this->alias]['account_id'],
                                                                    'AccountEntry.date < "'.$data[$this->alias]['date'].'"',
                                                                    ),
                                              'fields' => array('SUM(AccountEntry.value) as `current_total`', 
                                                                )
                                              ));
                        $current_total = $tmp[0][0]['current_total'];
                    }
                    else
                    {
                      $current_total += $results[$id][$this->alias]['value'];
                    }
              $results[$id][$this->alias]['current_total'] = $current_total;
            }
          }
          return $results;
        }
}
