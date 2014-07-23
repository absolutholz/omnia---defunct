<?php
class Completion extends AppModel {
    public $validate = array(
        'participation_id' => array(
            'rule' => 'notEmpty'
        ),
        'collection_item_id' => array(
            'rule' => 'notEmpty'
        ),
        'completion_item_id' => array(
            'rule' => 'notEmpty'
        )
    );
	
	public $belongsTo = array('CollectionItem', 'CompletionStatus', "Participation" => array(
            "counterCache" => 'completion_count',
            "counterScope" => array(
              "Completion.completion_status_id" => 3
            )
	));
	
	public function getCompletionsByParticipationID ($participation_id) {
		$completions = Cache::read('completions_' . $participation_id);

        if (empty($completions)) {
			$completions = $this->cacheCompletionsByParticipationID($participation_id);
		}
		return $completions;
	}
	
	public function cacheCompletionsByParticipationID ($participation_id) {
		$completions = $this->find('all', array("contain" => 'CompletionStatus', "conditions" => array("participation_id" => $participation_id)));		
		$completions = Hash::combine($completions, '{n}.Completion.collection_item_id', '{n}');

		Cache::write('completions_' . $participation_id, $completions);
		
		return $completions;
	}
	
	public function setCompleteness ($collection_id, $collection_item_id, $status_id, $user_id) {
		if (!is_array($user_id)) {
			
			$participation = $this->Participation->getByCollectionID($collection_id)[$user_id]['Participation'];
			
			if (!$participation) {
				// THROW EXCEPTION
				return false;
			}
			
			$completion_id = $this->getCompletionsByParticipationID($participation['id'])[$collection_item_id]['Completion']['id'];
			if ($completion_id) {
//var_dump('update', $collection_id, $collection_item_id, $status_id, $user_id, $completion_id);die;
				return $this->save(array("id" => $completion_id, "completion_status_id" => $status_id));
			} else {
//var_dump('create', $collection_id, $collection_item_id, $status_id, $user_id, $completion_id);die;
				$this->create();
				return $this->save(array("participation_id" => $participation_id, "collection_item_id" => $collection_id, "completion_status_id	" => $status_id));
			}
			
		} else {
			
			$results = array();
			foreach ($user_id as $alue) {
				array_push($this->setCompleteness($collection_id, $collection_item_id, $status_id, $value));
			}
			return $results;
			
		}
	}
}
?>