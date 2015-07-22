<?php
App::uses('Territory', 'Model');

/**
 * Territory Test Case
 *
 */
class TerritoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.territory'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Territory = ClassRegistry::init('Territory');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Territory);

		parent::tearDown();
	}

}
