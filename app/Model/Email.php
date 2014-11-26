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

	public function getMailboxPath($data = NULL)
	{
            if($data == NULL)
            {
              $data = $this->data;
            }
            $maildir = Configure::read('Settings.Emails.path').$data[$this->alias]['company_id'].'/'.$data[$this->alias]['email'];;
            return $maildir;
	}
	
        public function beforeSave($options = array())
        {
            App::uses('Folder', 'Utility');

            $maildir = Configure::read('Settings.Emails.path').$this->data[$this->alias]['company_id'];
            if(!is_dir($maildir))
            {
              $f = new Folder();
              if(!$f->create(Configure::read('Settings.Emails.path').$this->data[$this->alias]['company_id']))
              {
                debug("could not create root email directory");
                return false;
              }
            }
            
            $tmp = $this->findById($this->id);
            if(count($tmp) == 0 )
            { // create
              $command = Configure::read('Settings.Emails.maildirmake').' '.$this->getMailboxPath();
              system($command);
              if(!is_dir($this->getMailboxPath()))
              {
                debug("could not create email directory");
                debug($command);
                return false;
              }
            }
            else
            {
            debug($tmp);
            debug($this->data);
              if( $this->getMailboxPath($tmp) != $this->getMailboxPath($this->data))
              { // move
                $from = $this->getMailboxPath($tmp);
                $to = $this->getMailboxPath($this->data);
                debug('move from '.$from.' to '.$to);
                $folder = new Folder($from);
                return $folder->move($to);
              }
            }
        }
        
        public function afterFind($results, $primary = false)
        {
          $results = parent::afterFind($results, $primary);
          foreach($results as $id => $data)
          {
            if(isset($data[$this->alias]))
            {
              $results[$id][$this->alias]['mailbox_exists'] = is_dir($this->getMailboxPath($data));
            }
          }
          return $results;
        }
        
        public function beforeDelete($cascade = true) {
          $data = $this->findById($this->id);
          debug($this->getMailboxPath($data));
//           return false;
          App::uses('Folder', 'Utility');
          $folder = new Folder($this->getMailboxPath($data));
          return $folder->delete();
        }
}
