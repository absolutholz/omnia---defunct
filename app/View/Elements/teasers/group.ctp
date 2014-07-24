<?php 
	$this->extend('teasers/base');
	
	// variables
	$collection_id = $collection['Collection']['id'];

	// blocks
	$this->assign('tsr-type', 'group');
	$this->assign('tsr-url', Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "grouped_by_id" => $grouped_by_id, "open_group_id" => $group_id)));
	$this->assign('tsr-title', 'View ' . $group_id);
	$this->assign('tsr-maintext', $group_id);
	
	$this->assign('tsr-actions', '');
	$this->start('tsr-actions');
	$this->end(); ?>

<em><?php echo count($group); ?></em>
