<?php 
	$this->extend('teasers/base');
	
	// variables
	$collection_id = $collection['Collection']['id'];
	$collection_name = $collection['Collection']['name']; 
	
	// blocks
	$this->assign('tsr-type', 'coll');
	$this->assign('tsr-url', Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id)));
	$this->assign('tsr-title', 'View ' . $collection_name);
	$this->assign('tsr-maintext', $collection_name);
	
	$this->assign('tsr-actions', '');
	$this->start('tsr-actions');
?>
<ul class="actions">
	<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id)); ?>" title="View <?php echo $collection_name; ?>">View</a></li>
	<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'participate', "collection_id" => $collection_id)); ?>" title="Participate in <?php echo $collection_name; ?>">Participate</a></li>
</ul>
<?php $this->end(); ?>

<h2><?php echo $collection['Collection']['description']; ?></h2>
<em><?php echo $collection['Collection']['collection_item_count']; ?></em>
