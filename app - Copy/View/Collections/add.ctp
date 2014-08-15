<?php 
	$this->assign('template', 'form');
	$this->assign('page', 'collection_create');
?>

<header>
	<?php 
		echo $this->element('breadcrumbs', array("crmbs" => array())); 
	?>

	<h1>Create Collection</h1>
</header>

<?php
echo $this->Form->create('Collection');
echo $this->Form->input('user_id', array("type" => 'hidden', "value" => $user_id));
echo $this->Form->input('name');
echo $this->Form->input('description', array("rows" => '3'));
?>

<fieldset>
	<legend>Set the visibility of this collection</legend>
	<ul class="btn-group">
	<?php foreach ($visibilities as $visibility) { 
		$visibility = $visibility['Visibility'];
		$visibility_id = $visibility['id']; ?>
		<li>
			<input type="radio" name="data[Collection][visibility_id]" id="CollectionVisibilityId<?php echo $visibility_id; ?>" value="<?php echo $visibility_id; ?>"<?php if ($visibility_id == 1) { echo ' checked="checked"'; } ?>>
			<label for="CollectionVisibilityId<?php echo $visibility_id; ?>" class="btn btn-rad"><?php echo $visibility['name']; ?></label>
		</li>
	<?php } ?>
	</ul>
</fieldset>

<button type="submit" class="btn">Create</button>

<?php echo $this->Form->end(); ?>
