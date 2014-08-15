<?php
class FieldType extends AppModel {
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        ),
        'description' => array(
            'rule' => 'notEmpty'
        )
    );
	
	public $hasMany = 'Field';
}
?>