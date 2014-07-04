<?php
class Collection extends AppModel {
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        ),
        'description' => array(
            'rule' => 'notEmpty'
        ),
        'user_id' => array(
            'rule' => 'notEmpty'
        ),
        'visibility_id' => array(
            'rule' => 'notEmpty'
        )
    );
	
	public $belongsTo = array('User', 'Visibility');
	public $hasMany = array("Participation" => array("dependent" => true), "Field" => array("dependent" => true), "CollectionItem" => array("dependent" => true));

	// READ
	/**
	 * Get a single collection by it's ID.
	 * returns Collection Array with Collection Information, Collection Items, Participations (including the current user), Fields and Groups
	 */
	public function getByID ($collection_id) {
	Cache::clear();
		$collection = Cache::read('collection_' . $collection_id, 'long');

        if (empty($collection)) {
			$collection = $this->cacheByID($collection_id);
		}
		
		// TODO: cache these values as part of the collection?
		$participations = $this->Participation->getByCollectionID($collection_id);
		$collection_items = $this->CollectionItem->getListByCollectionID($collection_id);
		$groups = $this->Field->getGroupFieldsByCollectionID($collection_id);

		$collection = array_merge($collection, array("Participations" => $participations), array("CollectionItems" => $collection_items), array("Groups" => $groups));
		
		return $collection;
	}
	public function cacheByID ($collection_id) {
		$collection = $this->find('first', array("contain" => 'Field', "conditions" => array("Collection.id" => $collection_id)));
		$collection['Field'] = Hash::combine($collection['Field'], '{n}.id', '{n}');

		if ($collection) {
			Cache::write('collection_' . $collection_id, $collection, 'long');
		} else {
			Cache::delete('collection_' . $collection_id, 'long');
		}
		
		$event = new CakeEvent('Collection.change.on.' . $collection_id, $this, array());
		CakeEventManager::instance()->dispatch($event);
		
		return $collection;
	}

	/**
	 * Filter the list of public collections by removing the passed array of collection IDs.
	 */
	public function getFilteredCollections ($collections_to_filter_out) {
		$collections = $this->getPublicCollections();
		
		foreach ($collections_to_filter_out as $key => $value) {
			unset($collections[$key]);
		}
		
		return $collections;
	}
	
	// PUBLIC COLLECTIONS
	/**
	 * Get a list of all public collections.
	 */
	public function getPublicCollections () {
		$collections = Cache::read('collections_public', 'long');

        if (empty($collections)) {
			$collections = $this->cachePublicCollections();
		}
		
		return $collections;
	}
	
	/**
	 * Cache the list of public collections.
	 */
	public function cachePublicCollections () {
		$collections = $this->find('all', array("contain" => false, "conditions" => array("visibility_id" => 1)));
		$collections = Hash::combine($collections, '{n}.Collection.id', '{n}.Collection');
		
		Cache::write('collections_public', $collections);
		
		return $collections;
	}
	
	// CREATE
	public function createCollection ($data) {
		if ($data && $this->User->isValid($data['Collection']['user_id'])) {
		
			$this->create();
            if ($this->save($data)) {

				$collection_id = $this->id;
				if ($this->Participation->createParticipation($this->id, $data['Collection']['user_id'], $data['Collection']['visibility_id'])) {
		
					$this->cachePublicCollections();
					$this->cacheCollection($collection_id);
		
					$data['Collection']['id'] = $collection_id;
					return $data;
					
				} else {
					// TODO: ROLLBACK COLLECTION CREATION
					return false;
				}
			}

		} else {
			// TODO: THROW EXCEPTION		
		}
		
		return false;
	}
	
	// EDIT
	public function collection_items ($data) {
		$this->cachePublicCollections();
		$this->cacheCollection($collection_id);
		
		return true;
	}
	
	// DELETE
	public function deleteCollection ($collection_id) {
		if ($this->delete($collection_id, true)) {
			$this->cachePublicCollections();
			$this->cacheCollection($collection_id);
			return true;
		}

		return false;
	}
	
	// HELPER
	public function isValid ($collection_id) {
		if (!$collection_id) {
			throw new NotFoundException(__('Invalid Collection ID'));
			return false;
			
		} else if (!$this->getByID($collection_id)) {
			throw new NotFoundException(__('Invalid Collection'));
			return false;
		}

		return true;
	}
	
	public function getAll () {
Cache::clear();
		$collections = Cache::read('collections_all', 'long');

		if (empty($collections)) {
			$collections = $this->_cacheAll();
		}
		
		return $collections;
	}
	private function _cacheAll () {		
		$collections = $this->find('all', array("contain" => array('User', 'CollectionItem')));
		$collections = Hash::combine($collections, '{n}.Collection.id', '{n}');
		
		Cache::write('collections_all', $collections);
		
		return $collections;
	}

	public function getAllPublic () {
	Cache::clear();
		$collections = Cache::read('collections_public', 'long');

		if (empty($collections)) {
			$collections = $this->_cacheAllPublic();
		}
		
		var_dump($collections);die;
		return $collections;
	}
	private function _cacheAllPublic () {
		$collections = $this->getAll();

		foreach ($collections as $key => $collection) {
			if ($collection['Collection']['visibility_id'] != 1) {
				unset($collections[$key]);
			}
		}
		
		Cache::write('collections_public', $collections);
		
		return $collections;
	}
	
	public function search ($term) {
		$term = '%' . strtolower($term) . '%';
		$matches = array();
		$matched_ids = $this->find('list', array("contain" => false, "conditions" => array("or" => array("LOWER(Collection.name) LIKE" => $term, "LOWER(Collection.description) LIKE" => $term ))));
		
		foreach ($matched_ids as $key => $name) {
			$matches[$key] = $this->getByID($key);
		}

		return $matches;
	}
}
?>