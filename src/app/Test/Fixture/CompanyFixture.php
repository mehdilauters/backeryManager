<?php
/**
 * CompanyFixture
 *
 */
class CompanyFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'Company');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'rib' => '106',
			'address' => '61 avenue de la libération<br/>04310 PEYRUIS',
			'email' => 'boulangeriefaury@orange.fr',
			'phone' => '492350275',
			'capital' => '6000',
			'siret' => '75115697700017',
			'name' => 'SARL FAURY',
			'order_legals_mentions' => '',
			'title' => 'Christiane & Thierry FAURY',
			'event_type_id' => '1'
		),
		array(
			'id' => '3',
			'rib' => '80',
			'address' => 'coucou l\'adresse',
			'email' => 'mooi@p.fr',
			'phone' => '66666666',
			'capital' => '4',
			'siret' => '33344',
			'name' => 'testCompany',
			'order_legals_mentions' => null,
			'title' => 'Teeest',
			'event_type_id' => '1'
		),
	);

}
