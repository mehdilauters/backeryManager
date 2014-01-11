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
  public $components = array('DebugKit.Toolbar', 'Session');
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
    $isAdmin = false;
    if($this->Session->check('isAdmin'))
    {
     $isAdmin =   $this->Session->read('isAdmin');
    }
  
     $this->set('menu', $this->menu);
    //TODO debug mode
    $isAdmin = true;
    $tokens = array('isAdmin'=> $isAdmin ,'members'=>true);
    $this->set('tokens', $tokens);
  }
}
