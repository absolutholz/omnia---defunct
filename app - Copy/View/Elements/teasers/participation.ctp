<?php 
	$this->extend('teasers/base'); 

	// variables
	$collection = $participation['Collection'];
	$collection_id = $collection['id'];
	$collection_name = $collection['name']; 
	$completion_count = $participation['Participation']['completion_count'];

	// blocks
	$this->assign('tsr-type', 'part');
	$this->assign('tsr-url', Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "grouping_id" => 0)));
	$this->assign('tsr-title', 'View ' . $collection_name);
	$this->assign('tsr-maintext', $collection_name);
	
	$this->assign('tsr-actions', '');
	$this->start('tsr-actions');
	echo $this->element('actions/participation', array("collection" => $participation, "show_view" => true));
	$this->end(); ?>

<h2><?php echo $collection['description']; ?></h2>
<?php echo $this->element('meter', array("value" => $completion_count, "max" => $collection['collection_item_count'])); ?>
<em><?php echo $completion_count; ?> / <?php echo $collection['collection_item_count']; ?></em>




<?php /*
<section class="card tsr tsr-part">
	<a href="<?php echo Router::url(array('controller' => 'collections', 'action' => 'view', 'collection_id' => $collection_id)); ?>" title="View <?php echo $collection_name; ?>">
		<h1><?php echo $collection_name; ?></h1>
		<h2><?php echo $collection['description']; ?></h2>
		<?php echo $this->element('meter', array("value" => $completion_count, "max" => $collection['collection_item_count'])); ?>
		<em><?php echo $completion_count; ?> / <?php echo $collection['collection_item_count']; ?></em>
	</a>
	<ul class="actions">
		<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id)); ?>" title="View <?php echo $collection_name; ?>">View</a></li>
		<?php if ($authUser['role_id'] == 3) { ?>
		<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'edit', "collection_id" => $collection_id)); ?>" title="Edit <?php echo $collection_name; ?>">Edit</a></li>
		<li><a href="<?php echo Router::url(array("controller" => 'collection_items', "action" => 'add', "collection_id" => $collection_id)); ?>" title="Add to <?php echo $collection_name; ?>">Add to</a></li>
		<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'unparticipate', "collection_id" => $collection_id, "participation_id" => $participation['Participation']['id'])); ?>" title="Stop participating in <?php echo $collection_name; ?>">Unparticipate</a></li>
		<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'delete', "collection_id" => $collection_id)); ?>" title="Delete <?php echo $collection_name; ?>">Delete</a></li>
		<?php } ?>
	</ul>
</section>
*/ ?>