<?php
App::uses('EventType', 'Model');

/**
 * EventType Test Case
 *
 */
class EventTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.event_type',
		'app.event',
		'app.media',
		'app.user',
		'app.product',
		'app.product_types',
		'app.product_type',
		'app.products'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->EventType = ClassRegistry::init('EventType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EventType);

		parent::tearDown();
	}

}
