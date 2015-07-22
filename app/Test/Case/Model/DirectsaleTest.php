<?php
App::uses('Directsale', 'Model');

/**
 * Directsale Test Case
 *
 */
class DirectsaleTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.directsale',
		'app.client_ref'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Directsale = ClassRegistry::init('Directsale');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Directsale);

		parent::tearDown();
	}

}
