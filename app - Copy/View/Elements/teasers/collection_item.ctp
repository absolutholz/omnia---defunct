<?php 
	$this->extend('teasers/base');
	// variables
	$item = $collection_item['CollectionItem'];
	$item_id = $item['id'];

	$item_name = $item['name'];
	$collection_id = $item['collection_id'];
	
	$item_status = '';
	if ($item['collection_item_status_id'] != 1) { 
		$item_status = ' s-' . $availibility_statuses[$item['collection_item_status_id']];
	}
	
	$completion_status = '';
	if (isset($completions[$item_id]) && $completions[$item_id]['Completion']['completion_status_id'] != 1) { 
		$completion_status = ' s-' . $completion_statuses[$completions[$item_id]['Completion']['completion_status_id']];
	}
	
	// blocks
	$this->assign('class', $item_status . $completion_status);
	$this->assign('tsr-type', 'item');
	$this->assign('tsr-url', Router::url(array("controller" => 'collection_items', "action" => 'view', "collection_id" => $collection_id, 'collection_item_id' => $item_id)));
	$this->assign('tsr-title', 'View ' . $item_name);
	$this->assign('tsr-maintext', $item_name);
	$this->assign('tsr-actions', '');
?>

<?php if (!empty($collection['Groups'])): ?>
<ol>
<?php foreach ($collection['Groups'] as $group): if (isset($collection_item['CollectionItemField'][$group['Field']['id']])): ?>
	<li><?php echo $collection_item['CollectionItemField'][$group['Field']['id']]['value']; ?></li>
<?php endif; endforeach; ?>
</ol>
<?php endif; ?>

<?php /*
<section class="tsr tsr-item card<?php if ($collection_item['CollectionItemStatus']['id'] != 1) { echo ' s-' . $collection_item['CollectionItemStatus']['name']; } if (isset($completions[$item_id])) { echo ' s-' . $completions[$item_id]['CompletionStatus']['name'];} ?>">
	<a href="<?php echo Router::url(array("controller" => 'collection_items', "action" => 'view', "collection_id" => $collection_id, 'collection_item_id' => $item_id)); ?>" title="View <?php echo $collection_item['CollectionItem']['name']; ?>">
		<h1><?php echo $collection_item['CollectionItem']['name']; ?></h1>
		<ol>
		<?php //var_dump($collection_item);
		foreach ($collection['Groups'] as $group) { 
			if (isset($collection_item['CollectionItemField'][$group['id']])) { ?>				
			<li><?php echo $collection_item['CollectionItemField'][$group['id']]['value']; ?></li>
		<?php } } ?>
		</ol>
	</a>
</section>
*/ ?>