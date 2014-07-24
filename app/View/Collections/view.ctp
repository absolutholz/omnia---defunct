<?php 
	$this->assign('template', 'list');
	$this->assign('page', 'collection');
?>

<header class="stripe">	
	<?php //echo $this->element('breadcrumbs');
	$coll = $collection['Collection'];
	$collection_id = $coll['id'];
	$collection_name = $coll['name']; 
	//$completion_count = $participation['Participation']['completion_count'];?>
	
	<?php 
		echo $this->element('breadcrumbs', array("crmbs" => array())); 
	?>

	<h1><?php echo $collection_name; ?></h1>
	<h2><?php echo $coll['description']; ?></h2>
	<!--meter value="<?php //echo $completion_count; ?>" max="<?php //echo $collection['collection_item_count']; ?>"></meter-->
	<em><!--?php //echo $completion_count; ?> / --><?php echo $coll['collection_item_count']; ?></em>

	<menu>
		<ul class="actions">
			<li><a href="<?php echo Router::url(array('controller' => 'collection_items', 'action' => 'add', 'collection_id' => $collection_id)); ?>" title="Add an item to <?php echo $collection_name; ?>">Add Item</a></li>
			<li><a href="<?php echo Router::url(array('controller' => 'collections', 'action' => 'edit', 'collection_id' => $collection_id)); ?>" title="Edit <?php echo $collection_name; ?>">Edit</a></li>
			<?php if (empty($participation)) { ?>
			<li><a href="<?php echo Router::url(array('controller' => 'collections', 'action' => 'participate', 'collection_id' => $collection_id)); ?>" title="Participate <?php echo $collection_name; ?>">Participate</a></li>
			<?php } else { ?>
			<li><a href="<?php echo Router::url(array('controller' => 'collections', 'action' => 'unparticipate', 'collection_id' => $participation['Participation']['id'])); ?>" title="Unparticipate <?php echo $collection_name; ?>">Unparticipate</a></li>
			<?php } ?>
			<?php if (!empty($participation)) { ?>
			<li><a href="<?php echo Router::url(array('controller' => 'collections', 'action' => 'delete', 'collection_id' => $collection_id)); ?>" title="Delete <?php echo $collection_name; ?>">Delete</a></li>
			<?php } ?>
		</ul>
	</menu>
</header>

<section class="stripe tags">
	<h1>Who's participating in this collection</h1>
	
	<ul class="list-tags">
		<?php foreach ($collection['Participations'] as $participation): ?>
			<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "?" => array("user_id" => $participation['User']['id']))); ?>" title="View <?php echo $collection_name . ' for ' . $participation['User']['username']; ?>" class="tag">
<?php echo $participation['User']['username']; ?></a></li>
		<?php endforeach; ?>
	</ul>
</section>
	
<ol class="tsrs">
<?php if (!empty($collection['CollectionItems']['Groups'])) { ?>

	<?php foreach ($collection['CollectionItems']['Groups'] as $key => $group): if ($key != 'UNGROUPED'): ?>
	<li><?php echo $this->element('teasers/group', array("group" => $group, "group_id" => $key, "collection" => $collection, "grouped_by_id" => $grouped_by_id));
	if ($open_group_id == $key): ?>

	<ol>
		<?php foreach ($group as $collection_item): ?>
		<li><?php echo $this->element('teasers/collection_item', array("collection_item" => $collection_item, "collection" => $collection)); ?></li>
		<?php endforeach; ?>
	</ol>
	
	<?php endif; ?></li>	
	<?php endif; endforeach; ?>

	<?php foreach ($collection['CollectionItems']['Groups']['UNGROUPED'] as $collection_item): ?>
		<li><?php echo $this->element('teasers/collection_item', array("collection_item" => $collection_item, "collection" => $collection)); ?></li>
	<?php endforeach; ?>

<?php } else { ?>

	<?php foreach ($collection['CollectionItems'] as $collection_item): ?>
		<li><?php echo $this->element('teasers/collection_item', array("collection_item" => $collection_item, "collection" => $collection)); ?></li>
	<?php endforeach; ?>

<?php } ?>
</ol>
	
<footer class="stripe">
	<p>Created on: <time datetime="<?php echo $collection['Collection']['created']; ?>"><?php $date = date_create($collection['Collection']['created']); echo date_format($date, 'd.m.Y H:i:s'); ?></time></p>
	<?php if (isset($collection['Collection']['modified'])) { ?>
	<p>Last modified on: <time datetime="<?php echo $collection['Collection']['modified']; ?>"><?php $date = date_create($collection['Collection']['modified']); echo date_format($date, 'd.m.Y H:i:s'); ?></time></p>
	<?php } ?>
</footer>
