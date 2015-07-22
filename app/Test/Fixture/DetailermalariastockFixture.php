<?php
/**
 * DetailermalariastockFixture
 *
 */
class DetailermalariastockFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'detailermalariastock';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'malariadetail_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => true, 'key' => 'index'),
		'detailer_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => true, 'key' => 'index'),
		'brand' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'stock_level' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'category' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'selling_price' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'buying_price' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'_date_last_updated' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'uuid' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 191, 'collate' => 'utf8mb4_unicode_ci', 'charset' => 'utf8mb4'),
		'date_created' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'last_updated' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'leave_uuid_intact' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'_date_created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'index_foreignkey_detailermalariastock_malariadetail' => array('column' => 'malariadetail_id', 'unique' => 0),
			'index_foreignkey_detailermalariastock_detailer' => array('column' => 'detailer_id', 'unique' => 0)
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
			'malariadetail_id' => 1,
			'detailer_id' => 1,
			'brand' => 'Lorem ipsum dolor sit amet',
			'stock_level' => 1,
			'category' => 'Lorem ipsum dolor sit amet',
			'selling_price' => 1,
			'buying_price' => 1,
			'_date_last_updated' => '2015-07-16 06:56:33',
			'uuid' => 'Lorem ipsum dolor sit amet',
			'date_created' => 1,
			'last_updated' => 1,
			'leave_uuid_intact' => 1,
			'_date_created' => '2015-07-16 06:56:33'
		),
	);

}
