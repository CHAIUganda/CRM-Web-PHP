<?php
App::uses('AppModel', 'Model');
/**
 * Company Model
 *
 * @property Advert $Advert
 */
class DetailerTask extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	/**
	 * If we want to create() or update() we need to specify the fields
	 * available. We use the same array keys as we do with CakeSchema, eg.
	 * fixtures and schema migrations.
	 */
    public $schema = array(
        'id' => array(
            'type' => 'integer',
            'null' => false,
            'key' => 'primary',
            'length' => 11,
        ),
        'name' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255,
        ),
        'message' => array(
            'type' => 'text',
            'null' => true,
        ),
	    'type' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'knowledgeAbtOrsAndUsage' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'completionDate' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'recommendationNextStep' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'diarrheaEffectsOnBody' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'description' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'heardAboutDiarrheaTreatmentInChildren' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'whatYouKnowAbtDiarrhea' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'diarrheaPatientsInFacility' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'recommendationLevel' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'knowledgeAbtZincAndUsage' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'isAdhock' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'status' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'howDidYouHear' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'otherWaysHowYouHeard' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'pointOfsaleMaterial' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'whyNotUseAntibiotics' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'wkt' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'lng' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'lat' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    '_dateLastUpdated' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'uuid' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'dateCreated' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'lastUpdated' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    'leaveUuidIntact' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        ),
	    '_dateCreated' => array(
	            'type' => 'string',
	            'null' => true,
	            'length' => 255,
	        )
	    );
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Advert' => array(
			'className' => 'Advert',
			'foreignKey' => 'company_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'company_id',
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
