<?php
class Field extends AppModel {
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        ),
        'field_type_id' => array(
            'rule' => 'notEmpty'
        ),
        'collection_id' => array(
            'rule' => 'notEmpty'
        )
    );
	
	public $belongsTo = array('Collection', 'FieldType');
	public $hasMany = array(
		"CollectionItemField" => array(
			"dependent" => true
		)
	);
	
	public function getGroupFieldsByCollectionID ($collection_id) {
		$groups = Cache::read('groups_' . $collection_id);

        if (empty($groups)) {
			$groups = $this->cacheGroupFieldsByCollectionID($collection_id);
		}
		
		return $groups;
	}
	
	public function cacheGroupFieldsByCollectionID ($collection_id) {
		$groups = $this->find('all', array("contain" => false, "conditions" => array("collection_id" => $collection_id, "is_groupable" => true)));		
		$groups = Hash::combine($groups, '{n}.Field.id', '{n}.Field');

		Cache::write('groups_' . $collection_id, $groups);
		
		return $groups;
	}
}
?>