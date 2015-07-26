<?php
App::uses('Directsalewithstock', 'Model');

/**
 * Directsalewithstock Test Case
 *
 */
class DirectsalewithstockTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.directsalewithstock',
		'app.client_ref'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Directsalewithstock = ClassRegistry::init('Directsalewithstock');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Directsalewithstock);

		parent::tearDown();
	}

}
