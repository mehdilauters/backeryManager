<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {
	var $publicActions = array('display');
/**
 * Controller name
 *
 * @var string
 */
  public $name = 'Pages';

/**
 * This controller does not use a model
 *
 * @var array
 */
  public $uses = array('Shop', 'Photo', 'Product', 'EventType');

  
/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
  public function display() {
    $path = func_get_args();

    $count = count($path);
    if (!$count) {
      $this->redirect('/');
    }
    $page = $subpage = $title_for_layout = null;

    if (!empty($path[0])) {
      $page = $path[0];
    }
    if (!empty($path[1])) {
      $subpage = $path[1];
    }
    if (!empty($path[$count - 1])) {
      $title_for_layout = Inflector::humanize($path[$count - 1]);
    }
    
    $title_for_layout = 'Nos magasins';
    $this->menu['Menu']['Magasins']['active'] = true;
    $this->Shop->recursive = 3;
    $shops = $this->Shop->find('all');
    foreach ($shops as $id=>$shop)
    {
      $shops[$id]['Shop']['isOpened'] = $this->requestAction(array('controller'=>'shops', 'action'=>'isOpened'), array( 'pass'=>array($shop)));
    }
    $isCalendarAvailable = $this->requestAction(array('controller'=>'events', 'action'=>'eventsAvailable'));
    $this->set('isCalendarAvailable', $isCalendarAvailable);
    $this->set('shops', $shops);

    $conditions = array();
    $conditions[] = 'Product.media_id';
    if(!$this->Auth->user('isRoot'))
    {
      $conditions[] = 'Product.customer_display';
    }
    $this->Product->contain('Media.Photo');
    $products = $this->Product->find('all', array('conditions'=>$conditions));
 
    $this->set(compact('page', 'subpage', 'title_for_layout', 'products'));
	if($this->Auth->user('isRoot'))
	{
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array(), 'group' => array('time'=>'week', 'shop'=>'shop'))));
		$this->set('results',$res['results']);
	}
	
	$isCalendarAvailable = $this->requestAction(array('controller'=>'events', 'action'=>'eventsAvailable'));
    $contain = array();
    if($isCalendarAvailable)
    {
	  $contain[] = 'Event.EventType';
      $contain[] = 'Event.Gevent.GeventDate';
    }
    
	$this->EventType->contain($contain);
	$eventType = $this->EventType->find('first',array('conditions'=>array('EventType.id'=>2)));
	$this->set('eventType',$eventType);
    $this->render(implode('/', $path));
  }
}
