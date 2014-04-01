<?php
/**
 * VideoFixture
 *
 */
class VideoFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_videos_medias' => array('column' => 'id', 'unique' => 0)
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
			'id' => 1
		),
		array(
			'id' => 2
		),
		array(
			'id' => 3
		),
		array(
			'id' => 4
		),
		array(
			'id' => 5
		),
		array(
			'id' => 6
		),
		array(
			'id' => 7
		),
		array(
			'id' => 8
		),
		array(
			'id' => 9
		),
		array(
			'id' => 10
		),
	);

}
