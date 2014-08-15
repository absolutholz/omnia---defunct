<!-- File: /app/View/Posts/add.ctp -->

<header>
	<h1>Update User</h1>
</header>

<?php
echo $this->Form->create('User');
echo $this->Form->input('id');
echo $this->Form->input('username');
echo $this->Form->input('password', array("type" => 'text', "required" => false, "placeholder" => 'enter only to overwrite current password'));

if (isset ($roles) && is_array($roles)) { ?>
<fieldset>
<?php // NOTE: flex box does not work on fieldset elements ?>
	<legend>User Role</legend>
	<div>
	<?php foreach ($roles as $role) { 
		$role = $role['Role'];
		$role_id = $role['id']; ?>
		<label for="UserRoleId<?php echo $role_id; ?>"><?php echo $role['name']; ?></label>
		<input type="radio" name="data[User][role_id]" id="UserRoleId<?php echo $role_id; ?>" value="<?php echo $role_id; ?>"<?php if ($role_id == $user['User']['role_id']) { echo ' checked="checked"'; } ?>>
		<label for="UserRoleId<?php echo $role_id; ?>"><?php echo $role['description']; ?></label>
	<?php } ?>
	</div>
</fieldset>
<?php } echo $this->Form->end('Update'); ?>