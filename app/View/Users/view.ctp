<header class="stripe">
	<h1><?php echo $user['User']['username']; ?></h1>
	<h2><?php echo $user['Role']['name']; ?></h2>

	<?php if ($authUser['id'] == $user['User']['id'] || $authUser['role_id'] == 3) { ?>			
	<menu>
		<ul class="actions">
			<li><a href="<?php echo Router::url(array("controller" => 'users', "action" => 'edit', "user_id" => $user['User']['id'])); ?>" title="Edit">Edit</a></li>
		</ul>
	</menu>
	<?php } ?>
</header>
	
<ul class="list-cards">
	<?php foreach ($user['Participations'] as $participation): 
		$collection = $participation['Collection'];
		$collection_id = $collection['id'];
		$collection_name = $collection['name']; 
		$completion_count = $participation['Participation']['completion_count'];?>
	<li>

		<section class="card">
			<a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "?" => array("user_id" => $user['User']['id']))); ?>" title="View <?php echo $collection_name; ?>">
				<h1><?php echo $collection_name; ?></h1>
				<h2><?php echo $collection['description']; ?></h2>
				<meter value="<?php echo $completion_count; ?>" max="<?php echo $collection['collection_item_count']; ?>"></meter>
				<em><?php echo $completion_count; ?> / <?php echo $collection['collection_item_count']; ?></em>
			</a>
		</section>

	</li>
	<?php endforeach; ?>
</ul>

<footer class="stripe">
	<p>Created on: <time datetime="<?php echo $user['User']['created']; ?>"><?php $date = date_create($user['User']['created']); echo date_format($date, 'd.m.Y H:i:s'); ?></time></p>
	<?php if (isset($user['User']['modified'])) { ?>
	<p>Last modified on: <time datetime="<?php echo $user['User']['modified']; ?>"><?php $date = date_create($user['User']['modified']); echo date_format($date, 'd.m.Y H:i:s'); ?></time></p>
	<?php } ?>
</footer>
