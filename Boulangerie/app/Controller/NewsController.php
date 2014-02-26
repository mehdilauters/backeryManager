<?php
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class NewsController extends AppController {
	var $publicActions = array('getNews', 'index');



	/**
	* Controller name
	*
	* @var string
	*/
	  public $name = 'News';

	/**
	* This controller does not use a model
	*
	* @var array
	*/
	  public $uses = array('FullCalendar.EventType');


  public function getNews()
  {
      $eventType = $this->EventType->findByName('news');
      $news = array();
	if( count($eventType) != 0)
	{
		$dateStart = new DateTime('yesterday');
		$dateStart->setTime(23,59);

		$dateEnd = new DateTime('tomorrow');
		$dateEnd->setTime(0,0);

		$news = $this->requestAction(array(	'plugin'=>'full_calendar',
							  'controller'=>'events',
							  'action'=>'feed'
						  ),
						  array( 
							  'pass'=>array(
										  'idType'=>$eventType['EventType']['id'],
										  'start' => $dateStart->getTimestamp(),
										  'end'=>$dateEnd->getTimestamp()
										  )
								  )
								  ,array('return')
					);
	}
     return $news;
  }

  public function index() {
	$news = $this->requestAction(array('controller'=>'news', 'action'=>'getNews'));
  	if ($this->RequestHandler->isRss() ) {
  		$this->set('title_for_layout', 'Les news');
          return $this->set(compact('news'));
      }


  }

} 

?>