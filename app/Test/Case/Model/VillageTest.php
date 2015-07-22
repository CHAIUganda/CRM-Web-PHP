<?php
App::uses('Village', 'Model');

/**
 * Village Test Case
 *
 */
class VillageTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.village'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Village = ClassRegistry::init('Village');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Village);

		parent::tearDown();
	}

}
