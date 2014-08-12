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
  public $uses = array('Shop', 'Photo', 'Product', 'FullCalendar.EventType');

  
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
//     $this->Shop->recursive = 3;
    $shops = $this->Shop->find('all', array('conditions'=>array('company_id'=> $this->getCompanyId())));
    foreach ($shops as $id=>$shop)
    {
      $shops[$id]['EventType']['Event'] = $this->requestAction(array(	'plugin'=>'full_calendar',
							'controller'=>'events',
							'action'=>'feed'
						),
						array( 
							'pass'=>array(
										'idType'=>$shops[$id]['EventType']['id'],
										'start' => time(),
										'end'=>(time() + 60*60*24*7*2)
										)
								)
										    );
      $shops[$id]['Shop']['isOpened'] = $this->requestAction(array('controller'=>'shops', 'action'=>'isOpened'), array( 'pass'=>array($shops[$id])));
    }

    $this->set('shops', $shops);

    $conditions = array('Product.media_id', 'ProductType.company_id' => $this->getCompanyId());
    $tokens = $this->getUserTokens();
    if(!$tokens['isAdmin'])
    {
      $conditions[] = 'Product.customer_display';
    }
    $this->Product->contain('Media.Photo', 'ProductType');
    $products = $this->Product->find('all', array('conditions'=>$conditions));
 
	$displayProducts = array();
	$daysProduct = false;
	foreach($products as $product)
	{
		if($product['Product']['produced_today'] != 0)
		{
			$daysProduct = true;
			$displayProducts[] = $product;
		}
	}
 
	if(!$daysProduct)
	{
		$displayProducts = $products;
	}
	
	$this->set('products', $displayProducts);
    $this->set(compact('page', 'subpage', 'title_for_layout', 'daysProduct'));
	if($tokens['isAdmin'])
	{
		$res = $this->requestAction(array('controller'=>'results', 'action'=>'stats'), array( 'pass'=>array('_conditions'=>array(), 'group' => array('time'=>'week', 'shop'=>true, 'productType'=>false))));
		$this->set('results',$res);
	}
	
    
	$eventType = $this->EventType->find('first',array('conditions'=>array('EventType.id'=>2)));
	$this->set('eventType',$eventType);
    $this->render(implode('/', $path));
  }
}
