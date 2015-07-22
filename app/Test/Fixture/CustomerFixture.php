<?php
/**
 * CustomerFixture
 *
 */
class CustomerFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'customer';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'opening_hours' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'turn_over' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'description_of_outlet_location' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'key_whole_saler_contact' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'outlet_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'split' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'visible_equipment' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'number_of_customers_per_day' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => true),
		'segment_score' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'lng' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'children_under5yrs_per_day' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => true),
		'key_whole_saler_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'restock_frequency' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => true),
		'outlet_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'has_sister_branch' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'outlet_size' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'majority_source_of_supply' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'number_of_employees' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => true),
		'number_of_products' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'lat' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'_date_last_updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'uuid' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'date_created' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'last_updated' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'leave_uuid_intact' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'_date_created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'deleted' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'type_of_licence' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'date_outlet_opened' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'trading_center' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'building_structure' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'opening_hours' => 'Lorem ipsum dolor sit amet',
			'turn_over' => 1,
			'is_active' => 1,
			'description_of_outlet_location' => 'Lorem ipsum dolor sit amet',
			'key_whole_saler_contact' => 'Lorem ipsum dolor sit amet',
			'outlet_name' => 'Lorem ipsum dolor sit amet',
			'split' => 'Lorem ipsum dolor sit amet',
			'visible_equipment' => 'Lorem ipsum dolor sit amet',
			'number_of_customers_per_day' => 1,
			'segment_score' => 1,
			'lng' => 1,
			'children_under5yrs_per_day' => 1,
			'key_whole_saler_name' => 'Lorem ipsum dolor sit amet',
			'restock_frequency' => 1,
			'outlet_type' => 'Lorem ipsum dolor sit amet',
			'has_sister_branch' => 1,
			'outlet_size' => 'Lorem ipsum dolor sit amet',
			'majority_source_of_supply' => 'Lorem ipsum dolor sit amet',
			'number_of_employees' => 1,
			'number_of_products' => 'Lorem ipsum dolor sit amet',
			'lat' => 1,
			'_date_last_updated' => '2015-07-16 06:56:31',
			'uuid' => 'Lorem ipsum dolor sit amet',
			'date_created' => 1,
			'last_updated' => 1,
			'leave_uuid_intact' => 1,
			'_date_created' => '2015-07-16 06:56:31',
			'deleted' => 1,
			'type_of_licence' => 'Lorem ipsum dolor sit amet',
			'date_outlet_opened' => 1,
			'trading_center' => 'Lorem ipsum dolor sit amet',
			'building_structure' => 'Lorem ipsum dolor sit amet'
		),
	);

}
