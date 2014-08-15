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
	<?php 
		$collection_item_count = $coll['collection_item_count'];
		if (isset($participation)) {
			$completion_count = $participation['Participation']['completion_count'];
			echo $this->element('meter', array("value" => $completion_count, "max" => $collection_item_count)); 
	?>
	<em><?php echo $completion_count; ?> / <?php echo $collection_item_count; ?></em>
	<?php } else { ?>
	<em><?php echo $collection_item_count; ?></em>
	<?php } ?>
	<menu>
	<?php 
		if (!isset($participation)) {
			echo $this->element('actions/collection', array("collection" => $collection));
		} else {
			echo $this->element('actions/participation', array("collection" => $collection, "participation" => $participation));
		}
	?>
	</menu>
	
	<section class="statistics">
		<h1>Status Statistics</h1>
		
		<section>
			<h1>Availibility</h1>
			<ul class="statistics-bar">
				<?php foreach ($collection['CollectionItems']['StatusStatistics'] as $key => $value): if ($value != 0): ?>
				<li class="s-<?php echo $availibility_statuses[$key]; ?>" style="flex-grow:<?php echo $value; ?>;"><?php echo $availibility_statuses[$key] . ' = ' . $value; ?></li>
				<?php endif; endforeach; ?>
			</ul>
		</section>

		<?php /*if (isset($completions)) { ?>
		<section>
			<h1>Completions</h1>
			<ul class="statistics-bar">
				<?php foreach ($completions['StatusStatistics'] as $key => $value): if ($value != 0): ?>
				<li class="s-<?php echo $key; ?>" style="flex-grow:<?php echo $value; ?>;"><?php echo $key . ' = ' . $value; ?></li>
				<?php endif; endforeach; ?>
			</ul>
		</section>
		<?php }*/ ?>
	</section>
		
	<nav class="grouping">
		<?php if (!empty($grouping_id)) { ?>
		<p>Currently grouped by <em><?php echo $collection['Groups'][$grouping_id]['Field']['name']; ?></em>.</p>
		<?php } else { ?>
		<p>Items in this collection are not currently grouped</p>
		<?php } ?>
		<?php if (!empty($collection['Groups'])) { ?>
		<div>
			<p>Other ways to group for this collection are:</p>
			<ul>
				<?php foreach ($collection['Groups'] as $key => $group): if ($key != $grouping_id && $key != 'UNGROUPED'): ?>
				<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "grouping_id" => $key)); ?>">by <?php echo $collection['Groups'][$key]['Field']['name']; ?></a></li>
				<?php endif; endforeach; ?>
				<?php if (!empty($grouping_id)) { ?>
				<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id)); ?>">Ungrouped</a></li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
	</nav>

	<section class="stripe tags">
		<h1>Who's participating in this collection</h1>
		
		<ul class="list-tags">
			<?php foreach ($collection['Participations'] as $participation): ?>
				<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "?" => array("user_id" => $participation['User']['id']))); ?>" title="View <?php echo $collection_name . ' for ' . $participation['User']['username']; ?>" class="tag">
	<?php echo $participation['User']['username']; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</section>
</header>
	
<ol class="tsrs">
<?php if (!empty($collection['CollectionItems']['Groups'])) { ?>

	<?php foreach ($collection['CollectionItems']['Groups'] as $key => $group): if ($key != 'UNGROUPED'): ?>
	<li><?php echo $this->element('teasers/group', array("group" => $group, "group_id" => $key, "collection" => $collection, "grouping_id" => $grouping_id, "completions" => $completions));
	
		if ($open_group_id == $key): ?>
		<ol>
			<?php foreach ($group as $collection_item): if (isset($collection_item["CollectionItem"])): ?>
			<li><?php echo $this->element('teasers/collection_item', array("collection_item" => $collection_item, "collection" => $collection, "completions" => $completions, "completion_statuses" => $completion_statuses, "availibility_statuses" => $availibility_statuses)); ?></li>
			<?php endif; endforeach; ?>
		</ol>
		<?php endif; ?>

	</li>	
	<?php endif; endforeach; ?>

	<?php if (!empty($collection['CollectionItems']['Groups']['UNGROUPED'])): foreach ($collection['CollectionItems']['Groups']['UNGROUPED'] as $collection_item): ?>
		<li><?php echo $this->element('teasers/collection_item', array("collection_item" => $collection_item, "collection" => $collection, "completions" => $completions, "completion_statuses" => $completion_statuses, "availibility_statuses" => $availibility_statuses)); ?></li>
	<?php endforeach; endif; ?>

<?php } else { ?>

	<?php foreach ($collection['CollectionItems'] as $collection_item): ?>
		<li><?php echo $this->element('teasers/collection_item', array("collection_item" => $collection_item, "collection" => $collection, "completions" => $completions, "completion_statuses" => $completion_statuses, "availibility_statuses" => $availibility_statuses)); ?></li>
	<?php endforeach; ?>

<?php } ?>
</ol>
	
<footer class="stripe">
	<p>Created on: <time datetime="<?php echo $collection['Collection']['created']; ?>"><?php $date = date_create($collection['Collection']['created']); echo date_format($date, 'd.m.Y H:i:s'); ?></time></p>
	<?php if (isset($collection['Collection']['modified'])) { ?>
	<p>Last modified on: <time datetime="<?php echo $collection['Collection']['modified']; ?>"><?php $date = date_create($collection['Collection']['modified']); echo date_format($date, 'd.m.Y H:i:s'); ?></time></p>
	<?php } ?>
</footer>
