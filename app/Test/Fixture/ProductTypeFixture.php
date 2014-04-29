<?php
/**
 * ProductTypeFixture
 *
 */
class ProductTypeFixture extends CakeTestFixture {

public $import = array('table' => 'product_types', 'records' => true);

// /**
 // * Fields
 // *
 // * @var array
 // */
	// public $fields = array(
		// 'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		// 'media_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		// 'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		// 'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
		// 'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		// 'customer_display' => array('type' => 'boolean', 'null' => true, 'default' => '1'),
		// 'tva' => array('type' => 'float', 'null' => false, 'default' => null),
		// 'indexes' => array(
			// 'PRIMARY' => array('column' => 'id', 'unique' => 1),
			// 'fk_producttypes_media' => array('column' => 'media_id', 'unique' => 0)
		// ),
		// 'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	// );

// /**
 // * Records
 // *
 // * @var array
 // */
	// public $records = array(
		// array(
			// 'id' => 1,
			// 'media_id' => 1,
			// 'name' => 'Lorem ipsum dolor sit amet',
			// 'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			// 'created' => '2014-04-01 10:19:01',
			// 'customer_display' => 1,
			// 'tva' => 1
		// ),
		// array(
			// 'id' => 2,
			// 'media_id' => 2,
			// 'name' => 'Lorem ipsum dolor sit amet',
			// 'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			// 'created' => '2014-04-01 10:19:01',
			// 'customer_display' => 1,
			// 'tva' => 2
		// ),
		// array(
			// 'id' => 3,
			// 'media_id' => 3,
			// 'name' => 'Lorem ipsum dolor sit amet',
			// 'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			// 'created' => '2014-04-01 10:19:01',
			// 'customer_display' => 1,
			// 'tva' => 3
		// ),
		// array(
			// 'id' => 4,
			// 'media_id' => 4,
			// 'name' => 'Lorem ipsum dolor sit amet',
			// 'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			// 'created' => '2014-04-01 10:19:01',
			// 'customer_display' => 1,
			// 'tva' => 4
		// ),
		// array(
			// 'id' => 5,
			// 'media_id' => 5,
			// 'name' => 'Lorem ipsum dolor sit amet',
			// 'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			// 'created' => '2014-04-01 10:19:01',
			// 'customer_display' => 1,
			// 'tva' => 5
		// ),
		// array(
			// 'id' => 6,
			// 'media_id' => 6,
			// 'name' => 'Lorem ipsum dolor sit amet',
			// 'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			// 'created' => '2014-04-01 10:19:01',
			// 'customer_display' => 1,
			// 'tva' => 6
		// ),
		// array(
			// 'id' => 7,
			// 'media_id' => 7,
			// 'name' => 'Lorem ipsum dolor sit amet',
			// 'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			// 'created' => '2014-04-01 10:19:01',
			// 'customer_display' => 1,
			// 'tva' => 7
		// ),
		// array(
			// 'id' => 8,
			// 'media_id' => 8,
			// 'name' => 'Lorem ipsum dolor sit amet',
			// 'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			// 'created' => '2014-04-01 10:19:01',
			// 'customer_display' => 1,
			// 'tva' => 8
		// ),
		// array(
			// 'id' => 9,
			// 'media_id' => 9,
			// 'name' => 'Lorem ipsum dolor sit amet',
			// 'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			// 'created' => '2014-04-01 10:19:01',
			// 'customer_display' => 1,
			// 'tva' => 9
		// ),
		// array(
			// 'id' => 10,
			// 'media_id' => 10,
			// 'name' => 'Lorem ipsum dolor sit amet',
			// 'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			// 'created' => '2014-04-01 10:19:01',
			// 'customer_display' => 1,
			// 'tva' => 10
		// ),
	// );

}
