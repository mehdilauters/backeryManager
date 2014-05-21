<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	var $uses=array('User', 'EventType');
	public $helpers = array('Text','Rss', 'Html', 'MyHtml');
	  var $publicActions = array('exportExcel', 'backupDb');
	  var $memberActions = array();

  public $components = array(
 			  'DebugKit.Toolbar',
			   'Session', 'Cookie',
			    // 'Security', // getNews requestAction redirection
			    'Auth' => array(
				'authenticate' => array(
				      'Form' => array(
					  'fields' => array('username' => 'email'),
					  'passwordHasher' => array(
					      'className' => 'Simple',
					      'hashType' => 'sha1'
					      )
					    )
				  ),
				  'loginRedirect' => '/',
				  'logoutRedirect' => '/',
				   'authorize' => array('Controller'),
				),
			  'RequestHandler',
			  'Functions'
			  );
  public $menu = array('Menu' => 
			array( 
				'Magasins' => 
				  array( 'url' => 'WEBROOT/', 'active' => false ),
				 'Categories' => 
				    array( 'url' => 'WEBROOT/typesProduits', 'active' => false ),
				 'Produits' => 
				    array( 'url' => 'WEBROOT/produits', 'active' => false ),
// 				 'Contact' => 
// 				    array( 'url' => 'WEBROOT/users/add', 'active' => false ),
		    )
			);
  
  
  /**
    * Returns true if the action was called with requestAction()
    *
    * @return boolean
    * @access public
    */
    public function isRequestedAction() {
      return array_key_exists('requested', $this->params);
    }
  

  public function backupDb()
  {
    App::uses('CakeTime', 'Utility');
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');

    $backupExists = false;
    $dirPath = Configure::read('dbBackupPath');
    $dir = new Folder($dirPath);
    $files = $dir->find('.*backup.*\.sql', true);
    foreach( $files as $file )
    {
	$matches = array();
	preg_match ( '/.*backup-(\d+)-(\d+)-(\d+)\.sql/', $file , $matches);
	$date = CakeTime::fromString("$matches[1]/$matches[2]/$matches[3]");
	$isToday = CakeTime::isToday($date);
	$backupExists |= $isToday;
	if(!$isToday)
	{
	    $file = new File($dirPath.$file);
	    $file->delete();
	}
    }
    if(!$backupExists)
    {
       $this->requestAction('/config/dbBackup');
    }
  }

  public function exportExcel()
  {
    App::uses('CakeTime', 'Utility');
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');

    $backupExists = false;
    $dirPath = Configure::read('excelExportPath');
    $dir = new Folder($dirPath);
    $files = $dir->find('.*backup.*\.xls', true);
    $d = new DateTime();
    $d->modify( 'previous month' );

    foreach( $files as $file )
    {
	$matches = array();
	preg_match ( '/.*backup-(\d+)-(\d+)\.xls/', $file , $matches);
	$date = CakeTime::fromString("$matches[1]/$matches[2]/01");

	$isThisMonth = (date('m/Y', $date) == date('m/Y', $d->getTimestamp()));
	$backupExists |= $isThisMonth;
	if(!$isThisMonth)
	{
	    $file = new File($dirPath.$file);
	    $file->delete();
	}
    }
    if(!$backupExists)
    {
      $d = new DateTime();
      $d->modify( 'previous month' );
      $dateStart = date('01/m/Y',$d->getTimestamp()); // hard-coded '01' for first day
      $dateEnd  = date('t/m/Y', $d->getTimestamp());
      $fileName = $dirPath.'backup-'.date('Y-m', $d->getTimestamp()).'.xls';
       $this->requestAction(array('controller' => 'results', 'action' => 'index'), 
				  array('pass' => 
				      array($dateStart, $dateEnd, $fileName)
					    ));
    }
  }

  
  public function sendMail($config)
  {
	$configDefault = array('user'=>NULL,
							'email' => NULL,
							'view' => 'default',
							'data' => array(),
							'subject' => '',
							'message' => 'Hello World',
							'attachment' => NULL
					);
					
	$config = array_merge($configDefault, $config);


    // debug($config);
	
	$emailAddr = '';
	if($config['user'] == NULL)
	{
		if($config['email'] == NULL)
		{
			$this->log("Email not sent: no email or user specified", 'error');
			$this->Session->setFlash(__('Email not sent: no email or user specified'),'flash/fail');
		}
		else
		{
			$emailAddr = $config['email'];
		}
	}
	else
	{
		$emailAddr = $config['user']['User']['email'];
	}
	
	$normalAddr = '';
	if( Configure::read('email.debug.status') )
	{
		$normalAddr = ' (debug dest : '.$emailAddr.')';
		$emailAddr = Configure::read('email.debug.email');
	}
	
    $email = new CakeEmail('default');
	if($config['attachment'] != NULL )
	{
		$email->attachments($config['attachment']);
	}
	debug(array(Configure::read('email.from.email') => Configure::read('email.from.name')));
    $email->from(array(Configure::read('email.from.email') => Configure::read('email.from.name')))
	->sender(Configure::read('email.from.email'), Configure::read('email.from.name'))
        ->to($emailAddr)
        ->template($config['view'], 'default')
        ->viewVars($config['data'])
        ->subject($config['subject'])
        ->send($config['message']);

	$this->log('Email to '.$emailAddr.' : '.$config['subject'].', attachment: '.$config['attachment'], 'email');
	$this->Session->setFlash('Email to '.$emailAddr.$normalAddr.' : '.$config['subject'],'flash/ok');
		

    if ( Configure::read('debug') != 0)
    {
      //$this->Session->setFlash('Debug: email envoyé', 'flash/ok');
    }
  }
  
  public function beforeRender()
  {
   //debug($this->Auth->user());
    
//    $this->backupDb();
//     $this->exportExcel();

  
  
	if($this->Auth->user())
	{
		$this->menu['Menu']['Deconnexion'] = array( 'url' => $this->webroot.'users/logout', 'active' => false );
	}
	else
	{
		$this->menu['Menu']['Connexion'] = array( 'url' => $this->webroot.'users/login', 'active' => false );
	}
  
     $this->set('menu', $this->menu);
    
     // debug($this->Auth->user('isRoot'));
    $tokens = array('isAdmin'=> $this->Auth->user('isRoot') ,'members'=>$this->Auth->loggedIn());
    $this->set('tokens', $tokens);
    $news =  $this->requestAction(array('plugin'=>'', 'controller'=>'news', 'action'=>'getNews' ));
    $this->set('news',$news);
  }
  
  
  public function isAuthorized($user = null) {
	$ret = false;
	if(in_array('*',$this->publicActions) || $this->isCommandLineInterface())
	{
		return true;
	}
	if(in_array($this->request->params['action'],$this->publicActions))
	{
		return true;
	}
	
	if($this->Auth->loggedIn())
	{
		if(in_array('*',$this->memberActions))
		{
			return true;
		}
		if(in_array($this->request->params['action'],$this->memberActions))
		{
			return true;
		}
		
		if($this->Auth->user('isRoot'))
		{
			return true;
		}
	}
	


    // Default deny
    return false;
}
  
  
 public function isCommandLineInterface()
{
    return (php_sapi_name() === 'cli');
}
  

 function blackHole($error) {
    switch ($error) {
      case 'secure':
	$this->log(Router::url( $this->here, true ).' redirected to https', 'debug');
        $this->redirect('https://' . env('SERVER_NAME') . $this->here);
        break;
      default:
           $this->log('BlackHole error: '.$error.' for address '.$this->here, 'debug');
        break;
    }
}


