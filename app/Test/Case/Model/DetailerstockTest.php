<?php
App::uses('Detailerstock', 'Model');

/**
 * Detailerstock Test Case
 *
 */
class DetailerstockTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.detailerstock'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Detailerstock = ClassRegistry::init('Detailerstock');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Detailerstock);

		parent::tearDown();
	}

}
