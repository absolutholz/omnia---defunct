<?php
class UsersController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    public $components = array('Session');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('login', 'create'); // Letting users register themselves
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {

				// Remember Me => http://www.bayesianconspiracy.com/2013/01/cakephp-2-remember-me-feature/
				if ($this->request->data['User']['rememberMe'] == 1) {
					// After what time frame should the cookie expire
					$cookieTime = "12 months"; // You can do e.g: 1 week, 17 weeks, 14 days
				 
					// remove "remember me checkbox"
					unset($this->request->data['User']['rememberMe']);
								 
					// hash the user's password
					$this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
								 
					// write the cookie
					$this->Cookie->write('rememberMe', $this->request->data['User'], true, $cookieTime);
				}

				return $this->redirect($this->Auth->redirectUrl());
			}
			//$this->Session->setFlash(__('Invalid username or password, try again'));
		}
	}

	public function logout() {
	    //$this->Session->setFlash("You've been logged out");
		$this->Cookie->delete('rememberMe');
		$this->Auth->logout();
		//$this->redirect($this->Auth->logout());
		$this->redirect(array("controller" => 'collections', "action" => 'index'));
	
		//return $this->redirect($this->Auth->logout());
	}

    public function index() {
		$user_id = $this->Auth->user('id');

		if (isset($user_id)) {
			$this->set('user_current', $this->User->getByID($user_id));
		}
		
		$this->set('users', $this->User->getFilteredUsers($user_id, $this->Auth->user('role_id')));
    }
	
	public function view ($user_id) {
		$model = $this->User;
		
		$user = $model->getUser($user_id, $model::DETAIL_LEVEL_EXTENDED);
		
		// if the desired user is not publicly visible, check the current user's role
		if ($user['User']['visibility_id'] != 1 && $this->Auth->user('role_id') != 3) {
			$this->redirect(array("action" => 'index'));
		}
		
		$this->set('user', $user);
	/*
		if (empty($user_id)) {
			$this->redirect(array("action" => 'read', "user_id" => $this->Auth->user('id')));
		}
		
		$this->set('user', $this->User->find('first', array("contain" => array('Role'), "conditions" => array("User.id" => $user_id))));
	*/
	}

    public function add () {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Your User has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to add your User.'));
        }

		$this->User->Role->contain();
		$this->set('roles', $this->User->Role->find('all'));
    }
	
	public function edit ($user_id = null) {
		// IF no user_id in url OR user not logged in 
		// IF NOT logged in user is the same as passed user_id OR is admin
		$bUserAllowedToChange = $user_id == $this->Auth->user('id') || $this->Auth->user('role_id') == 3;
		if (!$user_id || !$this->Auth->user() || !$bUserAllowedToChange) {
			//throw new NotFoundException(__('Invalid User'));
			return $this->redirect(array("action" => 'index'));
		}
/*
		$user = $this->User->findById($user_id);
		if (!$user) {
			throw new NotFoundException(__('Invalid User'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->id = $user_id;
*/

		if ($this->request->data) {
			$data = $this->request->data;
			
			if (empty($data['User']['password'])) {
			//var_dump($data);
				unset($data['User']['password']);
			//var_dump($data);die;
			}
			
			if ($this->User->save($data)) {
//				$this->Session->setFlash(__('Your User has been updated.'));
				return $this->redirect(array("action" => 'read', "user_id" => $data['User']['id']));
			}
		}
/*
			$this->Session->setFlash(__('Unable to update your User.'));
		}

		if (!$this->request->data) {
			$this->request->data = $user;
		}
*/

		if (!$this->request->data) {
			$this->User->Role->contain();
			$this->set('roles', $this->User->Role->find('all'));
			$this->User->contain();
			$user = $this->User->findById($user_id);
			$user['User']['password'] = null;
			$this->set('user', $user);
			$this->request->data = $user;
		}
	 }
}
?>