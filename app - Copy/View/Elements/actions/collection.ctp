<?php
	$this->extend('actions/base');

	// Variables
	$collection_id = $collection['Collection']['id'];
	$collection_name = $collection['Collection']['name'];
?>

<?php if (isset($show_view) && $show_view) { ?>
<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "grouping_id" => 0)); ?>">View</a></li>
<?php } ?>
<?php // no need to show other actions since they only make sense to logged in users. non-logged in users can still accomplish these actions, but only on the detail page ?>
<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'participate', "collection_id" => $collection_id)); ?>">Participate</a></li>
