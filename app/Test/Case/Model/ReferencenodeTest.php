<?php
App::uses('Referencenode', 'Model');

/**
 * Referencenode Test Case
 *
 */
class ReferencenodeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.referencenode'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Referencenode = ClassRegistry::init('Referencenode');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Referencenode);

		parent::tearDown();
	}

}
