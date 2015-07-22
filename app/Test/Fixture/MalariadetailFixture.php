<?php
/**
 * MalariadetailFixture
 *
 */
class MalariadetailFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'malaria_patients_in_facility' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => true),
		'moh_guidelines' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'how_to_manage_patients_with_severe_malaria' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'do_you_prescribe_treatment' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'what_green_leaf_represents' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'heard_about_green_leaf' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'how_you_suspect_malaria' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'number_of_children' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => true),
		'why_prescribe_without_green_leaf' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'signs_of_severe_malaria' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'completion_date' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'recommendation_next_step' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'is_adhock' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'how_did_you_hear' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'point_ofsale_material' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'lng' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'lat' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'client_ref_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => true, 'key' => 'index'),
		'_date_last_updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'uuid' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'date_created' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'last_updated' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'leave_uuid_intact' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'_date_created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'know_about_green_leaf_antimalarials' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'do_you_prescribe_without_green_leaf' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'do_you_know_moh_guidelines' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'know_what_severe_malaria_is' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'due_date' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'index_foreignkey_malariadetails_client_ref' => array('column' => 'client_ref_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8mb4', 'collate' => 'utf8mb4_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'malaria_patients_in_facility' => 1,
			'moh_guidelines' => 'Lorem ipsum dolor sit amet',
			'how_to_manage_patients_with_severe_malaria' => 'Lorem ipsum dolor sit amet',
			'do_you_prescribe_treatment' => 'Lorem ipsum dolor sit amet',
			'what_green_leaf_represents' => 'Lorem ipsum dolor sit amet',
			'heard_about_green_leaf' => 'Lorem ipsum dolor sit amet',
			'how_you_suspect_malaria' => 'Lorem ipsum dolor sit amet',
			'number_of_children' => 1,
			'why_prescribe_without_green_leaf' => 'Lorem ipsum dolor sit amet',
			'signs_of_severe_malaria' => 'Lorem ipsum dolor sit amet',
			'type' => 'Lorem ipsum dolor sit amet',
			'completion_date' => 1,
			'recommendation_next_step' => 'Lorem ipsum dolor sit amet',
			'description' => 'Lorem ipsum dolor sit amet',
			'is_adhock' => 1,
			'status' => 'Lorem ipsum dolor sit amet',
			'how_did_you_hear' => 'Lorem ipsum dolor sit amet',
			'point_ofsale_material' => 'Lorem ipsum dolor sit amet',
			'lng' => 1,
			'lat' => 1,
			'client_ref_id' => 1,
			'_date_last_updated' => '2015-07-16 06:56:37',
			'uuid' => 'Lorem ipsum dolor sit amet',
			'date_created' => 1,
			'last_updated' => 1,
			'leave_uuid_intact' => 1,
			'_date_created' => '2015-07-16 06:56:37',
			'know_about_green_leaf_antimalarials' => 'Lorem ipsum dolor sit amet',
			'do_you_prescribe_without_green_leaf' => 'Lorem ipsum dolor sit amet',
			'do_you_know_moh_guidelines' => 'Lorem ipsum dolor sit amet',
			'know_what_severe_malaria_is' => 'Lorem ipsum dolor sit amet',
			'due_date' => 1
		),
	);

}
