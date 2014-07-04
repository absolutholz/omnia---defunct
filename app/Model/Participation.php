<?php
class Participation extends AppModel {
    public $validate = array(
        'user_id' => array(
            'rule' => 'notEmpty'
        ),
        'collection_id' => array(
            'rule' => 'notEmpty'
         ),
        'visibility_id' => array(
            'rule' => 'notEmpty'
       )
    );
	
	public $belongsTo = array('Collection', 'User', 'Visibility');
	public $hasMany = array('Completion');
	
	public function createParticipation ($collection_id, $user_id, $visibility_id = 1) {
		if ($this->User->isValid($user_id) && $this->Collection->isValid($collection_id)) {
			$this->create();
			$this->save(array("collection_id" => $collection_id, "user_id" => $user_id, "visibility_id" => $visibility_id));
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
	
	/**
	 *
	 */
	public function getByCollectionID ($collection_id) {
		$participants = Cache::read($this->_cache_key . '_byCollID_' . $collection_id, 'long');

        if (empty($participants)) {
			$participants = $this->cacheByCollectionID($collection_id);
		}
		
		return $participants;
	}	
	public function cacheByCollectionID ($collection_id) {
		$participants = $this->find('all', array("contain" => 'User', "conditions" => array("Participation.collection_id" => $collection_id, "Participation.visibility_id" => 1)));
		$participants = Hash::combine($participants, '{n}.User.id', '{n}');
		
		Cache::write($this->_cache_key . '_byCollID_' . $collection_id, $participants, 'long');
		
		return $participants;
	}
	
	/**
	 *
	 */
	public function getByUserID ($user_id) {
		$collections = Cache::read($this->_cache_key . '_byUserID_' . $user_id, 'long');

        if (empty($collections)) {
			$collections = $this->cacheByUserID($user_id);
		}
		
		return $collections;
	}
	public function cacheByUserID ($user_id) {
		$collections = $this->find('all', array("contain" => 'Collection', "conditions" => array("Participation.user_id" => $user_id)));
		$collections = Hash::combine($collections, '{n}.Collection.id', '{n}');
		
		Cache::write($this->_cache_key . '_byUserID_' . $user_id, $collections, 'long');
		
		return $collections;
	}
}
?>