<?php 
	$this->assign('template', 'form');
	$this->assign('page', 'collection_update');
?>

<header class="stripe">
	<?php 
		echo $this->element('breadcrumbs', array("crmbs" => array())); 
	?>

	<h1>Edit <?php echo $collection['Collection']['name']; ?></h1>
</header>

<?php
	$collection_id = $collection['Collection']['id'];
	echo $this->Form->create('Collection');
	echo $this->Form->input('name');
	echo $this->Form->input('description', array('rows' => '3'));
?>

<div class="btn-group">
<?php foreach ($visibilities as $visibility) { 
	$visibility = $visibility['Visibility'];
	$visibility_id = $visibility['id']; ?>
	<label for="CollectionVisibilityId<?php echo $visibility_id; ?>" class="btn"><?php echo $visibility['name']; ?></label>
	<input type="radio" name="data[Collection][visibility_id]" id="CollectionVisibilityId<?php echo $visibility_id; ?>" value="<?php echo $visibility_id; ?>"<?php if ($visibility_id == $collection['Collection']['visibility_id']) { echo ' checked="checked"'; } ?>>
<?php } ?>
</div>

<section>
	<header>
		<h1>Fields</h1>
		<a href="<?php echo Router::url(array("controller" => 'fields', "action" => 'add', "collection_id" => $collection_id)); ?>" title="Add Field">Add Field</a>
	</header>
	
	<ul>
	    <?php foreach ($collection['Field']as $field): ?>
			<li>
				<section class="card">
					<a href="#" title="">
						<h1><?php echo $field['name'] ?></h1>
					</a>

					<?php if ($field['is_required'] || $field['is_groupable']) { ?>
					<ul>
						<?php if ($field['is_required']) { ?><li class="tag">required</li><?php } ?>
						<?php if ($field['is_groupable']) { ?><li class="tag">groupable</li><?php } ?>
					</ul>
					<?php } ?>
		
					<ul class="actions">
						<?php // if (!empty($field['groupable'])) { echo '<em>Groupable</em>'; } ?>
						<?php // if (!empty($field['required'])) { echo '<em>Required</em>'; } ?>
						<li><?php echo $this->Html->link('Edit', array("controller" => 'fields', "action"=> 'edit', "collection_id" => $collection_id, "field_id" => $field['id'])); ?></li>
						<li><?php echo $this->Html->link('Delete', array("controller" => 'fields', "action" => 'delete', "collection_id" => $collection_id, "field_id" => $field['id'])); ?></li>
					</ul>
				</section>
			</li>
		<?php endforeach; ?>
	</ul>
</section>

<?php echo $this->Form->end('Save Collection'); ?>
