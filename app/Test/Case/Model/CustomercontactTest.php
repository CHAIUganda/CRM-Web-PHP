<?php
App::uses('Customercontact', 'Model');

/**
 * Customercontact Test Case
 *
 */
class CustomercontactTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.customercontact'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Customercontact = ClassRegistry::init('Customercontact');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Customercontact);

		parent::tearDown();
	}

}
