<?php
class CollectionItem extends AppModel {

    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        ),
        'collection_id' => array(
            'rule' => 'notEmpty'
        ),
        'collection_item_status_id' => array(
            'rule' => 'notEmpty'
        ),
        'user_id' => array(
            'rule' => 'notEmpty'
        )
    );
	
	public $belongsTo = array("Collection" => array(
            "counterCache" => 'collection_item_count',
            "counterScope" => array(
              "CollectionItem.collection_item_status_id" => 1
            )
        ), 'CollectionItemStatus', 'User');
	public $hasMany = array("CollectionItemField" => array("dependent" => true), 'Completion');

	// HELPERS
	private $_relations = array('CollectionItemStatus', 'CollectionItemField', 'Completion');
	
	// READ
	/**
	 * Get a single Collection Item by it's ID
	 */
	public function getByID ($collection_item_id/*, $collection_id*/) {
		// GET collection_item, collection_item_fields, completions
		// merge with collection on collection_item.collection_id and with participations on completion.participation_id 
	
		//if (!$collection_id) {
			$collection_item = $this->find('first', array("contain" => $this->_relations, "conditions" => array("CollectionItem.id" => $collection_item_id)/*, "joins" => array(
				array('table' => 'participations',
					'alias' => 'Participations',
					'type' => 'inner',
					'conditions' => array(
						'participation_id = Participation.id'
					)
				),
				array('table' => 'users',
					'alias' => 'Users',
					'type' => 'inner',
					'conditions' => array(
						'Participations.user_id = User.id'
					)
				)
			)*/));
			$collection_item['CollectionItemField'] = Hash::combine($collection_item['CollectionItemField'], '{n}.field_id', '{n}');
		//} else {
			//$collection_item = $this->getListByCollectionID($collection_id)[$collection_item_id];
		//}

		return $collection_item;
	}	
	
	/**
	 * Get a list of Collection Items by their Collection ID
	 */
	public function getListByCollectionID ($collection_id, $groups = null, $group_id = null) {
		// TODO: join CompletionStatus.name and Field.name
		$collection_items = $this->find('all', array("contain" => $this->_relations, "conditions" => array("CollectionItem.collection_id" => $collection_id), "order" => 'CollectionItem.name'));
		$collection_items = Hash::combine($collection_items, '{n}.CollectionItem.id', '{n}');
		
		foreach ($collection_items as $key => $collection_item) {
			$collection_items[$key]['CollectionItemField'] = Hash::combine($collection_item['CollectionItemField'], '{n}.field_id', '{n}');
		}
		
		// if this collection has groups
		if ($groups) {
			// grouped collection_items array
			$grouped = array();
			//$collection_items = $collection['CollectionItems'];
			
			// iterate through the collection items
			foreach ($collection_items as $item) {
			
				// iterate through the collection groups (z.B. key = Region|City)
				foreach ($groups as $key => $iter) {
				
					// if this group doesn't exist yet, create it
					if (!isset($groups[$key]['Groups'])) {
						$groups[$key]['Groups'] = array();
					}

					if (isset($item['CollectionItemField'][$key])) {
					
						// if this collection item has a field with the current group key, it should be placed in a grouped array named by the value of the field
						$group_key = $item['CollectionItemField'][$key]['value'];
						
					} else {

						// if the collection item does not have a field with the current group key, it should be placed in a ungrouped array
						$group_key = 'UNGROUPED';
					}

					// if an array with the group key value does not yet exist, create it
					if (!isset($groups[$key]['Groups'][$group_key])) {
						$groups[$key]['Groups'][$group_key] = array();
					}

					array_push($groups[$key]['Groups'][$group_key], $item);
				}
			}

			// sort the entries in the groups
			foreach ($groups as $key => $iter) {
				$tmp = $iter['Groups'];
				ksort($tmp);
				$groups[$key]['Groups'] = $tmp;
			}
		}
		
		//var_dump($collection_items);var_dump($groups);die;

		return array("Ungrouped" => $collection_items, "Grouped" => $groups);
	}
	
	// CREATE
	public function createCollectionItem ($data) {
		if ($data && $this->User->isValid($data['CollectionItem']['user_id'])) {
		
			$this->create();
				//var_dump($data);die;
            if ($this->saveAll($data)) {
				$collection_item_id = $this->id;	
				$data['CollectionItem']['id'] = $collection_item_id;
								
				return $data;
			}

		} else {
			// TODO: THROW EXCEPTION		
		}
		
		return false;
	}
	
	// EDIT
	public function updateCollectionItem ($data) {
		if ($this->saveAll($data)) {
		
			return $data	;
		}
		
		return false;
	}
	
	// DELETE
	public function deleteCollectionItem ($collection_item_id) {
		if ($this->delete($collection_item_id, true)) {
			
			return true;
		}

		return false;
	}
	
	public function setAvailability ($collection_item_id, $status_id) {
		$this->save(array("id" => $collection_item_id, "collection_item_status_id" => $status_id));
	}
	
	public function search ($term) {
		$term = '%' . strtolower($term) . '%';
		$matches = array();
		$matched_ids = $this->find('list', array("contain" => false, "conditions" => array("or" => array("LOWER(CollectionItem.name) LIKE" => $term, "LOWER(CollectionItem.notes) LIKE" => $term ))));
		
		foreach ($matched_ids as $key => $name) {
			$matches[$key] = $this->getByID($key);
		}

		return $matches;
	}
}
?>