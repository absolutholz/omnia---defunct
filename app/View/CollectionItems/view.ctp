<?php 
	$this->assign('template', 'details');
	$this->assign('page', 'collection_item');
	$collection_id = $collection['Collection']['id'];
	$item_id = $collection_item['CollectionItem']['id'];
	$item_status = isset($collection_item['CollectionItem']['collection_item_status_id']) ? $collection_item['CollectionItem']['collection_item_status_id'] : 1; // default = available
	// TODO: logged in completion status
	$completion_status = isset($completion['Completion']['completion_status_id']) ? $completion['Completion']['Completion']['completion_status_id'] : 1; // default = incomplete
?>

<header>
	<?php 
		echo $this->element('breadcrumbs', array("crmbs" => array(
			array("href" => Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "#" => $item_id)), "text" => $collection['Collection']['name'], "title" => $collection['Collection']['name']),
//			array("href" => Router::url(array("controller" => 'collection_items', "action" => 'view', "collection_id" => $collection_id, "collection_item_id" => $item_id)), "text" => $collection_item['CollectionItem']['name'], "title" => $collection_item['CollectionItem']['name'])
		))); 
	?>
	
	<?php if ($item_status == 3) { ?>
	<p class="msg msg-warn">Achtung! This item has been marked as being no longer available.</p>
	<?php } else if ($item_status == 2) { ?>
	<p class="msg msg-info">This item has been marked as only having limited availability.</p>
	<?php } ?>
	
	<h1><?php echo $collection_item['CollectionItem']['name']; ?></h1>
		
	<menu class="actions">		
		<ul>
			<li><a 
				href="<?php echo Router::url(array("controller" => 'collection_items', "action" => 'edit', "collection_id" => $collection_id, "collection_item_id" => $item_id)); ?>" 
				title="Edit <?php echo $collection_item['CollectionItem']['name']; ?>">Edit</a></li>
			<!-- TODO: not cached, admin or creator feature only -->
			<li><a 
				href="<?php echo Router::url(array("controller" => 'collection_items', "action" => 'delete', "collection_id" => $collection_id, "collection_item_id" => $item_id)); ?>" 
				title="Delete <?php echo $collection_item['CollectionItem']['name']; ?>">Delete</a></li>
		</ul>
	</menu>	
</header>

<menu>
	<ul class="btn-group">
		<!-- TODO: not cached -->
		<?php foreach (array_reverse($status_completion, true) as $status_id => $status_name) { ?>
		<li><a 
			href="<?php echo Router::url(array("controller" => 'collection_items', "action" => 'complete', "collection_id" => $collection_id, "collection_item_id" => $item_id, "status_id" => $status_id)); ?>" 
			title="Mark as <?php echo $status_name; ?>" 
			class="btn <?php if ($status_id == 3) { echo 'btn-positive'; } elseif ($status_id == 1) { echo 'btn-negative';} if ($completion_status == $status_id) { echo ' s-active'; } ?>"><?php echo $status_name; ?></a></li>
		<?php } ?>
	</ul>
</menu>

<dl class="card">
	<?php if (!empty($collection_item['CollectionItem']['notes'])) { ?>
	<dt>Notes</dt>
	<?php foreach (explode(';', $collection_item['CollectionItem']['notes']) as $note) { ?>
	<dd><?php echo $note; ?></dd>
	<?php } ?>
	<?php } ?>
	
	<?php 
		foreach ($collection_item['CollectionItemField'] as $item_field):
			$field = $collection['Field'][$item_field['field_id']];
			if (!empty($item_field['value'])) { ?>
	<dt><?php echo $field['name'] ?></dt>
	<?php 
		switch ($field['field_type_id']) {
			case 4: // multiple values
				foreach (explode(';', $item_field['value']) as $field_value) {
					echo '<dd>' . $field_value . '</dd>';
				}
				break;
			case 5: // website
				echo '<dd>' . $this->Html->link($item_field['value'], $item_field['value'], array("class" => 'link-external', "target" => 'blank')). '</dd>';
				break;
			default:
				echo '<dd>' . $item_field['value'] . '</dd>';
		}
	?>
	</dd>
	<?php }
	endforeach; ?>

	<?php if (!empty($collection_item['Completion'])) { ?>
	<dt>Completions</dt>
	<dd>

		<ul class="list-tags">
		<?php 
			$participations = $collection['Participations'];
			foreach ($collection_item['Completion'] as $completion): 
				$participation = $participations[$completion['participation_id']];
				if (isset($participations) && $participation['Participation']['visibility_id'] == 1) {
					$user = $participation['User'];
		?>
			<li>
				<a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id, "?" => array("user_id" => $user['id']))); ?>" 
					title="View <?php echo $collection['Collection']['name'] . ' for ' . $user['username']; ?>" 
					class="tag <?php echo 'is-' . ($completion['completion_status_id'] == 3 ? 'complete' : 'pending'); ?>"><?php echo $user['username']; ?></a>
			</li>
		<?php } endforeach; ?>
		</ul>
	<dd>
	<?php } ?>
