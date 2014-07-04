<?php 
	$this->assign('template', 'list');
	$this->assign('page', 'home');
?>

<?php if (!empty($participations)) { ?>
<header class="stripe">
	<h1>Your Collections</h1>
	<a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'add')); ?>" title="Create a new collection">New Collection</a>
</header>

<ul class="list-cards">
	<?php foreach ($participations as $participation): 
		$collection = $participation['Collection'];
		$collection_id = $collection['id'];
		$collection_name = $collection['name']; 
		$completion_count = $participation['Participation']['completion_count'];?>
	<li>

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

	</li>
	<?php endforeach; ?>
</ul>
<?php } ?>

<header class="stripe">
	<h1>Public Collections</h1>
	<a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'add')); ?>" title="Create a new collection">New Collection</a>
</header>

<ul class="tsrs list-cards">
	<?php foreach ($collections as $collection): 
		//$collection_id = $collection['id'];
		//$collection_name = $collection['name']; ?>
	<li>
	

		<?php echo $this->element('teasers/collection', array("collection" => array("Collection" => $collection))); /*array(
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
