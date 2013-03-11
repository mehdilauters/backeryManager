<?php
App::uses('VideosController', 'Controller');

/**
 * VideosController Test Case
 *
 */
class VideosControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.video',
		'app.media',
		'app.user',
		'app.event',
		'app.product',
		'app.product_types',
		'app.products_type'
	);

}
