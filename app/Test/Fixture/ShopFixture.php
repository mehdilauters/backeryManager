<?php
/**
 * ShopFixture
 *
 */
class ShopFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'Shop');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'media_id' => '2',
			'event_type_id' => '2',
			'name' => 'Boulangerie Faury',
			'phone' => '492350275',
			'address' => '61 Avenue de la Libération, 04310 Peyruis, France',
			'description' => 'Ouverte il y a 2 ans!!',
			'created' => '2013-03-12 13:11:06',
			'company_id' => '1'
		),
		array(
			'id' => '2',
			'media_id' => '105',
			'event_type_id' => '3',
			'name' => 'Depot',
			'phone' => '490883377',
			'address' => '84330 Les mees',
			'description' => 'Plus près de chez vous',
			'created' => '2013-03-12 13:11:31',
			'company_id' => '1'
		),
		array(
			'id' => '5',
			'media_id' => '107',
			'event_type_id' => '4',
			'name' => 'Magasin1',
			'phone' => '7',
			'address' => 'je suis là!',
			'description' => '<p>coucou</p>',
			'created' => '2014-07-19 15:52:14',
			'company_id' => '3'
		),
	);

}
