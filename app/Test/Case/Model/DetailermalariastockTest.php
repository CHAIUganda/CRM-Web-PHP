<?php
App::uses('Detailermalariastock', 'Model');

/**
 * Detailermalariastock Test Case
 *
 */
class DetailermalariastockTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.detailermalariastock',
		'app.malariadetail',
		'app.detailer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Detailermalariastock = ClassRegistry::init('Detailermalariastock');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Detailermalariastock);

		parent::tearDown();
	}

}
