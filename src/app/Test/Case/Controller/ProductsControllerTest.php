<?php
App::uses('ProductsController', 'Controller');
include 'AppControllerTest.php';

/**
 * ProductsController Test Case
 *
 */
class ProductsControllerTest extends AppControllerTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.product',
		'app.media',
		'app.photo',
		'app.shop',
// 		'app.order',
// 		'app.user',
// 		'app.event',
		'app.sale',
		'app.product_type',
		'app.company',
// 		'app.EventType'
	);
	
	
		public function setUp() {
		parent::setUp();
		$photo = $this->Product->Media->Photo->find('first');
		$this->testRecord = array(
					  array(
						  'Product' => array(
							  'name' => 'testInsert1',
							  'media_id' => $photo['Photo']['id'],
						  )
					  ),
					  array(
						  'Product' => array(
							  'name' => 'testInsert2',
							  'media_id' => $photo['Photo']['id'],
						  )
					  ),
				  );
	}

}
