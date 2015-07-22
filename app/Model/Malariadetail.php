<?php
App::uses('AppModel', 'Model');
/**
 * Malariadetail Model
 *
 * @property ClientRef $ClientRef
 * @property Detailermalariastock $Detailermalariastock
 */
class Malariadetail extends AppModel {


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

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Detailermalariastock' => array(
			'className' => 'Detailermalariastock',
			'foreignKey' => 'malariadetail_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
