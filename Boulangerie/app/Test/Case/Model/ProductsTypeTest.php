<?php
App::uses('ProductsType', 'Model');

/**
 * ProductsType Test Case
 *
 */
class ProductsTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.products_type',
		'app.media',
		'app.user',
		'app.event',
		'app.product',
		'app.product_types',
		'app.etyetyhet'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ProductsType = ClassRegistry::init('ProductsType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ProductsType);

		parent::tearDown();
	}

}
