<?php
class CollectionsController extends AppController {
/*
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');
	public $uses = array('Collection', 'Participation', 'CollectionItem');
*/
	public $uses = array('User', 'Collection', 'Participation', 'CollectionItem', 'Completion');

	public function search () {
		$search_term = $this->request->query['search_term'];
		$this->set('search_term', $search_term);
		$this->set('collections', $this->Collection->search($search_term));
		$this->set('collection_items', $this->Collection->CollectionItem->search($search_term));
	}
	
    public function index () {
		$user_id = $this->Auth->user('id');

		if (isset($user_id)) {
			$participations = $this->Collection->Participation->getByUserID($user_id);
			
			$this->set('participations', $participations);
			$this->set('collections', $this->Collection->getFilteredCollections($participations));
			
		} else {
		
			$this->set('participations', null);
			$this->set('collections', $this->Collection->getPublicCollections());
		}
	}
	
    public function view ($collection_id) {
		$user_id = $this->Auth->user('id');

		$collection = $this->Collection->getByID($collection_id);
		$participation = null;

		if (isset($collection['Participations'][$user_id])) {
			$participation = $collection['Participations'][$user_id];
			unset($collection['Participations'][$user_id]);
			$this->set('completions', $this->Completion->getCompletionsByParticipationID($participation['Participation']['id']));
		}
		$this->set('collection', $collection);
		$this->set('participation', $participation);
	}	
	
	public function add () {
        if ($this->request->is('post')) {
			$collection = $this->Collection->createCollection($this->request->data);
			return $this->redirect(array("action" => 'edit', "collection_id" => $collection['Collection']['id']));
/*		
            if ($this->Collection->save($this->request->data)) {							
                $this->Session->setFlash(__('Your Collection has been saved.'));
                return $this->redirect(array("controller" => 'collections', "action" => 'edit', $this->Collection->id));
            }
            $this->Session->setFlash(__('Unable to add your Collection.'));
*/
		}
		
		$this->set('user_id', $this->Auth->user('id'));
		$this->set('visibilities', $this->Collection->Visibility->getVisibilities());
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

	// notify creator if not creator modifying
	public function edit ($collection_id = null) {
		if (!$collection_id) {
			throw new NotFoundException(__('Invalid Collection'));
			return $this->redirect(array("action" => 'add'));

		} else {

			if ($this->request->is('post') || $this->request->is('put')) {

				$this->Collection->id = $collection_id;
				
				if ($this->Collection->save($this->request->data)) {
					$this->Session->setFlash(__('Your Collection has been updated.'));
					$this->Collection->updateCollection($collection_id);
					return $this->redirect(array("action" => 'view', $collection_id));
				}
				
				$this->Session->setFlash(__('Unable to update your Collection.'));
				
			} else {

				$collection = $this->Collection->getByID($collection_id);

				$this->request->data = $collection;
				$this->set('visibilities', $this->Collection->Visibility->getVisibilities());
				$this->set('collection', $collection);
			/*
				if (!$collection) {
					throw new NotFoundException(__('Invalid Collection'));
				}
			*/
			}
		}
	}


	// TODO: delete if user is also creator, if not send notification to creator asking to delete
	public function delete($collection_id) {
					//$participation_count = $this->Collection->Participation->find('count', array("conditions" => array("collection_id" => $collection_id, "NOT" => array("Participation.user_id" => $this->Auth->user('id')))));

		if (!$collection_id) {
			throw new NotFoundException(__('Invalid Collection'));
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
				
/*




			
					if ($collection['Collection']['user_id'] == $this->Auth->user('id')) {
						// collection CREATOR = can delete, but warn other participants
						$participations = $this->Collection->Participation->getByCollectionID($collection_id);
						if ($participations) {
						}
						
					} else {
						// NOT collection CREATOR = can't do it, but can send a message to creator
					}
				}

			} else {
				// NOT LOGGED IN = can't do it, but can send a message to creator
			}
				
			
			
*/
/*		
		// find the collection to be deleted (information purposes and to check the creator user_id
		$collection = $this->Collection->find('first', array("contain" => false, "conditions" => array("id" => $id)));
		
		// check if the user trying to delete the collection is the one who created it
		if ($collection['Collection']['user_id'] == $this->Auth->user('id')) {
		
			if ($this->Collection->delete($id, true)) {
				$this->Session->setFlash(__('The Collection <em>%s</em> has been deleted for all users.', h($collection['name'])));
				return $this->redirect(array("action" => 'index', "controller" => 'Participations'));
			}
			
		}
*/
//		}
	}

	
	
	
	/*
	public function view ($collection_id) {
		$collection = $this->CollectionItem->Collection->find('first', array("contain" => false, "conditions" => array("Collection.id" => $collection_id)))['Collection'];
		$collection_items = $this->CollectionItem->find('all', array("contain" => 'CollectionItemField', "conditions" => array("collection_id" => $collection_id), "order" => 'name'));
		
		$this->set('collection', $collection);
		$this->set('collection_items', $collection_items);
	}
		
    public function view($id) {
        if (!$id) {
            throw new NotFoundException(__('Invalid Collection'));
        }

        $collection = $this->Collection->findById($id);
        if (!$collection) {
            throw new NotFoundException(__('Invalid Collection'));
        }
        $this->set('collection', $collection);
		$this->set('fields', $this->Collection->Field->find('all', array("conditions" => array("collection_id" => $id))));
    }
	
	public function details ($collection_id) {
		$collection = $this->CollectionItem->Collection->find('first', array("contain" => false, "conditions" => array("Collection.id" => $collection_id)))['Collection'];

		$this->set('collection', $collection);
	}
	*/
}
?>