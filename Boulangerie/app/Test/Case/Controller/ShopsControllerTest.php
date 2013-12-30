<?php
App::uses('ShopsController', 'Controller');

/**
 * ShopsController Test Case
 *
 */
class ShopsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shop',
		'app.media',
		'app.user',
		'app.event',
		'app.product',
		'app.product_type',
		'app.products',
		'app.event_type',
		'app.gcalendar',
		'app.gevent',
		'app.gevent_date'
	);

}
