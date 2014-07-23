<?php
class Collection extends AppModel {
    public $validate = array(
		"id" => array(
			"required" => 'update'
		),
        "name" => array(
            "rule" => 'notEmpty',
			"required" => true,
			"message" => 'A name is required for a collection.'
        ),
        "description" => array(
            "rule" => 'notEmpty',
			"required" => true,
			"message" => 'A short description is required for a collection.'
        ),
        "user_id" => array(
            "valid_entry" => array(
				"rule" => 'notEmpty',
				"required" => 'create',
				"message" => 'A user ID is required for a collection.'
			),
			"valid_user" => array(
				"rule" => array('validateUser'),
				"required" => 'create',
				"message" => 'A valid user is required for a collection.'
			)
        ),
        "visibility_id" => array(
            "rule" => 'notEmpty',
			"required" => true,
			"message" => 'A collection must have a visibility of either public or private.'
        )
    );
	
	public $belongsTo = array('User', 'Visibility');
	public $hasMany = array("Participation" => array("dependent" => true), "Field" => array("dependent" => true), "CollectionItem" => array("dependent" => true));

	public function isValid ($collection_id) {
		if (!$collection_id) {
			//throw new NotFoundException(__('Invalid Collection ID'));
			return false;
			
//		} else if ($this->find()) {
//			//throw new NotFoundException(__('Invalid Collection'));
//			return false;
		}

		return true;
	}

	/**
	 * Check if the collection's user id is valid.
	 */
	public function validateUser ($check) {
		return $this->User->isValid($check['user_id']);
	}
	
	/**
	 * Recache all collection information (used on create, update and delete)
	 */
	public function recache ($collection_id, $user_id, $public = true) {
	var_dump($collection_id);
		// if the collection is public, reset the public LIST cache
		if ($public) {
			$this->cacheAllPublic();
		}
		// reset the DETAILed collection cache
		$this->cacheByID($collection_id);
		// reset participation LIST cache(s)
		$this->Participation->recacheByCollectionID($collection_id);
	}
	
	// READ

	/**
	 * Get a list of all public collections.
	 */
	public function getAllPublic () {
		$collections = Cache::read('collections_public', 'long');

        if (empty($collections)) {
			$collections = $this->cacheAllPublic();
		}
		
		return $collections;
	}
	/**
	 * Cache the list of public collections.
	 */
	public function cacheAllPublic () {
		$collections = $this->find('all', array("contain" => false, "conditions" => array("Collection.visibility_id" => 1)));
		$collections = Hash::combine($collections, '{n}.Collection.id', '{n}');

		Cache::write('collections_public', $collections, 'long');
		
		return $collections;
	}
				
	/**
	 * Filter the list of public collections by removing the passed array of collection IDs.
	 */
	public function getAllPublicFiltered ($collections_to_filter_out) {
		$collections = $this->getAllPublic();
		
		foreach ($collections_to_filter_out as $key => $value) {
			unset($collections[$key]);
		}
		
		return $collections;
	}

	/**
	 * Search the collections but not the collection items
	 * TODO: improve logic
	 */
	public function search ($term) {
		$term = '%' . strtolower($term) . '%';
		$matches = array("Collections" => array(), "Participations" => array());
		$matched_ids = $this->find('list', array("contain" => false, "conditions" => array("or" => array("LOWER(Collection.name) LIKE" => $term, "LOWER(Collection.description) LIKE" => $term))));
		
		foreach ($matched_ids as $key => $name) {
			$matches[$key] = $this->getByID($key);
		}

		return $matches;
	}

	/**
	 * Get a single collection by it's ID.
	 * returns Collection Array with Collection Information, Collection Items, Participations (including the current user), Fields and Groups
	 */
	public function getByID ($collection_id) {
		$collection = Cache::read('collection_' . $collection_id, 'long');

        if (empty($collection)) {
			$collection = $this->cacheByID($collection_id);
		}
		
		// TODO: cache these values as part of the collection?
		$participations = $this->Participation->getByCollectionID($collection_id);
		$collection_items = $this->CollectionItem->getListByCollectionID($collection_id);
		$groups = $this->Field->getGroupsByCollectionID($collection_id);
		
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
		
		return $collection;
	}

	// CREATE
	public function createCollection ($data) {
		$this->create();
		$save_collection = $this->save($data);
		if ($save_collection) {

			$collection_id = $this->id;
			$data['Collection']['id'] = $collection_id;
			
			// The user creating this collection will be assumed to also be a participant of it
			$save_participation = $this->Participation->createParticipation($this->id, $data['Collection']['user_id'], $data['Collection']['visibility_id']);
			if ($save_participation) {
			
				// new collection means that a public collection may be cached for listing, the details can be cached already, and a participation for the user
				$this->recache($collection_id, $data['Collection']['visibility_id'] == 1);
				return $data;
				
			} else {

				// could not save the participation
				return array("errors" => $this->validationErrors);
			}
			
		} else {
		
			// could not save the collection, return the errors (validation)
			return array("errors" => $this->validationErrors);
		}

		// error while trying to save the collection (validation)
		return $data;
	}
	
	// EDIT
	public function updateCollection ($data) {
		$save_collection = $this->save($data);
		if ($save_collection) {

			$this->recache($this->id, $data['Collection']['visibility_id'] == 1);

		} else {
		
			// could not save the collection, return the errors (validation)
			return array("errors" => $this->validationErrors);
		}
		
		return $data;
	}
	
	// DELETE
	public function deleteCollection ($collection_id) {
		if ($this->delete($collection_id, true)) {

			// TODO: find out if the collection is public in order to set the second argument
			$this->recache($collection_id, true);

			return true;
		}

		return false;
	}
}
?>