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

	// ***** COLLECTIONS *****
	// / - list of public collections & participations for logged in user
	Router::connect('/', array("controller" => 'collections', "action" => 'index'));
	// /search - search collections, collection items, & collection item fields
	Router::connect('/search', array("controller" => 'collections', "action" => 'search'));
	// /login - user login
	Router::connect('/login', array("controller" => 'users', "action" => 'login'));
	// /logout - user logout
	Router::connect('/logout', array("controller" => 'users', "action" => 'logout'));

	// ***** USERS *****	
	// /users - list of publicly visible users
	Router::connect('/users', array("controller" => 'users', "action" => 'index'));
	// /users/search - search within users
	Router::connect('/users/search', array("controller" => 'users', "action" => 'search'));		

	// /users/new
	Router::connect('/users/new', array("controller" => 'users', "action" => 'create'));
	// /users/[user_id] - user details and list of user's publicly visible participations
	Router::connect('/users/:user_id', array("controller" => 'users', "action" => 'view'), array("pass" => array('user_id')));
	// /users/[user_id]/edit
	Router::connect('/users/:user_id/edit', array("controller" => 'users', "action" => 'edit'), array("pass" => array('user_id')));
	// /users/[user_id]/delete
	Router::connect('/users/:user_id/delete', array("controller" => 'users', "action" => 'edit'), array("pass" => array('user_id')));

/*
	// /users/[user_id]/[collection_id] - view user's participation for particular collection
	Router::connect('/users/:user_id/:collection_id', array("controller" => 'collections', "action" => 'view'), array("pass" => array('collection_id', 'user_id')));
	// /users/[user_id]/[collection_id]/group/[grouping_id]
	Router::connect('/users/:user_id/:collection_id/group/:grouping_id', array("controller" => 'collections', "action" => 'view', "grouping_id" => null), array("pass" => array('collection_id', 'grouping_id', 'user_id')));
	// /users/[user_id]/[collection_id]/group/[grouping_id]/[open_group_id]
	Router::connect('/users/:user_id/:collection_id/group/:grouping_id/:open_group_id', array("controller" => 'collections', "action" => 'view'), array("pass" => array('collection_id', 'grouping_id', 'open_group_id', 'user_id')));
	// /users/[user_id]/[collection_id]/[collection_item_id] - view user's participation for particular collection item
	Router::connect('/users/:user_id/:collection_id/:collection_item_id', array("controller" => 'collection_items', "action" => 'view'), array("pass" => array('collection_id', 'collection_item_id', 'user_id')));
*/

	// /new - create new collection
	Router::connect('/new', array("controller" => 'collections', "action" => 'create'));

	// /[collection_id] - collection details and list of (ungrouped) collection items
	Router::connect('/:collection_id', array("controller" => 'collections', "action" => 'view'), array("pass" => array('collection_id')));
	// /[collection_id]/group/[grouping_id]
	Router::connect('/:collection_id/groupby/:grouping_id', array("controller" => 'collections', "action" => 'view'), array("pass" => array('collection_id', 'grouping_id')));
	// /[collection_id]/group/[grouping_id]/[open_group_id]
	Router::connect('/:collection_id/groupby/:grouping_id/:open_group_id', array("controller" => 'collections', "action" => 'view'), array("pass" => array('collection_id', 'grouping_id', 'open_group_id')));
	
	// /[collection_id]/search - search within specific collection
	Router::connect('/:collection_id/search', array("controller" => 'collections', "action" => 'search'), array("pass" => array('collection_id')));
	
	// /[collection_id]/edit
	Router::connect('/:collection_id/edit', array("controller" => 'collections', "action" => 'edit'), array("pass" => array('collection_id')));
	// ***** COLLECTION FIELDS *****
	// /[collection_id]/edit/fields
	// /[collection_id]/edit/fields/new
	Router::connect('/:collection_id/edit/fields/new', array("controller" => 'fields', "action" => 'create'), array("pass" => array('collection_id')));
	// /[collection_id]/edit/fields/[field_id]/edit
	Router::connect('/:collection_id/edit/fields/:field_id/edit', array("controller" => 'fields', "action" => 'edit'), array("pass" => array('collection_id', 'field_id')));
	// /[collection_id]/edit/fields/[field_id]/delete
	Router::connect('/:collection_id/edit/fields/:field_id/delete', array("controller" => 'fields', "action" => 'delete'), array("pass" => array('collection_id', 'field_id')));

	// /[collection_id]/delete
	Router::connect('/:collection_id/delete', array("controller" => 'collections', "action" => 'delete'), array("pass" => array('collection_id')));
	
	// /[collection_id]/participate
	Router::connect('/:collection_id/participate', array("controller" => 'collections', "action" => 'participate'), array("pass" => array('collection_id')));
	// /[collection_id]/unparticipate/:participation_id - participation_id is optional ... if omitted, use the logged in user and collection_id
	Router::connect('/:collection_id/unparticipate/:participation_id', array("controller" => 'collections', "action" => 'unparticipate', "participation_id" => null), array("pass" => array('collection_id', 'participation_id')));
	
	// ***** COLLECTION ITEM *****
	// /[collection_id]/addto - create collection item and add it to collection
	Router::connect('/:collection_id/addto', array("controller" => 'collection_items', "action" => 'create'), array("pass" => array('collection_id')));
	
	// /[collection_id]/[collection_item_id] - collection item details and list of collection item fields
	Router::connect('/:collection_id/:collection_item_id', array("controller" => 'collection_items', "action" => 'view'), array("pass" => array('collection_id', 'collection_item_id')));
	// /[collection_id]/[collection_item_id]/edit
	Router::connect('/:collection_id/:collection_item_id/edit', array("controller" => 'collection_items', "action" => 'edit'), array("pass" => array('collection_id', 'collection_item_id')));
	// /[collection_id]/[collection_item_id]/delete
	Router::connect('/:collection_id/:collection_item_id/delete', array("controller" => 'collection_items', "action" => 'delete'), array("pass" => array('collection_id', 'collection_item_id')));
	// /[collection_id]/[collection_item_id]/available/[status_id] - set the availability of the collection item
	Router::connect('/:collection_id/:collection_item_id/complete/:status_id', array("controller" => 'collection_items', "action" => 'complete', 'status_id' => 3), array("pass" => array('collection_id', 'collection_item_id', 'status_id')));
	// /[collection_id]/[collection_item_id]/complete/[status_id] - set the completion status of the collection item
	Router::connect('/:collection_id/:collection_item_id/available/:status_id', array("controller" => 'collection_items', "action" => 'available', 'status_id' => 1), array("pass" => array('collection_id', 'collection_item_id', 'status_id')));
	
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
