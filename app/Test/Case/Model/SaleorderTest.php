<?php
App::uses('Saleorder', 'Model');

/**
 * Saleorder Test Case
 *
 */
class SaleorderTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.saleorder',
		'app.client_ref'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Saleorder = ClassRegistry::init('Saleorder');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Saleorder);

		parent::tearDown();
	}

}
