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
	
	public function getGroupsByCollectionID ($collection_id) {
		$groups = Cache::read('groups_' . $collection_id, 'long');

        if (empty($groups)) {
			$groups = $this->_cacheGroupsByCollectionID($collection_id);
		}
		
		return $groups;
	}
	
	private function _cacheGroupsByCollectionID ($collection_id) {
		$groups = $this->find('all', array("contain" => false, "conditions" => array("collection_id" => $collection_id, "is_groupable" => true)));		
		$groups = Hash::combine($groups, '{n}.Field.id', '{n}');

		Cache::write('groups_' . $collection_id, $groups, 'long');
		
		$event = new CakeEvent('Group.change.on.' . $collection_id, $this, array());
		CakeEventManager::instance()->dispatch($event);
		
		return $groups;
	}
}
?>