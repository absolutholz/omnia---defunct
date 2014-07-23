<?php 
	$this->assign('template', 'list');
	$this->assign('page', 'home');
?>

<?php if (empty($participations)) { ?>
<header>
	<p>Here to help <a href="<?php echo Router::url(array("controller" => 'users', "action" => 'index')); ?>" title="View and select all users on the User Management page">someone else</a>, or do you want to <a href="<?php echo Router::url(array("controller" => 'users', "action" => 'login')); ?>">login</a> and track your own progress?</p>
</header>
<?php } ?>

<?php if (!empty($participations)) { ?>
<section>
	<header>
		<h1>Your Collections</h1>
		<a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'add')); ?>" title="Create a new collection">New Collection</a>
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
		<a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'add')); ?>" title="Create a new collection">New Collection</a>
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