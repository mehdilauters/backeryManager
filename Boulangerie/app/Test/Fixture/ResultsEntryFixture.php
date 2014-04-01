<?php
/**
 * ResultsEntryFixture
 *
 */
class ResultsEntryFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'result_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'product_types_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'result' => array('type' => 'float', 'null' => true, 'default' => null),
		'shop_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'date' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'unique_results' => array('column' => array('result_id', 'product_types_id'), 'unique' => 1),
			'fk_results_entries_results' => array('column' => 'result_id', 'unique' => 0),
			'fk_results_entries_productsTypes' => array('column' => 'product_types_id', 'unique' => 0),
			'fk_results_entries_shops' => array('column' => 'shop_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'result_id' => 1,
			'product_types_id' => 1,
			'result' => 1,
			'shop_id' => 1,
			'date' => '2014-04-01 10:19:05'
		),
		array(
			'id' => 2,
			'result_id' => 2,
			'product_types_id' => 2,
			'result' => 2,
			'shop_id' => 2,
			'date' => '2014-04-01 10:19:05'
		),
		array(
			'id' => 3,
			'result_id' => 3,
			'product_types_id' => 3,
			'result' => 3,
			'shop_id' => 3,
			'date' => '2014-04-01 10:19:05'
		),
		array(
			'id' => 4,
			'result_id' => 4,
			'product_types_id' => 4,
			'result' => 4,
			'shop_id' => 4,
			'date' => '2014-04-01 10:19:05'
		),
		array(
			'id' => 5,
			'result_id' => 5,
			'product_types_id' => 5,
			'result' => 5,
			'shop_id' => 5,
			'date' => '2014-04-01 10:19:05'
		),
		array(
			'id' => 6,
			'result_id' => 6,
			'product_types_id' => 6,
			'result' => 6,
			'shop_id' => 6,
			'date' => '2014-04-01 10:19:05'
		),
		array(
			'id' => 7,
			'result_id' => 7,
			'product_types_id' => 7,
			'result' => 7,
			'shop_id' => 7,
			'date' => '2014-04-01 10:19:05'
		),
		array(
			'id' => 8,
			'result_id' => 8,
			'product_types_id' => 8,
			'result' => 8,
			'shop_id' => 8,
			'date' => '2014-04-01 10:19:05'
		),
		array(
			'id' => 9,
			'result_id' => 9,
			'product_types_id' => 9,
			'result' => 9,
			'shop_id' => 9,
			'date' => '2014-04-01 10:19:05'
		),
		array(
			'id' => 10,
			'result_id' => 10,
			'product_types_id' => 10,
			'result' => 10,
			'shop_id' => 10,
			'date' => '2014-04-01 10:19:05'
		),
	);

}
