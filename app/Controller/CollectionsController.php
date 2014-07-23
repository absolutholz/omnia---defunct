<?php
class CollectionsController extends AppController {
	public $uses = array('User', 'Field', 'Collection', 'Participation', 'CollectionItem', 'Completion', 'CollectionItemField');

	/**
	 * Search
	 */
	public function search () {
		$search_term = $this->request->query['search_term'];
		$this->set('search_term', $search_term);
		$this->set('collections', $this->Collection->search($search_term));
		$this->set('collection_items', $this->Collection->CollectionItem->search($search_term));
	}

	/**
	 * The Homepage (Collection List Page)
	 * 
	 * Show all public collections if the user is not logged in, OR if logged in, the collections that the user 
	 * is participating in and only those public collections that they are not.
	 */
    public function index () {
		$user_id = $this->Auth->user('id');

		//  if the user is logged in, separate the collections he is participating in from the other public ones
		if (isset($user_id)) {
			// this returns a list of participations arranged by collection_id
			$participations = $this->Collection->Participation->getByUserID($user_id);
			
			$this->set('participations', $participations);
			$this->set('collections', $this->Collection->getAllPublicFiltered($participations));
			
		} else {
		
			$this->set('participations', null);
			$this->set('collections', $this->Collection->getAllPublic());
		}
	}
	
	/**
	 * Collection Detail Page
	 *
	 * Show the collection items for the passed collection_id.
	 * TODO: grouping
	 */
    public function view ($collection_id) {
		$user_id = $this->Auth->user('id');

		$collection = $this->Collection->getByID($collection_id);

		$groups = $this->Field->getGroupsByCollectionID($collection_id);

		$grouped = array();
		$collection_items = $collection['CollectionItems'];
		foreach ($collection_items as $item) {
			foreach ($groups as $key => $iter) {
				if (!isset($groups[$key]['Groups'])) {
					$groups[$key]['Groups'] = array();
				}

				if (isset($item['CollectionItemField'][$key])) {
					$group_key = $item['CollectionItemField'][$key]['value'];
				} else {
					$group_key = 'UNGROUPED';
				}

				if (!isset($groups[$key]['Groups'][$group_key])) {
					$groups[$key]['Groups'][$group_key] = array();
				}

				array_push($groups[$key]['Groups'][$group_key], $item);
			}
		}

		foreach ($groups as $key => $iter) {
			$tmp = $iter['Groups'];
			ksort($tmp);
			$groups[$key]['Groups'] = $tmp;
		}
		
		$participation = null;

		if (isset($collection['Participations'][$user_id])) {
			$participation = $collection['Participations'][$user_id];
			unset($collection['Participations'][$user_id]);
			$this->set('completions', $this->Completion->getCompletionsByParticipationID($participation['Participation']['id']));
		}
		$this->set('collection', $collection);
		$this->set('participation', $participation);
	}	
	
	/**
	 * Create a new collection
	 */
	public function add () {
        if ($this->request->is('post')) {

			$collection = $this->Collection->createCollection($this->request->data);

			if (!isset($collection['errors'])) {
                $this->Session->setFlash(__('Your Collection has been created.'));
				return $this->redirect(array("action" => 'edit', "collection_id" => $collection['Collection']['id']));
			} 

            $this->Session->setFlash(__('Unable to create your Collection.'));
			$this->set('errors', $collection['errors']);
		}
		
		$this->set('user_id', $this->Auth->user('id'));
		$this->set('visibilities', $this->Collection->Visibility->getVisibilities());
   }
   
