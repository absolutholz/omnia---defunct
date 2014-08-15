<header>
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
	
		<?php echo $this->element('teasers/participation', array("participation" => $participation)); ?>

	</li>
	<?php endforeach; ?>
</ul>

<footer>
	<p>Created on: <time datetime="<?php echo $user['User']['created']; ?>"><?php $date = date_create($user['User']['created']); echo date_format($date, 'd.m.Y H:i:s'); ?></time></p>
	<?php if (isset($user['User']['modified'])) { ?>
	<p>Last modified on: <time datetime="<?php echo $user['User']['modified']; ?>"><?php $date = date_create($user['User']['modified']); echo date_format($date, 'd.m.Y H:i:s'); ?></time></p>
	<?php } ?>
</footer>
