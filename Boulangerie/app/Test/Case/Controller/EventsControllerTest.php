<?php
App::uses('EventsController', 'Controller');

/**
 * EventsController Test Case
 *
 */
class EventsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.event',
		'app.media',
		'app.user',
		'app.product',
		'app.product_types',
		'app.product_type',
		'app.products'
	);

}
