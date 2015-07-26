<?php
App::uses('Stockinfo', 'Model');

/**
 * Stockinfo Test Case
 *
 */
class StockinfoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.stockinfo',
		'app.client_ref'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Stockinfo = ClassRegistry::init('Stockinfo');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Stockinfo);

		parent::tearDown();
	}

}
