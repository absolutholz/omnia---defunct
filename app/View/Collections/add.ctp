<?php 
	$this->assign('template', 'form');
	$this->assign('page', 'collection_create');
?>

<header class="stripe">
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
<div class="btn-group">
<?php foreach ($visibilities as $visibility) { 
	$visibility = $visibility['Visibility'];
	$visibility_id = $visibility['id']; ?>
	<label for="CollectionVisibilityId<?php echo $visibility_id; ?>" class="btn"><?php echo $visibility['name']; ?></label>
	<input type="radio" name="data[Collection][visibility_id]" id="CollectionVisibilityId<?php echo $visibility_id; ?>" value="<?php echo $visibility_id; ?>"<?php if ($visibility_id == 1) { echo ' checked="checked"'; } ?>>
<?php } ?>
</div>
<?php echo $this->Form->end('Create'); ?>
