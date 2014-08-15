<?php 
	$this->assign('template', 'list');
	$this->assign('page', 'home');
?>

<header>
	<h1>Keep track of all of your hoarding.</h1>
	<p>Found something interesting any you think a <a href="<?php echo Router::url(array("controller" => 'users', "action" => 'index')); ?>" title="View all users and see what they're collecting">friend of yours</a> might be looking for it?</p>
<?php if (empty($participations)) { ?>
	<p>Or do you want to <a href="<?php echo Router::url(array("controller" => 'users', "action" => 'login')); ?>">login</a> and track your own progress?</p>
<?php } ?>
	<a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'create')); ?>" class="btn">Create a New Collection</a>
</header>

<?php if (!empty($participations)) { ?>
<section>
	<header>
		<h1>Your Collections</h1>
	</header>

	<ul class="tsrs">
		<?php foreach ($participations as $participation): ?>
		<li>

		<?php echo $this->element('teasers/participation', array("participation" => $participation)); ?>
			
		</li>
		<?php endforeach; ?>
	</ul>
</section>
<?php } ?>

<?php if (!empty($collections)): ?>
<section>
	<header>
		<h1>Public Collections</h1>
	</header>

	<ul class="tsrs">
		<?php foreach ($collections as $collection): ?>
		<li>

			<?php echo $this->element('teasers/collection', array("collection" => $collection)); /*array(
				"href" => Router::url(array('controller' => 'collections', 'action' => 'view', 'collection_id' => $collection_id)),
				"title" => 'View ' . $collection_name,
				"name" => $collection_name,
				"description" => $collection['description'],
				"count" => $collection['collection_item_count'],
				"actions" => array(
					array(
						"href" => Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id)),
						"title" => 'View',
						"text" => 'View'
					), array (
						"href" => Router::url(array("controller" => 'collections', "action" => 'participate', "collection_id" => $collection_id)),
						"title" => 'Participate in ' . $collection_name,
						"text" => 'Participate'
					), array (
						"href" => Router::url(array("controller" => 'collections', "action" => 'update', "collection_id" => $collection_id)),
						"title" => 'Edit ' . $collection_name,
						"text" => 'Edit'
					)
				)
			));*/ ?>

		</li>
		<?php endforeach; ?>
	</ul>
</section>
<?php endif; ?>