<?php
App::uses('AppModel', 'Model');
/**
 * Dbchangeset Model
 *
 * @property Change $Change
 */
class Dbchangeset extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'dbchangeset';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Change' => array(
			'className' => 'Change',
			'foreignKey' => 'change_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
