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
		$collections = $this->find('all', array("contain" => 'Collection', "conditions" => array("Participation.user_id" => $user_id)));
		$collections = Hash::combine($collections, '{n}.Collection.id', '{n}');
		
		return $collections;
	}
	
	/**
	 * Get a list of collections (w/ participation information) for the passed collection id.
	 */
	public function getByCollectionID ($collection_id) {
		$participants = $this->find('all', array("contain" => 'User', "conditions" => array("Participation.collection_id" => $collection_id, "Participation.visibility_id" => 1)));
		$participants = Hash::combine($participants, '{n}.User.id', '{n}');
		
		return $participants;
	}	
	
	/**
	 * Participate
	 */
	public function createParticipation ($collection_id, $user_id, $visibility_id = 1) {
		if (count($this->find('list', array("conditions" => array("Participation.collection_id" => $collection_id, "Participation.user_id" => $user_id)))) == 0) { 
	
			$this->create();
			$participation = $this->save(array("collection_id" => $collection_id, "user_id" => $user_id, "visibility_id" => $visibility_id));
			if (!$participation) {
			
				return array("errors" => $this->validationErrors);
			
			}
		
		} else {
		
			return array("errors" => array("You are already participating in this collection"));
			
		}
		
		return true;
	}
	
	/**
	 * Unparticipate
	 */
	public function deleteParticipation ($participation_id) {
		if (!$participation_id) {
			throw new NotFoundException(__('Invalid Participation'));
		} else {

			$this->delete($participation_id, true);

		}
	}
}
?>