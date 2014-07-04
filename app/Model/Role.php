<?php
App::uses('AuthComponent', 'Controller/Component');

class Role extends AppModel {
	public $hasOne = array('User');
}
?>