<?php
class CollectionItemField extends AppModel {
    public $validate = array(
        'collection_id' => array(
            'rule' => 'notEmpty'
        ),
        'field_id' => array(
            'rule' => 'notEmpty'
        )
    );
	
	public $belongsTo = array('Field', 'CollectionItem');
}
?>