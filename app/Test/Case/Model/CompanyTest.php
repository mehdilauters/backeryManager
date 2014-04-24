<?php
App::uses('Company', 'Model');

/**
 * Company Test Case
 *
 */
class CompanyTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.company',
		'app.media',
		'app.user',
		'app.order',
		'app.shop',
		'app.event_type',
		'app.event',
		'app.ordered_item',
		'app.product',
		'app.product_type',
		'app.sale',
		'app.photo'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Company = ClassRegistry::init('Company');
	}

	
	public function testCount() {
	$res = $this->Company->find('count');
    $this->assertEquals(1, $res);
}
	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Company);

		parent::tearDown();
	}

}
