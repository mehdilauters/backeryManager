<?php
App::uses('Shop', 'Model');

/**
 * Shop Test Case
 *
 */
class ShopTest extends CakeTestCase {

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

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Shop = ClassRegistry::init('Shop');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Shop);

		parent::tearDown();
	}

}
