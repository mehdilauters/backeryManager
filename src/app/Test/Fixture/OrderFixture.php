<?php
/**
 * OrderFixture
 *
 */
class OrderFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'Order');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '2',
			'shop_id' => '1',
			'created' => '2014-03-22 17:56:49',
			'user_id' => '5',
			'status' => 'paid',
			'delivery_date' => '2014-03-01 00:00:00',
			'comment' => '',
			'discount' => '10'
		),
		array(
			'id' => '5',
			'shop_id' => '1',
			'created' => '2014-03-23 14:14:04',
			'user_id' => '4',
			'status' => 'paid',
			'delivery_date' => '2014-03-20 00:00:00',
			'comment' => '',
			'discount' => '10'
		),
		array(
			'id' => '6',
			'shop_id' => '1',
			'created' => '2014-03-26 17:19:37',
			'user_id' => '6',
			'status' => 'paid',
			'delivery_date' => '2014-03-25 00:00:00',
			'comment' => '',
			'discount' => '0'
		),
		array(
			'id' => '7',
			'shop_id' => '1',
			'created' => '2014-03-27 10:48:12',
			'user_id' => '7',
			'status' => 'paid',
			'delivery_date' => '2014-03-12 00:00:00',
			'comment' => '',
			'discount' => '0'
		),
		array(
			'id' => '8',
			'shop_id' => '1',
			'created' => '2014-03-27 10:52:45',
			'user_id' => '10',
			'status' => 'paid',
			'delivery_date' => '2014-03-13 00:00:00',
			'comment' => '',
			'discount' => '0'
		),
		array(
			'id' => '9',
			'shop_id' => '1',
			'created' => '2014-04-02 07:08:10',
			'user_id' => '7',
			'status' => 'paid',
			'delivery_date' => '2014-04-02 00:00:00',
			'comment' => '',
			'discount' => '0'
		),
		array(
			'id' => '10',
			'shop_id' => '1',
			'created' => '2014-04-02 07:13:16',
			'user_id' => '6',
			'status' => 'paid',
			'delivery_date' => '2014-04-02 00:00:00',
			'comment' => '',
			'discount' => '0'
		),
		array(
			'id' => '12',
			'shop_id' => '1',
			'created' => '2014-04-03 09:38:40',
			'user_id' => '10',
			'status' => 'paid',
			'delivery_date' => '2014-04-03 00:00:00',
			'comment' => '',
			'discount' => '0'
		),
		array(
			'id' => '14',
			'shop_id' => '1',
			'created' => '2014-04-04 08:17:07',
			'user_id' => '5',
			'status' => 'paid',
			'delivery_date' => '2014-04-04 00:00:00',
			'comment' => '',
			'discount' => '10'
		),
		array(
			'id' => '15',
			'shop_id' => '1',
			'created' => '2014-04-05 10:53:19',
			'user_id' => '4',
			'status' => 'paid',
			'delivery_date' => '2014-04-05 00:00:00',
			'comment' => '',
			'discount' => '10'
		),
	);

}
