<?php
/**
 * EventTypeFixture
 *
 */
class EventTypeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'name' => 'Lorem ipsum dolor '
		),
		array(
			'id' => 2,
			'name' => 'Lorem ipsum dolor '
		),
		array(
			'id' => 3,
			'name' => 'Lorem ipsum dolor '
		),
		array(
			'id' => 4,
			'name' => 'Lorem ipsum dolor '
		),
		array(
			'id' => 5,
			'name' => 'Lorem ipsum dolor '
		),
		array(
			'id' => 6,
			'name' => 'Lorem ipsum dolor '
		),
		array(
			'id' => 7,
			'name' => 'Lorem ipsum dolor '
		),
		array(
			'id' => 8,
			'name' => 'Lorem ipsum dolor '
		),
		array(
			'id' => 9,
			'name' => 'Lorem ipsum dolor '
		),
		array(
			'id' => 10,
			'name' => 'Lorem ipsum dolor '
		),
	);

}
