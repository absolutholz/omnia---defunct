<?php 
	$item_id = $collection_item['CollectionItem']['id'];
	$collection_id = $collection_item['CollectionItem']['collection_id'];
?>

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
