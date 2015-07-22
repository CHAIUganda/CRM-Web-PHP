<?php
App::uses('AppModel', 'Model');
/**
 * Sale Model
 *
 * @property ClientRef $ClientRef
 */
class Sale extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'sale';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ClientRef' => array(
			'className' => 'ClientRef',
			'foreignKey' => 'client_ref_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