   	/**
	 * Edit an existing collection
	 *
	 * notify creator if not creator modifying
	 */
	public function edit ($collection_id = null) {
		if (!$collection_id) {
			throw new NotFoundException(__('Invalid Collection'));
			return $this->redirect(array("action" => 'add'));
		} 		

		if ($this->request->is('post') || $this->request->is('put')) {

			$this->Collection->id = $collection_id;
			$collection = $this->Collection->updateCollection($this->request->data);
			
			if (!isset($collection['errors'])) {
				$this->Session->setFlash(__('Your Collection has been updated.'));
				return $this->redirect(array("action" => 'view', $collection_id));
			}
            
			$this->Session->setFlash(__('Unable to update your Collection.'));
			$this->set('errors', $collection['errors']);
		}

		$collection = $this->Collection->getByID($collection_id);

		$this->request->data = $collection;
		$this->set('visibilities', $this->Collection->Visibility->getVisibilities());
		$this->set('collection', $collection);
	}

	/**
	 * Delete a collection
	 */
	// TODO: delete if user is also creator, if not send notification to creator asking to delete
	public function delete($collection_id) {
		//$participation_count = $this->Collection->Participation->find('count', array("conditions" => array("collection_id" => $collection_id, "NOT" => array("Participation.user_id" => $this->Auth->user('id')))));

		if (!$collection_id) {
			throw new NotFoundException(__('Invalid Collection'));
			return $this->redirect(array("action" => 'index'));

		} else {

			$collection = $this->Collection->getByID($collection_id);
			$participants = $this->Collection->Participation->getByCollectionID($collection_id);

			if (($this->Auth->user())) {
				// LOGGED IN

				if ($this->Auth->user('role_id') == 3) {
					// ADMIN = can delete, but warn creator and other participants
					
					// DELETE
// TODO: delete before sending message in this case?
					if ($this->Collection->deleteCollection($collection_id)) {
// TODO: temporary redirect
						return $this->redirect(array("action" => 'index'));
					}
					
					// exclude the logged in user (with admin rights) from the list of participants to notify
					unset($participants[$this->Auth->user('id')]);
					
					// is the creator of the collection participating?
					$creator_id = $collection['Collection']['user_id'];
					if (!isset($participants[$creator_id])) {
						// NO => add the creator to the list of participants to receive a MESSAGE
						$participants = array_merge($participants, $this->User->getByID($creator_id));
					}

					if (count($participants > 0)) {
						// SEND MESSAGE to participants
					}
					
					// DONE

				} else {
					// NOT ADMIN	

					$creator_id = $collection['Collection']['user_id'];
					if ($creator_id == $this->Auth->user('id')) {
						// CREATOR

						// DELETE
// TODO: delete before sending message in this case?
						if ($this->Collection->deleteCollection($collection_id)) {
// TODO: temporary redirect	
							return $this->redirect(array("action" => 'index'));
						}
					
						// exclude the creator (user initiating action) from the list of participants to notify
						unset($participants[$creator_id]);

						// DELETE & SEND MESSAGE to participants
						if (count($participants > 0)) {
							// SEND MESSAGE to participants
						}
					
						// DONE

					} else {
						// NOT CREATOR
						// SEND MESSAGE to creator					
					}
				}
			}
		}

		return $this->redirect(array("action" => 'index'));
	}
	
	public function participate ($collection_id) {
		$this->Participation->createParticipation($collection_id, $this->Auth->user('id'));
		return $this->redirect(array("action" => 'view', "collection_id" => $collection_id));
	}

	public function unparticipate ($collection_id, $participation_id = null) {
		$participation = null;
		
		if ($participation_id) {
			$participation = $this->Participation->getByID($participation_id);
		}
		
		if (!$participation) {
			$participation = $this->Participation->getByUserID($this->Auth->user('id'))[$collection_id];
		}
				
		if ($this->Auth->user('id') == $participation['Participation']['user_id']) {
			// logged in USER = participation USER					
			$this->Participation->deleteParticipation($participation_id);
		
		} else {
			if ($this->Auth->user('role_id') == 3) {
				// logged in USER = ADMIN
				$this->Participation->deleteParticipation($participation_id);
						
				// SEND MESSAGE to 
			}
		}
	
		return $this->redirect(array("action" => 'index'));
	}
}
?>