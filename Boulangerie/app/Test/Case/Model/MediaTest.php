<?php
App::uses('Media', 'Model');

/**
 * Media Test Case
 *
 */
class MediaTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.media',
		'app.user',
		'app.event',
		'app.product',
		'app.products_type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Media = ClassRegistry::init('Media');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Media);

		parent::tearDown();
	}

}
