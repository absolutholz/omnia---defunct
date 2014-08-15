<?php 
	$this->assign('template', 'list');
	$this->assign('page', 'search');
?>

<?php if (!empty($collections) || !empty($collection_items)) { ?>

<?php if (!empty($collections)) { ?>
	<p><em><?php echo count($collections); ?></em> Collections found for <em><?php echo $search_term; ?></em>
	<ol>
	<?php foreach ($collections as $key => $collection) { ?>
		<li><?php echo $this->element('teasers/collection', array("collection" => $collection)); ?></li>
	<?php } ?>
	</ol>
<?php } ?>

<?php if (!empty($collection_items)) { ?>
	<p><em><?php echo count($collection_items); ?></em> Collection Items found for <em><?php echo $search_term; ?></em>
	<ol>
	<?php foreach ($collection_items as $key => $item) { ?>
		<li><?php echo $this->element('teasers/collection_item', array("collection_item" => $item, "collection" => $collection)); ?></li>
	<?php } ?>
	</ol>
<?php } ?>	
	
<?php } else { ?>
<p>No Results for <em><?php echo $search_term; ?></em></p>
<?php } ?>

<footer>
	<p>Didn't find what you were looking for?</p>
	<a href="<?php echo Router::url(array("controller" => 'collection_items', "action" => 'add')); ?>" title="Create a new collection item">New Collection Item</a>
</footer>