</dl>

<menu>
	<ul class="btn-group">
		<li><a
			href="<?php echo Router::url(array('controller' => 'collection_items', 'action' => 'available', 'collection_id' => $collection_id, 'collection_item_id' => $item_id, 'status_id' => 1)); ?>" 
			title="Mark as Available" 
			class="btn btn-positive<?php if ($item_status == 1) { echo ' s-active'; } ?>">Available</a></li>
		<li><a 
			href="<?php echo Router::url(array('controller' => 'collection_items', 'action' => 'available', 'collection_id' => $collection_id, 'collection_item_id' => $item_id, 'status_id' => 2)); ?>" 
			title="Mark as Limited" 
			class="btn<?php if ($item_status == 2) { echo ' s-active'; } ?>">Limited</a></li>
		<li><a 
			href="<?php echo Router::url(array('controller' => 'collection_items', 'action' => 'available', 'collection_id' => $collection_id, 'collection_item_id' => $item_id, 'status_id' => 3)); ?>" 
			title="Mark as Defunct" 
			class="btn btn-negative<?php if ($item_status == 3) { echo ' s-active'; } ?>">Defunct</a></li>
	</ul>
</menu>

<footer>
	<p>Created on: <time datetime="<?php echo $collection_item['CollectionItem']['created']; ?>"><?php $date = date_create($collection_item['CollectionItem']['created']); echo date_format($date, 'd.m.Y H:i:s'); ?></time></p>
	<?php if (isset($collection_item['CollectionItem']['modified'])) { ?>
	<p>Last modified on: <time datetime="<?php echo $collection_item['CollectionItem']['modified']; ?>"><?php $date = date_create($collection_item['CollectionItem']['modified']); echo date_format($date, 'd.m.Y H:i:s'); ?></time></p>
	<?php } ?>
</footer>

	<?php /*if (isset($user_id)) { 
		$completion_id = isset($collection_item['Completion']['Completion']) ? $collection_item['Completion']['Completion']['id'] : null;
		$link_params_default = empty($completion_id) ? array("controller" => 'completions', "action" =>'add', $collection_item['Collection']['id'], $collection_item['collection_item']['id']) : array("controller" => 'completions', "action" =>'edit', $completion_id); ?>
	<ul>
	
		<?php $current_status = isset($collection_item['Completion']['Completion']['completion_status_id']) ? $collection_item['Completion']['Completion']['completion_status_id'] : 1; ?>
		
		<?php if ($current_status != 3) { 
					$link_params = $link_params_default;
					array_push($link_params, 3); ?>
		<li><?php echo $this->Html->link('Mark as complete', $link_params); ?></li>
		<?php } ?>

		<?php if ($current_status != 1) { 
					$link_params = $link_params_default;
					array_push($link_params, 1); ?>
		<li><?php echo $this->Html->link('Mark as incomplete', $link_params); ?></li>
		<?php } ?>

		<?php if ($current_status != 2) { 
					$link_params = $link_params_default;
					array_push($link_params, 2); ?>
		<li><?php echo $this->Html->link('Mark as pending', $link_params); ?></li>
		<?php } ?>
		
		<?php if ($collection_item['collection_item']['collection_item_status_id'] != 1) { ?>
		<li><?php echo $this->Html->link('Mark as available', array("action" => 'setStatus', $collection_item['Collection']['id'], 1)); ?></li>
		<?php } ?>

		<?php if ($collection_item['collection_item']['collection_item_status_id'] != 2) { ?>
		<li><?php echo $this->Html->link('Mark as limited', array("action" => 'setStatus', $collection_item['Collection']['id'], 2)); ?></li>
		<?php } ?>

		<?php if ($collection_item['collection_item']['collection_item_status_id'] != 3) { ?>
		<li><?php echo $this->Html->link('Mark as defunct', array("action" => 'setStatus', $collection_item['Collection']['id'], 3)); ?></li>
		<?php } ?>
						
		<li><?php echo $this->Html->link('Edit', array('action' => 'edit', $collection_item['collection_item']['id'])); ?></li>
		<li><?php echo $this->Form->postLink('Delete', array("action" => 'delete', $collection_item['collection_item']['id']), array('confirm' => 'Are you sure?')); ?></li>				
	</ul>
	<?php } */?>
	