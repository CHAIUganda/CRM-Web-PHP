<?php
App::uses('AppModel', 'Model');
/**
 * Detailermalariastock Model
 *
 * @property Malariadetail $Malariadetail
 * @property Detailer $Detailer
 */
class Detailermalariastock extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'detailermalariastock';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Malariadetail' => array(
			'className' => 'Malariadetail',
			'foreignKey' => 'malariadetail_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Detailer' => array(
			'className' => 'Detailer',
			'foreignKey' => 'detailer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
