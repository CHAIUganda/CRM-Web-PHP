<?php
App::uses('Malariadetail', 'Model');

/**
 * Malariadetail Test Case
 *
 */
class MalariadetailTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.malariadetail',
		'app.client_ref',
		'app.detailermalariastock',
		'app.detailer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Malariadetail = ClassRegistry::init('Malariadetail');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Malariadetail);

		parent::tearDown();
	}

}
