<?php 
	$this->extend('teasers/base');
	
	// variables
	$collection_id = $collection['Collection']['id'];
	$collection_name = $collection['Collection']['name']; 
	
	// blocks
	$this->assign('tsr-type', 'coll');
	$this->assign('tsr-url', Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "grouping_id" => 0)));
	$this->assign('tsr-title', 'View ' . $collection_name);
	$this->assign('tsr-maintext', $collection_name);
	
	$this->assign('tsr-actions', '');
	$this->start('tsr-actions');
	echo $this->element('actions/collection', array("collection" => $collection, "show_view" => true));
	$this->end(); 
?>

<h2><?php echo $collection['Collection']['description']; ?></h2>
<em><?php echo $collection['Collection']['collection_item_count']; ?></em>
