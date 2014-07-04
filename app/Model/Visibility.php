<?php
App::uses('AuthComponent', 'Controller/Component');

class Visibility extends AppModel {
	public function getVisibilities () {
		$visibilities = Cache::read('visibilities', 'long');

        if (empty($visibilities)) {
			$visibilities = $this->cacheVisibilities();
		}
		
		return $visibilities;
	}
	
	public function cacheVisibilities () {
		$visibilities = $this->find('all', array("contain" => false));
		
		Cache::write('visibilities', $visibilities);
		
		return $visibilities;
	}
}
?>