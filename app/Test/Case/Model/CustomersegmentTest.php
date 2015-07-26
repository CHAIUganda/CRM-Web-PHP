<?php
App::uses('Customersegment', 'Model');

/**
 * Customersegment Test Case
 *
 */
class CustomersegmentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.customersegment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Customersegment = ClassRegistry::init('Customersegment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Customersegment);

		parent::tearDown();
	}

}
