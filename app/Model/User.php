<?php
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
	const DETAIL_LEVEL_BASIC = 1;
	const DETAIL_LEVEL_EXTENDED = 2;

    public $validate = array(
        "username" => array(
			"input" => array(
				"rule" => 'notEmpty',
				"required" => true
			),
			"input2" => array(
				"rule" => 'alphaNumeric'
			),
			"uniqueness" => array(
				"rule" => 'isUnique'
			)
        ),
        'password' => array(
            'rule' => 'notEmpty'
        ),
		'visibility_id' => array(
            'rule' => 'notEmpty'
        ),
		'role_id' => array(
            'rule' => 'notEmpty'
        ),
		'email' => 'email'
    );
	
	public $hasMany = 'Participation';
	public $belongsTo = array('Role', 'Visibility');

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}	
	
	public function isValid ($user_id) {
		if (!$user_id) {
			//throw new NotFoundException(__('Invalid User ID'));
			return false;
			
		} else if (!isset($this->getAllUsers()[$user_id])) {
			//throw new NotFoundException(__('Invalid User'));
			return false;
		}

		return true;
	}
	
	/* INDIVIDUAL USER INFO */
		
	/**
	 * Get information for a user by the passed ID. 
	 * Depending on the passed detail level, return either only the basic information (User, Role and Visibility) or extended (basic + collections).
	 */
	public function getByID ($user_id, $detail_level = self::DETAIL_LEVEL_BASIC) {
		$user = $this->getAllUsers()[$user_id];
		
		if ($detail_level == self::DETAIL_LEVEL_EXTENDED) {
		// TODO: user rights for participations and collections
			$user = array_merge($user, array("Participations" => $this->Participation->find('all', array("contain" => array('Collection'), "conditions" => array("Participation.user_id" => $user_id, "Participation.visibility_id" => 1, "Collection.visibility_id" => 1)))));
		}
		
		return $user;
	}

	/* USER LISTS */
	
	/**
	 * Get a list of users minus the passed it. 
	 * Depending on the passed role either all users or only public users.
	 */
	public function getFilteredUsers ($user_id, $user_role = 1) {
		if (empty($user_role) || $user_role != 3) {
			$users = $this->getPublicUsers();
		} else {
			$users = $this->getAllUsers();
		}
		
		unset($users[$user_id]);

		return $users;
	}
	
	/**
	 * Get a list of only publically visibile users.
	 */
	public function getPublicUsers () {
		$users = Cache::read('users_public', 'long');

        if (empty($users)) {
			$users = $this->cachePublicUsers();
		}
		
		return $users;
	}
	public function cachePublicUsers () {
		$users = $this->find('all', array("contain" => array('Role', 'Visibility'), "conditions" => array("User.visibility_id" => 1)));
		$users = Hash::combine($users, '{n}.User.id', '{n}');
		// TODO: expand with participation/collection/completion information
		
		Cache::write('users_public', $users, 'long');
		
		return $users;
	}
	
	/**
	 * Get a list of all Users in the database.
	 */
	public function getAllUsers () {
		$users = Cache::read('users_all', 'long');

        if (empty($users)) {
			$users = $this->cacheAllUsers();
		}
		
		return $users;
	}
	
	public function cacheAllUsers () {
		$users = $this->find('all', array("contain" => array('Role', 'Visibility')));
		$users = Hash::combine($users, '{n}.User.id', '{n}');
		// TODO: expand with participation/collection/completion information
		
		Cache::write('users_all', $users, 'long');
		
		return $users;
	}
}
?>