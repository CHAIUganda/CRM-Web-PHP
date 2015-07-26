<?php
App::uses('Dbchangeset', 'Model');

/**
 * Dbchangeset Test Case
 *
 */
class DbchangesetTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.dbchangeset',
		'app.change'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Dbchangeset = ClassRegistry::init('Dbchangeset');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Dbchangeset);

		parent::tearDown();
	}

}
