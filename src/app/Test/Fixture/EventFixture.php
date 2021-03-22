<?php
/**
 * EventFixture
 *
 */
class EventFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'Event');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '3',
			'event_type_id' => '1',
			'title' => 'Tableau de bord',
			'details' => 'Le lien tableau de bord marche maintenant.',
			'start' => '2014-03-21 00:00:00',
			'end' => '2014-03-23 00:00:00',
			'recursive_start' => null,
			'recursive_end' => null,
			'recursive' => null,
			'all_day' => 0,
			'status' => 'Scheduled',
			'active' => 1,
			'created' => '2014-03-21',
			'modified' => '2014-03-21'
		),
		array(
			'id' => '4',
			'event_type_id' => '2',
			'title' => 'mardi matin',
			'details' => '',
			'start' => '2014-06-17 07:00:00',
			'end' => '2014-06-17 13:00:00',
			'recursive_start' => '2014-06-17 00:00:00',
			'recursive_end' => '2015-06-17 23:59:00',
			'recursive' => 'week',
			'all_day' => 0,
			'status' => 'Scheduled',
			'active' => 1,
			'created' => '2014-06-17',
			'modified' => '2014-06-17'
		),
		array(
			'id' => '5',
			'event_type_id' => '2',
			'title' => 'dimanche matin',
			'details' => '',
			'start' => '2014-06-22 07:00:00',
			'end' => '2014-06-22 13:00:00',
			'recursive_start' => '2014-06-17 00:00:00',
			'recursive_end' => '2015-06-17 23:59:00',
			'recursive' => 'week',
			'all_day' => 0,
			'status' => 'Scheduled',
			'active' => 1,
			'created' => '2014-06-17',
			'modified' => '2014-06-17'
		),
		array(
			'id' => '6',
			'event_type_id' => '2',
			'title' => 'jeudi matin',
			'details' => '',
			'start' => '2014-06-19 07:00:00',
			'end' => '2014-06-19 13:00:00',
			'recursive_start' => '2014-06-17 00:00:00',
			'recursive_end' => '2015-06-17 23:59:00',
			'recursive' => 'week',
			'all_day' => 0,
			'status' => 'Scheduled',
			'active' => 1,
			'created' => '2014-06-17',
			'modified' => '2014-06-17'
		),
		array(
			'id' => '7',
			'event_type_id' => '2',
			'title' => 'vendredi matin',
			'details' => '',
			'start' => '2014-06-20 07:00:00',
			'end' => '2014-06-20 13:00:00',
			'recursive_start' => '2014-06-17 00:00:00',
			'recursive_end' => '2015-06-17 23:59:00',
			'recursive' => 'week',
			'all_day' => 0,
			'status' => 'Scheduled',
			'active' => 1,
			'created' => '2014-06-17',
			'modified' => '2014-06-17'
		),
		array(
			'id' => '8',
			'event_type_id' => '2',
			'title' => 'samedi matin',
			'details' => '',
			'start' => '2014-06-21 07:00:00',
			'end' => '2014-06-21 13:00:00',
			'recursive_start' => '2014-06-17 00:00:00',
			'recursive_end' => '2015-06-17 23:59:00',
			'recursive' => 'week',
			'all_day' => 0,
			'status' => 'Scheduled',
			'active' => 1,
			'created' => '2014-06-17',
			'modified' => '2014-06-17'
		),
		array(
			'id' => '9',
			'event_type_id' => '2',
			'title' => 'dimanche apres midi',
			'details' => '',
			'start' => '2014-06-22 16:00:00',
			'end' => '2014-06-22 19:00:00',
			'recursive_start' => '2014-06-17 00:00:00',
			'recursive_end' => '2015-06-17 23:59:00',
			'recursive' => 'week',
			'all_day' => 0,
			'status' => 'Scheduled',
			'active' => 1,
			'created' => '2014-06-17',
			'modified' => '2014-06-17'
		),
		array(
			'id' => '10',
			'event_type_id' => '2',
			'title' => 'mercredi apres midi',
			'details' => '',
			'start' => '2014-06-18 16:00:00',
			'end' => '2014-06-18 19:00:00',
			'recursive_start' => '2014-06-17 00:00:00',
			'recursive_end' => '2015-06-17 23:59:00',
			'recursive' => 'week',
			'all_day' => 0,
			'status' => 'Scheduled',
			'active' => 1,
			'created' => '2014-06-17',
			'modified' => '2014-06-17'
		),
		array(
			'id' => '11',
			'event_type_id' => '2',
			'title' => 'vendredi apres midi',
			'details' => '',
			'start' => '2014-06-20 16:00:00',
			'end' => '2014-06-20 19:00:00',
			'recursive_start' => '2014-06-17 00:00:00',
			'recursive_end' => '2015-06-17 23:59:00',
			'recursive' => 'week',
			'all_day' => 0,
			'status' => 'Scheduled',
			'active' => 1,
			'created' => '2014-06-17',
			'modified' => '2014-06-17'
		),
		array(
			'id' => '12',
			'event_type_id' => '2',
			'title' => 'jeudi apres midi',
			'details' => '',
			'start' => '2014-06-19 16:00:00',
			'end' => '2014-06-19 19:00:00',
			'recursive_start' => '2014-06-17 00:00:00',
			'recursive_end' => '2015-06-17 23:59:00',
			'recursive' => 'week',
			'all_day' => 0,
			'status' => 'Scheduled',
			'active' => 1,
			'created' => '2014-06-17',
			'modified' => '2014-06-17'
		),
	);

}
