<?php 
	$this->extend('teasers/base');
	
	// variables
	$user_id = $user['User']['id'];
	
	// blocks
	$this->assign('tsr-type', 'user');
	$this->assign('tsr-url', Router::url(array("controller" => 'users', "action" => 'view', "user_id" => $user_id)));
	$this->assign('tsr-title', 'View this user\'s information');
	$this->assign('tsr-maintext', $user['User']['username']);
	
	$this->assign('tsr-actions', '');
	$this->start('tsr-actions');
?>
<!--ul class="actions">
	<li><a href="<?php echo Router::url(array("controller" => 'users', "action" => 'view', "user_id" => $user_id)); ?>" title="View this user's information">View</a></li>
	<?php if ($authUser['id'] == $user_id || $authUser['role_id'] == 3) { ?>
	<li><a href="<?php echo Router::url(array("controller" => 'users', "action" => 'edit', "user_id" => $user_id)); ?>" title="Edit this user's information">Edit</a></li>
	<?php } ?>
</ul-->
<?php $this->end(); ?>