// TODO move into a helper/components
public function getFunctionText($coefficients)
	{
		$functionText = "f(x) = ";
		if($coefficients === false)
		{
			$functionText .= 'error';
			return $functionText;
		}
			foreach($coefficients as $power => $coefficient)

			{

				$functionText .= ($coefficient > 0) ? " + " : " - ";

			  $functionText .= abs(round($coefficient, 4));

			  if ($power > 0)

			  {

				$functionText .= "x";

				if ($power > 1)

				  $functionText .= "^" . $power;

			  }

			}
		return $functionText;
		}


  public function beforeFilter()
  {
   if( $this->isCommandLineInterface()   )
   {
	$this->Auth->allow();
   }

  if( Configure::read('Security.ssl') && !($this->Session->check('noSSL') && $this->Session->read('noSSL')) && $this->action != 'noSSL')
  {
    if($this->Auth->user('isRoot'))
    {
		if(!$this->request->is('ssl'))
		{
			$this->blackHole('secure');
		}
    }
  }


	  // $user = $this->User->find('first',array('conditions'=>array('User.email' => 'mehdilauters@gmail.com')));
			// if(isset($user['User']['id']))
			// {

					// $this->Auth->login($user['User']);
			// }
	  // session_destroy();
	  
	if($this->Session->check('debugMode') && $this->Session->read('debugMode'))
	{
      $this->set('debugMode',true);
      Configure::write('debug', 2);
    }
//  	debug($this->request->params['controller']);
//  	debug($this->request->params['action']);
	parent::beforeFilter();
	
	$this->Cookie->name='userAutoLogging';
	if( count($this->publicActions) != 0)
	{
		// debug($this->publicActions);
		$this->Auth->allow($this->publicActions);
	}
	if(!$this->Auth->loggedIn())
	{
	  if(!($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'autologin'))
	  {
		  $this->requestAction(array('plugin'=> '', 'controller' => 'users', 'action' => 'autologin'));
	  }
	}


// 		if(!($this->request->params['controller'] == 'config' && $this->request->params['action'] == 'upgradeDbStructure'))
// 		{
// 			$this->requestAction(array('controller' => 'config', 'action' => 'upgradeDbStructure'));
// 		}


	
	
  }
}
