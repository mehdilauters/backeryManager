<?php
App::uses('AppModel', 'Model');
/**
 * Email Model
 *
 * @property Company $Company
 */
class Email extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'company_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('alphanumeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'title' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	
	
        public function afterSave($created, $options = array())
        {
          if($created)
          {
            $maildir = Configure::read('Settings.Emails.path').$this->data['Email']['company_id'];
            mkdir($maildir);
            $mailbox = $this->data['Email']['company_id'].'/'.$this->data['Email']['email'];
            $fullPath = $maildir.$mailbox;
            $command = 'maildirmake '.$fullPath;
            system($command);
            if(!is_dir($fullPath))
            {
              debug("could not create email directory");
              debug($command);
              return false;
            }
          }
          else
          {
            debug("Warning: email rename not implemented");
          }
        }
}
