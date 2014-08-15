	<!-- File: /app/View/Collections/index.ctp -->

<header class="stripe">
	<h1>User Management</h1>
	<h2><?php echo isset($user_current) ? 'You are currently logged in as <em>' . $user_current['User']['username'] . '</em>' : 'Here are some other folks collecting stuff. You can view their progress, but to track your own you will need to login or create a user for yourself.'; ?></h2>
	<menu>
		<ul class="actions">
			<li><a href="<?php echo Router::url(array("controller" => 'users', "action" => 'create')); ?>" title="Create a new user">Create New User</a></li>
			<?php if (isset($user_current)) { ?>
			<li><a href="<?php echo Router::url(array("controller" => 'users', "action" => 'view', "user_id" => $user_current['User']['id'])); ?>" title="View">View</a></li>
			<li><a href="<?php echo Router::url(array("controller" => 'users', "action" => 'edit', "user_id" => $user_current['User']['id'])); ?>" title="Edit">Edit</a></li>
			<li><a href="<?php echo Router::url(array("controller" => 'users', "action" => 'logout')); ?>" title="Logout">Logout</a></li>
			<?php } else { ?>
			<li><a href="<?php echo Router::url(array("controller" => 'users', "action" => 'login')); ?>" title="Login">Login</a></li>
			<?php } ?>
		</ul>
	</menu>
</header>


<ul class="list-cards">
    <?php foreach ($users as $user): ?>
    <li>
	
		<?php echo $this->element('teasers/user', array("user" => $user)); ?>
	
    </li>
    <?php endforeach; ?>
</ul>
