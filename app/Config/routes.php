<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	//Router::connect('/pages/*', array("controller" => 'pages', "action" => 'display'));

	//Router::connect('/', array("controller" => 'participations', "action" => 'index'));

	// USERS
	Router::connect('/login', array("controller" => 'users', "action" => 'login'));
	Router::connect('/logout', array("controller" => 'users', "action" => 'logout'));
	Router::connect('/users', array("controller" => 'users', "action" => 'index'));
	Router::connect('/users/add', array("controller" => 'users', "action" => 'add'));
	Router::connect('/users/:user_id/edit', array("controller" => 'users', "action" => 'edit'), array("pass" => array('user_id')));
	Router::connect('/users/:user_id/delete', array("controller" => 'users', "action" => 'edit'), array("pass" => array('user_id')));
	Router::connect('/users/:user_id', array("controller" => 'users', "action" => 'view', "user_id" => 0), array("pass" => array('user_id')));

	// SEARCH
	Router::connect('/search', array("controller" => 'collections', "action" => 'search'));
	//Router::connect('/:collection_id/search/:search_term', array("controller" => 'collections', "action" => 'search'), array("pass" => array('search_string', 'collection_id')));
	
	// COLLECTIONS
	Router::connect('/', array("controller" => 'collections', "action" => 'index'));
	Router::connect('/add', array("controller" => 'collections', "action" => 'add'));
	Router::connect('/:collection_id', array("controller" => 'collections', "action" => 'view'), array("pass" => array('collection_id')));
	Router::connect('/:collection_id/edit', array("controller" => 'collections', "action" => 'edit'), array("pass" => array('collection_id')));
	Router::connect('/:collection_id/delete', array("controller" => 'collections', "action" => 'delete'), array("pass" => array('collection_id')));
	Router::connect('/:collection_id/participate', array("controller" => 'collections', "action" => 'participate'), array("pass" => array('collection_id')));
	Router::connect('/:collection_id/unparticipate/:participation_id', array("controller" => 'collections', "action" => 'unparticipate'), array("pass" => array('collection_id', 'participation_id')));
	Router::connect('/:collection_id/grouped/:grouped_by_id', array("controller" => 'collections', "action" => 'view', "grouped_by_id" => null), array("pass" => array('collection_id', 'grouped_by_id')));
	Router::connect('/:collection_id/grouped/:grouped_by_id/:open_group_id', array("controller" => 'collections', "action" => 'view', "grouped_by_id" => null, "open_group_id" => null), array("pass" => array('collection_id', 'grouped_by_id', 'open_group_id')));
	
	// COLLECTION ITEMS
	Router::connect('/:collection_id/add', array("controller" => 'collection_items', "action" => 'add'), array("pass" => array('collection_id')));
	Router::connect('/:collection_id/:collection_item_id', array("controller" => 'collection_items', "action" => 'view'), array("pass" => array('collection_id', 'collection_item_id')));
	Router::connect('/:collection_id/:collection_item_id/edit', array("controller" => 'collection_items', "action" => 'edit'), array("pass" => array('collection_id', 'collection_item_id')));
	Router::connect('/:collection_id/:collection_item_id/delete', array("controller" => 'collection_items', "action" => 'delete'), array("pass" => array('collection_id', 'collection_item_id')));
	Router::connect('/:collection_id/:collection_item_id/complete/:status_id', array("controller" => 'collection_items', "action" => 'complete', 'status_id' => 0), array("pass" => array('collection_id', 'collection_item_id', 'status_id')));
	Router::connect('/:collection_id/:collection_item_id/available/:status_id', array("controller" => 'collection_items', "action" => 'available', 'status_id' => 0), array("pass" => array('collection_id', 'collection_item_id', 'status_id')));
		
	// COLLECTION FIELDS
	Router::connect('/:collection_id/fields/add', array("controller" => 'fields', "action" => 'add'), array("pass" => array('collection_id')));
	Router::connect('/:collection_id/fields/:field_id/edit', array("controller" => 'fields', "action" => 'edit'), array("pass" => array('collection_id', 'field_id')));
	Router::connect('/:collection_id/fields/:field_id/delete', array("controller" => 'fields', "action" => 'delete'), array("pass" => array('collection_id', 'field_id')));
	
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
