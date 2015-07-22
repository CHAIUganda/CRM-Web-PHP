<?php
/**
 * DirectsaleFixture
 *
 */
class DirectsaleFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'directsale';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'date_of_sale' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'government_approval' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'completion_date' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'recommendation_next_step' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'due_date' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'lng' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'lat' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'client_ref_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => true, 'key' => 'index'),
		'_date_last_updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'uuid' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'date_created' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'last_updated' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'leave_uuid_intact' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'_date_created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'origin' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'actual_date_created' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'index_foreignkey_directsale_client_ref' => array('column' => 'client_ref_id', 'unique' => 0)
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
			'date_of_sale' => 1,
			'government_approval' => 1,
			'type' => 'Lorem ipsum dolor sit amet',
			'completion_date' => 1,
			'recommendation_next_step' => 'Lorem ipsum dolor sit amet',
			'due_date' => 1,
			'description' => 'Lorem ipsum dolor sit amet',
			'status' => 'Lorem ipsum dolor sit amet',
			'lng' => 1,
			'lat' => 1,
			'client_ref_id' => 1,
			'_date_last_updated' => '2015-07-16 06:56:35',
			'uuid' => 'Lorem ipsum dolor sit amet',
			'date_created' => 1,
			'last_updated' => 1,
			'leave_uuid_intact' => 1,
			'_date_created' => '2015-07-16 06:56:35',
			'origin' => 'Lorem ipsum dolor sit amet',
			'actual_date_created' => 1
		),
	);

}
