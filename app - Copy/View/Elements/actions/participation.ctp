<?php
	$this->extend('actions/base');

	// Variables
	$collection_id = $collection['Collection']['id'];
	$collection_name = $collection['Collection']['name'];
	
	if (!isset($participation)) {
		$participation = $collection;
	}
?>

<?php if (isset($show_view) && $show_view) { ?>
<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "grouping_id" => 0)); ?>">View</a></li>
<?php } ?>
<li><a href="<?php echo Router::url(array("controller" => 'collection_items', "action" => 'create', "collection_id" => $collection_id)); ?>">Add New Item</a></li>
<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'edit', "collection_id" => $collection_id)); ?>">Edit</a></li>
<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'delete', "collection_id" => $collection_id)); ?>">Delete</a></li>
<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'unparticipate', "collection_id" => $collection_id, "participation_id" => $participation['Participation']['id'])); ?>">Unparticipate</a></li>
