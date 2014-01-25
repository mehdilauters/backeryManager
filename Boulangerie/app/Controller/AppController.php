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


	  var $publicActions = array();
	  var $memberActions = array();

  public $components = array('DebugKit.Toolbar', 'Session', 'Cookie',
															'Auth' => array(
																'authenticate' => array(
																	'Form' => array(
																		'fields' => array('username' => 'email'),
																		'passwordHasher' => array(
																			'className' => 'Simple',
																			'hashType' => 'sha256'
																		)
																	)
																),
																'loginRedirect' => '/',
																'logoutRedirect' => '/',
															)
														);
  public $menu = array('Menu' => 
			array( 
				'Nos Magasins' => 
				  array( 'url' => 'WEBROOT/', 'active' => false ),
				 'Produits' => 
				    array( 'url' => 'WEBROOT/productTypes', 'active' => false ),
				 'Horaires' => 
				    array( 'url' => 'WEBROOT/events', 'active' => false ),
				 'Contact' => 
				    array( 'url' => 'WEBROOT/users/add', 'active' => false ),
		    )
			);
  
  
  

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

  public function beforeRender()
  {
    $this->backupDb();
    $this->exportExcel();

  
  
	if($this->Auth->user())
	{
		$this->menu['Menu']['Deconnexion'] = array( 'url' => $this->webroot.'users/logout', 'active' => false );
	}
	else
	{
		$this->menu['Menu']['Connexion'] = array( 'url' => $this->webroot.'users/login', 'active' => false );
	}
  
     $this->set('menu', $this->menu);
    //TODO debug mode
    $tokens = array('isAdmin'=> $this->Auth->user('User.isRoot') ,'members'=>$this->Auth->loggedIn());
    $this->set('tokens', $tokens);
  }
  
 
  
  
  public function beforeFilter()
  {
	if($this->Session->check('debugMode') && $this->Session->read('debugMode'))
	{
      $this->set('debugMode',true);
      Configure::write('debug', 2);
    }
// 	debug($this->request->params['controller']);
// 	debug($this->request->params['action']);
	parent::beforeFilter();
	
	$this->Cookie->name='userAutoLogging';
	
	$this->Auth->allow($this->publicActions);
	if(!$this->Auth->loggedIn())
	{
	  if(!($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'autologin'))
	  {
		  $this->requestAction(array('controller' => 'users', 'action' => 'autologin'));
	  }
	}

	if($this->Auth->loggedIn())
	{
		$this->Auth->allow($this->memberActions);
		if($this->Auth->user('isRoot'))
		{
			$this->Auth->allow();
		}
	}
	else
	{
	    
	}
// 		if(!($this->request->params['controller'] == 'config' && $this->request->params['action'] == 'upgradeDbStructure'))
// 		{
// 			$this->requestAction(array('controller' => 'config', 'action' => 'upgradeDbStructure'));
// 		}


	
	
	
	
  }
}
