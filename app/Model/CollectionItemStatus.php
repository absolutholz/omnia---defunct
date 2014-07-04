<?php
class CollectionItemStatus extends AppModel {
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
	
	public $hasOne = array('CollectionItem');

	public function getAll () {
		$status = Cache::read('status_item', 'long');

        if (empty($status)) {
			$status = $this->cacheAll();
		}
		
		return $status;
	}	
	public function cacheAll () {
		$status = $this->find('list', array("contain" => false));

		Cache::write('status_item', $status, 'long');
		
		return $status;
	}
}
?>