<?php
/**
 * UserFixture
 *
 */
class UserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'media_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'isRoot' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'rib_on_orders' => array('type' => 'boolean', 'null' => true, 'default' => '1'),
		'address' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		'phone' => array('type' => 'integer', 'null' => true, 'default' => null),
		'discount' => array('type' => 'float', 'null' => true, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'fk_users_media' => array('column' => 'media_id', 'unique' => 0)
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
			'media_id' => 1,
			'email' => 'mehdilauters@gmail.com',
			'password' => '48be5c03b006a57a6cfc8b1bc056e7bae2f91477',
			'isRoot' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-04-01 10:19:08',
			'rib_on_orders' => 1,
			'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'phone' => 1,
			'discount' => 1
		),
		array(
			'id' => 2,
			'media_id' => 2,
			'email' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'isRoot' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-04-01 10:19:08',
			'rib_on_orders' => 1,
			'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'phone' => 2,
			'discount' => 2
		),
		array(
			'id' => 3,
			'media_id' => 3,
			'email' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'isRoot' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-04-01 10:19:08',
			'rib_on_orders' => 1,
			'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'phone' => 3,
			'discount' => 3
		),
		array(
			'id' => 4,
			'media_id' => 4,
			'email' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'isRoot' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-04-01 10:19:08',
			'rib_on_orders' => 1,
			'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'phone' => 4,
			'discount' => 4
		),
		array(
			'id' => 5,
			'media_id' => 5,
			'email' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'isRoot' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-04-01 10:19:08',
			'rib_on_orders' => 1,
			'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'phone' => 5,
			'discount' => 5
		),
		array(
			'id' => 6,
			'media_id' => 6,
			'email' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'isRoot' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-04-01 10:19:08',
			'rib_on_orders' => 1,
			'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'phone' => 6,
			'discount' => 6
		),
		array(
			'id' => 7,
			'media_id' => 7,
			'email' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'isRoot' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-04-01 10:19:08',
			'rib_on_orders' => 1,
			'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'phone' => 7,
			'discount' => 7
		),
		array(
			'id' => 8,
			'media_id' => 8,
			'email' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'isRoot' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-04-01 10:19:08',
			'rib_on_orders' => 1,
			'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'phone' => 8,
			'discount' => 8
		),
		array(
			'id' => 9,
			'media_id' => 9,
			'email' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'isRoot' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-04-01 10:19:08',
			'rib_on_orders' => 1,
			'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'phone' => 9,
			'discount' => 9
		),
		array(
			'id' => 10,
			'media_id' => 10,
			'email' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'isRoot' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2014-04-01 10:19:08',
			'rib_on_orders' => 1,
			'address' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'phone' => 10,
			'discount' => 10
		),
	);

}
