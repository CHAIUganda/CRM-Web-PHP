<?php
App::uses('Productgroup', 'Model');

/**
 * Productgroup Test Case
 *
 */
class ProductgroupTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.productgroup'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Productgroup = ClassRegistry::init('Productgroup');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Productgroup);

		parent::tearDown();
	}

}
