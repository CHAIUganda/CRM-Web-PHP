<?php
App::uses('Requestmap', 'Model');

/**
 * Requestmap Test Case
 *
 */
class RequestmapTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.requestmap'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Requestmap = ClassRegistry::init('Requestmap');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Requestmap);

		parent::tearDown();
	}

}
