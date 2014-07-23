<?php
class Participation extends AppModel {
    public $validate = array(
		"collection_id" => array(
			"valid_entry" => array(
				"rule" => 'notEmpty',
				"required" => 'create',
				"message" => 'A collection ID is required for participation.'
			),
			"valid_user" => array(
				"rule" => array('validateCollection'),
				"required" => 'create',
				"message" => 'A valid collection is required for participation.'
			)
		),
		"user_id" => array(
			"valid_entry" => array(
				"rule" => 'notEmpty',
				"required" => 'create',
				"message" => 'A user ID is required for participation.'
			),
			"valid_user" => array(
				"rule" => array('validateUser'),
				"required" => 'create',
				"message" => 'A valid user is required for participation.'
			)
		),
		"visibility_id" => array(
			"rule" => 'notEmpty',
			"required" => true,
			"message" => 'Participation must have a visibility of either public or private.'
		)
    );
	
	public $belongsTo = array('Collection', 'User', 'Visibility');
	public $hasMany = array('Completion');

	/**
	 * Check if the collection's user id is valid.
	 */
	public function validateUser ($check) {
		return $this->User->isValid($check['user_id']);
	}

	/**
	 * Check if the collection's id is valid.
	 */
	public function validateCollection ($check) {
		return $this->Collection->isValid($check['collection_id']);
	}
		
	// READ

	/**
	 * Get a list of collections (w/ participation information) for the passed user id.
	 */
	public function getByUserID ($user_id) {
		$collections = Cache::read('participations_user_' . $user_id, 'long');

        if (empty($collections)) {
			$collections = $this->cacheByUserID($user_id);
		}
		
		return $collections;
	}
	/**
	 * Cache the list of collections for the passed user id.
	 */
	public function cacheByUserID ($user_id) {
		$collections = $this->find('all', array("contain" => 'Collection', "conditions" => array("Participation.user_id" => $user_id)));
		$collections = Hash::combine($collections, '{n}.Collection.id', '{n}');

		Cache::write('participations_user_' . $user_id, $collections, 'long');
		
		return $collections;
	}
	
	/**
	 * Get a list of collections (w/ participation information) for the passed collection id.
	 */
	public function getByCollectionID ($collection_id) {
		$participants = Cache::read('participtions_' . $collection_id, 'long');

        if (empty($participants)) {
			$participants = $this->cacheByCollectionID($collection_id);
		}
		
		return $participants;
	}	
	/**
	 * Cache the list of collections for the passed collection id.
	 */	
	public function cacheByCollectionID ($collection_id) {
		$participants = $this->find('all', array("contain" => 'User', "conditions" => array("Participation.collection_id" => $collection_id, "Participation.visibility_id" => 1)));
		$participants = Hash::combine($participants, '{n}.User.id', '{n}');
		
		Cache::write('participtions_' . $collection_id, $participants, 'long');
		
		return $participants;
	}	
	
	/**
	 * Reset the cache for a certain collection and for all users of it.
	 */
	public function recacheByCollectionID ($collection_id) {
		// reset the participation cache for this collection
		$participations = $this->cacheByCollectionID($collection_id);
		// reset the participation cache for any users
				var_dump($participations);

		foreach ($participations as $participation) {
			$this->cacheByUserID($participation['Participation']['user_id']);
		}
	}

	public function createParticipation ($collection_id, $user_id, $visibility_id = 1) {
		if ($this->User->isValid($user_id) && $this->Collection->isValid($collection_id)) {
			$this->create();
			$this->save(array("collection_id" => $collection_id, "user_id" => $user_id, "visibility_id" => $visibility_id));
			
			$this->cacheByUserID($user_id);
			$this->cacheByCollectionID($collection_id);

			return true;
		
		} else {
			// TODO: THROW EXCEPTION
		}
		
		return false;
	}
	
	public function deleteParticipation ($participation_id) {
		if (!$participation_id) {
			throw new NotFoundException(__('Invalid Participation'));
		} else {

			$this->delete($participation_id, true);

		}
	}
	
	// READ
	public function getByID ($participation_id) {
		return $this->find('first', array("conditions" => array("Participation.id" => $participation_id)));
	}
	
}
?>