<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array(
        'Session',
		'Cookie',
        'Auth' => array(
            //'loginRedirect' => array('controller' => 'participations', 'action' => 'index'),
            //'logoutRedirect' => array('controller' => 'participations', 'action' => 'index')
        )
    );

    public function beforeFilter() {
        $this->Auth->allow('index', 'view', 'search');
		
		// set cookie options
		$this->Cookie->httpOnly = true;

		if (!$this->Auth->loggedIn() && $this->Cookie->read('rememberMe')) {
			$cookie = $this->Cookie->read('rememberMe');

			$this->loadModel('User'); // If the User model is not loaded already
			$user = $this->User->find('first', array(
				'conditions' => array(
					'User.username' => $cookie['username'],
					'User.password' => $cookie['password']
				)
			));

			if ($user && !$this->Auth->login($user['User'])) {
				$this->redirect('/users/logout'); // destroy session & cookie
			}
		}
		
		$this->set('authUser', $this->Auth->user());
    }
}
