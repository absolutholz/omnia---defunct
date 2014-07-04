<?php
class CollectionItemsController extends AppController {
	public $uses = array('CollectionItem', 'Participation', 'CompletionStatus', 'CollectionItemStatus');
	
	public function view ($collection_id, $collection_item_id) {
		$collection = $this->CollectionItem->Collection->getByID($collection_id);
				
		//$collection_item = $collection['CollectionItems'][$collection_item_id];
		
		//if (!$collection_item) {
			$collection_item = $this->CollectionItem->getByID($collection_item_id, $collection_id);
		//}
			
//		$completions = $this->Participation->getByCollectionID($collection_id);
		
		$this->set('collection', $collection);
		$this->set('collection_item', $collection_item);
		$this->set('status_collection_item', $this->CollectionItemStatus->getAll());
		$this->set('status_completion', $this->CompletionStatus->getAll());
	}
	
	public function add ($collection_id) {
		// Check if the current user is allowed to perform this action.
		if ($collection['Collection']['visibility_id'] == 2 || !$this->Auth->user() || $this->Auth->user('id') != $collection['Collection']['user_id']) {
			// collection is private && current user is not logged in, or not the creator, or not an admin
			return $this->redirect(array("controller" => 'collections', "action" => 'index'));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$collection = $this->CollectionItem->createCollectionItem($this->request->data);
			return $this->redirect(array("action" => 'view', "collection_id" => $collection['CollectionItem']['collection_id'], "collection_item_id" => $collection['CollectionItem']['id']));
		}

		$this->set('user_id', $this->Auth->user('id'));
		$this->set('collection', $this->CollectionItem->Collection->getByID($collection_id));
	}
	
	public function edit ($collection_id, $collection_item_id) {
		$collection = $this->CollectionItem->Collection->getByID($collection_id);

		// Check if the current user is allowed to perform this action.
		if ($collection['Collection']['visibility_id'] == 2 || !$this->Auth->user() || $this->Auth->user('id') != $collection['Collection']['user_id']) {
			// collection is private && current user is not logged in, or not the creator, or not an admin
			return $this->redirect(array("controller" => 'collections', "action" => 'index'));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$collection = $this->CollectionItem->updateCollectionItem($this->request->data);
			return $this->redirect(array("action" => 'view', "collection_id" => $collection['CollectionItem']['collection_id'], "collection_item_id" => $collection['CollectionItem']['id']));
		}

		$collection = $this->CollectionItem->Collection->getByID($collection_id);
		$collection_item = $collection['CollectionItems'][$collection_item_id];
		
		if (!$collection_item) {
			$collection_item = $this->CollectionItem->getByID($collection_item_id, $collection_id);
		}

		$this->request->data = $collection_item;
		$this->set('collection', $collection);
		$this->set('collection_item', $collection_item);
	}
	
	public function delete ($collection_id, $collection_item_id) {
		if ($this->CollectionItem->deleteCollectionItem($collection_item_id)) {
			return $this->redirect(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id));
		}
	}
	
	public function available ($collection_id, $collection_item_id, $status_id) {
		$this->CollectionItem->setAvailability($collection_item_id, $status_id);
		return $this->redirect(array("action" => 'view', "collection_id" => $collection_id, "collection_item_id" => $collection_item_id));
	}
	
	public function complete ($collection_id, $collection_item_id, $status_id) {
		$this->CollectionItem->Completion->setCompleteness($collection_id, $collection_item_id, $status_id, $this->Auth->user('id'));
		return $this->redirect(array("action" => 'view', "collection_id" => $collection_id, "collection_item_id" => $collection_item_id));
	}
}
?>