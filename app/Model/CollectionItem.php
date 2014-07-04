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
	
	public $belongsTo = array('Collection', 'CollectionItemStatus', 'User');
	public $hasMany = array("CollectionItemField" => array("dependent" => true), 'Completion');

	// HELPERS
	private $_relations = array('CollectionItemStatus', 'CollectionItemField', 'Completion');
	
	// READ
	/**
	 * Get a single Collection Item by it's ID
	 */
	public function getByID ($collection_item_id/*, $collection_id*/) {
	Cache::clear();
		$collection_item = Cache::read('collection_item_' . $collection_item_id);

        if (empty($collection_item)) {
			$collection_item = $this->cacheByID($collection_item_id/*, $collection_id*/);
		}

		return $collection_item;
	}	
	public function cacheByID ($collection_item_id/*, $collection_id = null*/) {
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
		
		if (!empty($collection_item)) {
			Cache::write('collection_item_' . $collection_item_id, $collection_item, 'long');
		} else {
			Cache::delete('collection_item_' . $collection_item_id);
		}
		
		$event = new CakeEvent('CollectionItem.change.on.' . $collection_item_id, $this, array());
		CakeEventManager::instance()->dispatch($event);
		
		return $collection_item;
	}
	
	/**
	 * Get a list of Collection Items by their Collection ID
	 */
	public function getListByCollectionID ($collection_id) {
		$collection_items = Cache::read('collection_items_' . $collection_id);

        if (empty($collection_items)) {
			$collection_items = $this->cacheListByCollectionID($collection_id);
		}

		return $collection_items;
	}
	public function cacheListByCollectionID ($collection_id) {
		// TODO: join CompletionStatus.name and Field.name
		$collection_items = $this->find('all', array("contain" => $this->_relations, "conditions" => array("CollectionItem.collection_id" => $collection_id), "order" => 'CollectionItem.name'));
		$collection_items = Hash::combine($collection_items, '{n}.CollectionItem.id', '{n}');
		
		foreach ($collection_items as $key => $collection_item) {
			$collection_items[$key]['CollectionItemField'] = Hash::combine($collection_item['CollectionItemField'], '{n}.field_id', '{n}');
		}

		if (!empty($collection_items)) {
			Cache::write('collection_items_' . $collection_id, $collection_items, 'long');
		} else {
			Cache::delete('collection_items_' . $collection_id);
		}
		
		$event = new CakeEvent('CollectionItemList.change.on.' . $collection_id, $this, array());
		CakeEventManager::instance()->dispatch($event);
		
		return $collection_items;
	}
	
	// CREATE
	public function createCollectionItem ($data) {
		if ($data && $this->User->isValid($data['CollectionItem']['user_id'])) {
		
			$this->create();
				//var_dump($data);die;
            if ($this->saveAll($data)) {
				$collection_item_id = $this->id;	
				$data['CollectionItem']['id'] = $collection_item_id;
				
				$this->cacheListByCollectionID($data['CollectionItem']['collection_id']);
				$this->cacheByID($data['CollectionItem']['id']);
				
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
		
			$this->cacheListByCollectionID($data['CollectionItem']['collection_id']);
			$this->cacheByID($data['CollectionItem']['id']);
		
			return $data	;
		}
		
		return false;
	}
	
	// DELETE
	public function deleteCollectionItem ($collection_item_id) {
		if ($this->delete($collection_item_id, true)) {
			
			$this->cacheListByCollectionID($data['CollectionItem']['collection_id']);
			$this->cacheByID($data['CollectionItem']['id']);
			
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