<?php
App::uses('PhotosController', 'Controller');
include 'AppControllerTest.php';
/**
 * PhotosController Test Case
 *
 */
class PhotosControllerTest extends AppControllerTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.media',
		'app.photo',
		'app.user',
		'app.company',
		'app.event',
		'app.product',
		'app.product_type',
		'app.order',
	);
}
