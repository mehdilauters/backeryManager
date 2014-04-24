<?php
App::uses('MediaController', 'Controller');
include 'AppControllerTest.php';
/**
 * MediaController Test Case
 *
 */
class MediaControllerTest extends AppControllerTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.media',
		'app.user',
		'app.event',
		'app.event_type',
		'app.sale',
		'app.product',
		'app.product_type',
		'app.photo',
		'app.order',
		'app.company'
	);

}
