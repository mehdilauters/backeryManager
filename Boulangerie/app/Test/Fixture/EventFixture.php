<?php
/**
 * EventFixture
 *
 */
class EventFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'event_type_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'details' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'start' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'end' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'recursive_start' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'recursive_end' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'all_day' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'Scheduled', 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'created' => array('type' => 'date', 'null' => true, 'default' => null),
		'modified' => array('type' => 'date', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_events_event_type' => array('column' => 'event_type_id', 'unique' => 0)
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
			'event_type_id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'start' => '2014-04-01 10:18:55',
			'end' => '2014-04-01 10:18:55',
			'recursive_start' => '2014-04-01 10:18:55',
			'recursive_end' => '2014-04-01 10:18:55',
			'all_day' => 1,
			'status' => 'Lorem ipsum dolor ',
			'active' => 1,
			'created' => '2014-04-01',
			'modified' => '2014-04-01'
		),
		array(
			'id' => 2,
			'event_type_id' => 2,
			'title' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'start' => '2014-04-01 10:18:55',
			'end' => '2014-04-01 10:18:55',
			'recursive_start' => '2014-04-01 10:18:55',
			'recursive_end' => '2014-04-01 10:18:55',
			'all_day' => 1,
			'status' => 'Lorem ipsum dolor ',
			'active' => 1,
			'created' => '2014-04-01',
			'modified' => '2014-04-01'
		),
		array(
			'id' => 3,
			'event_type_id' => 3,
			'title' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'start' => '2014-04-01 10:18:55',
			'end' => '2014-04-01 10:18:55',
			'recursive_start' => '2014-04-01 10:18:55',
			'recursive_end' => '2014-04-01 10:18:55',
			'all_day' => 1,
			'status' => 'Lorem ipsum dolor ',
			'active' => 1,
			'created' => '2014-04-01',
			'modified' => '2014-04-01'
		),
		array(
			'id' => 4,
			'event_type_id' => 4,
			'title' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'start' => '2014-04-01 10:18:55',
			'end' => '2014-04-01 10:18:55',
			'recursive_start' => '2014-04-01 10:18:55',
			'recursive_end' => '2014-04-01 10:18:55',
			'all_day' => 1,
			'status' => 'Lorem ipsum dolor ',
			'active' => 1,
			'created' => '2014-04-01',
			'modified' => '2014-04-01'
		),
		array(
			'id' => 5,
			'event_type_id' => 5,
			'title' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'start' => '2014-04-01 10:18:55',
			'end' => '2014-04-01 10:18:55',
			'recursive_start' => '2014-04-01 10:18:55',
			'recursive_end' => '2014-04-01 10:18:55',
			'all_day' => 1,
			'status' => 'Lorem ipsum dolor ',
			'active' => 1,
			'created' => '2014-04-01',
			'modified' => '2014-04-01'
		),
		array(
			'id' => 6,
			'event_type_id' => 6,
			'title' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'start' => '2014-04-01 10:18:55',
			'end' => '2014-04-01 10:18:55',
			'recursive_start' => '2014-04-01 10:18:55',
			'recursive_end' => '2014-04-01 10:18:55',
			'all_day' => 1,
			'status' => 'Lorem ipsum dolor ',
			'active' => 1,
			'created' => '2014-04-01',
			'modified' => '2014-04-01'
		),
		array(
			'id' => 7,
			'event_type_id' => 7,
			'title' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'start' => '2014-04-01 10:18:55',
			'end' => '2014-04-01 10:18:55',
			'recursive_start' => '2014-04-01 10:18:55',
			'recursive_end' => '2014-04-01 10:18:55',
			'all_day' => 1,
			'status' => 'Lorem ipsum dolor ',
			'active' => 1,
			'created' => '2014-04-01',
			'modified' => '2014-04-01'
		),
		array(
			'id' => 8,
			'event_type_id' => 8,
			'title' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'start' => '2014-04-01 10:18:56',
			'end' => '2014-04-01 10:18:56',
			'recursive_start' => '2014-04-01 10:18:56',
			'recursive_end' => '2014-04-01 10:18:56',
			'all_day' => 1,
			'status' => 'Lorem ipsum dolor ',
			'active' => 1,
			'created' => '2014-04-01',
			'modified' => '2014-04-01'
		),
		array(
			'id' => 9,
			'event_type_id' => 9,
			'title' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'start' => '2014-04-01 10:18:56',
			'end' => '2014-04-01 10:18:56',
			'recursive_start' => '2014-04-01 10:18:56',
			'recursive_end' => '2014-04-01 10:18:56',
			'all_day' => 1,
			'status' => 'Lorem ipsum dolor ',
			'active' => 1,
			'created' => '2014-04-01',
			'modified' => '2014-04-01'
		),
		array(
			'id' => 10,
			'event_type_id' => 10,
			'title' => 'Lorem ipsum dolor sit amet',
			'details' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'start' => '2014-04-01 10:18:56',
			'end' => '2014-04-01 10:18:56',
			'recursive_start' => '2014-04-01 10:18:56',
			'recursive_end' => '2014-04-01 10:18:56',
			'all_day' => 1,
			'status' => 'Lorem ipsum dolor ',
			'active' => 1,
			'created' => '2014-04-01',
			'modified' => '2014-04-01'
		),
	);

}
