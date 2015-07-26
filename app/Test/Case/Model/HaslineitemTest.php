<?php
App::uses('Haslineitem', 'Model');

/**
 * Haslineitem Test Case
 *
 */
class HaslineitemTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.haslineitem',
		'app.client_ref'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Haslineitem = ClassRegistry::init('Haslineitem');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Haslineitem);

		parent::tearDown();
	}

}
