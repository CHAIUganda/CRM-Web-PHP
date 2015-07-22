<?php
App::uses('Salescall', 'Model');

/**
 * Salescall Test Case
 *
 */
class SalescallTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.salescall',
		'app.client_ref'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Salescall = ClassRegistry::init('Salescall');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Salescall);

		parent::tearDown();
	}

}
