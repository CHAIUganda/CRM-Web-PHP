<?php
App::uses('Subcounty', 'Model');

/**
 * Subcounty Test Case
 *
 */
class SubcountyTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.subcounty'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Subcounty = ClassRegistry::init('Subcounty');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Subcounty);

		parent::tearDown();
	}

}
