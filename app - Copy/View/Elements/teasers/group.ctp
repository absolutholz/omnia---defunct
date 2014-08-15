<?php 
	$this->extend('teasers/base');
	
	// variables
	$collection_id = $collection['Collection']['id'];
	$count_total = array_sum($group['StatusStatistics']);

	// blocks
	$this->assign('tsr-type', 'group');
	$this->assign('tsr-url', Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "grouping_id" => $grouping_id, "open_group_id" => $group_id)));
	$this->assign('tsr-title', 'View ' . $group_id);
	$this->assign('tsr-maintext', $group_id);
	
	$this->assign('tsr-actions', '');
	$this->start('tsr-actions');
	$this->end(); ?>

<?php 
	if (isset($completions)) {
		$completions_total = $completions['Groups'][$group_id];
		echo $this->element('meter', array("value" => $completions_total, "max" => $count_total)); ?>
<em><?php echo $completions_total; ?> / <?php echo $count_total; ?></em>
<?php } else { ?>
<em><?php echo count($count_total); ?></em>
<?php } ?>
