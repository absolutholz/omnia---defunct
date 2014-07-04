	<!-- File: /app/View/Collections/index.ctp -->

<header class="stripe">
	<h1>User Management</h1>
	<h2><?php echo isset($user_current) ? 'You are currently logged in as <em>' . $user_current['User']['username'] . '</em>' : 'Here are some other folks collecting stuff. You can view their progress, but to track your own you will need to login or create a user for yourself.'; ?></h2>
	<menu>
		<ul class="actions">
			<li><a href="<?php echo Router::url(array("controller" => 'users', "action" => 'add')); ?>" title="Create a new user">Create New User</a></li>
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
		<section class="card">
			<a href="<?php echo Router::url(array("controller" => 'users', "action" => 'view', "user_id" => $user['User']['id'])); ?>" title="View this user's information">
				<h1><?php echo $user['User']['username'] ?></h1>
			</a>
			<!--ul class="actions">
				<li><a href="<?php echo Router::url(array("controller" => 'users', "action" => 'view', "user_id" => $user['User']['id'])); ?>" title="View this user's information">View</a></li>
				<?php if ($authUser['id'] == $user['User']['id'] || $authUser['role_id'] == 3) { ?>
				<li><a href="<?php echo Router::url(array("controller" => 'users', "action" => 'edit', "user_id" => $user['User']['id'])); ?>" title="Edit this user's information">Edit</a></li>
				<?php } ?>
			</ul-->
		</section>
    </li>
    <?php endforeach; ?>
</ul>