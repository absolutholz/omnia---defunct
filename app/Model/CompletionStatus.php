<?php
class CompletionStatus extends AppModel {
    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );
	
	public $hasMany = 'Completion';
	
	public function getAll () {
		$status = Cache::read('status_completion', 'long');

        if (empty($status)) {
			$status = $this->cacheAll();
		}
		
		return $status;
	}	
	public function cacheAll () {
		$status = $this->find('list', array("contain" => false));

		Cache::write('status_completion', $status, 'long');
		
		return $status;
	}
}
?>