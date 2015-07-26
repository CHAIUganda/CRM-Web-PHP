<?php
App::uses('Detailertask', 'Model');

/**
 * Detailertask Test Case
 *
 */
class DetailertaskTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.detailertask',
		'app.client_ref'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Detailertask = ClassRegistry::init('Detailertask');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Detailertask);

		parent::tearDown();
	}

}
