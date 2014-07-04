<?php
class FieldsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');

    public function add ($collection_id) {
		if (!$collection_id) {
			throw new NotFoundException(__('Invalid Collection'));
		} else {

			if ($this->request->is('post')) {
				$this->Field->create();
				$this->request->data['Field']['collection_id'] = $collection_id;

				if ($this->Field->save($this->request->data)) {
					$this->Session->setFlash(__('Your Field has been saved.'));
					return $this->redirect(array("controller" => 'collections', "action" => 'edit', $collection_id));
				}
				$this->Session->setFlash(__('Unable to add your Field.'));
			}
			
			$this->set("collection_id", $collection_id);
			$this->set('field_types', $this->Field->FieldType->find('list'));
		}
   }
	
	public function edit ($collection_id = null, $field_id = null) {
		if (!$field_id) {
			throw new NotFoundException(__('Invalid Field'));
		} else if (!$collection_id) {
			throw new NotFoundException(__('Invalid Collection'));
		} else {

			if ($this->request->is('post') || $this->request->is('put')) {
				$this->Field->id = $field_id;
				$this->request->data['Field']['collection_id'] = $collection_id;

				if ($this->Field->save($this->request->data)) {
					$this->	Session->setFlash(__('Your Field has been updated.'));
					return $this->redirect(array("controller" => 'collections', "action" => 'edit', $collection_id));
				}

				$this->Session->setFlash(__('Unable to update your Field.'));
				
			} else {

				$field = $this->Field->find('first', array("contain" => 'FieldType', "conditions" => array("Field.id" => $field_id)));
				if (!$field) {
					throw new NotFoundException(__('Invalid Field'));
				}
		
				$this->request->data = $field;
				$this->set("collection_id", $collection_id);
				$this->set('field_types', $this->Field->FieldType->find('list'));
				
			}
		}
	}
	
	public function delete ($collection_id = null, $field_id = null) {
		if (!$field_id) {
			throw new NotFoundException(__('Invalid Field'));
		} else if (!$collection_id) {
			throw new NotFoundException(__('Invalid Collection'));
		} else {

			if ($this->request->is('post') || $this->request->is('put')) {
				if ($this->Field->delete($field_id)) {
				//	$this->	Session->setFlash(__('Your Field has been updated.'));
					return $this->redirect(array("controller" => 'collections', "action" => 'edit', $collection_id));
				}

				//$this->Session->setFlash(__('Unable to update your Field.'));
			}
			
			
		}
		/*if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}*/
/*
		if ($this->Field->delete($field_id)) {
			$this->Session->setFlash(__('The Field with id: %s has been deleted.', h($field_id)));
			return $this->redirect(array("controller" => 'collections', "action" => 'edit', $collection_id));
		}
*/
	}
}
?>