<nav>
	<a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'index')); ?>" title="Go back to home">omnia</a>
</nav>
<menu>
	<?php if (!isset($authUser)) { ?>
	<a href="<?php echo Router::url(array("controller" => 'users', "action" => 'login')); ?>" title="Login">Login</a>
	<?php } else { 
		if ($authUser['role_id'] == 3) { ?>
	<a href="<?php echo Router::url(array("controller" => 'users', "action" => 'index')); ?>" title="User Management"><?php echo $authUser['username']; ?></a>
		<?php } else { ?>
	<a href="<?php echo Router::url(array("controller" => 'users', "action" => 'view', "user_id" => $authUser['id'])); ?>" title="User Management"><?php echo $authUser['username']; ?></a>
		<?php } ?>
	<?php } ?>
	<a href="#search" title="Search through the collections">Search</a>
</menu>

<form id="search" method="get" action="<?php echo Router::url(array("controller" => 'collections', "action" => 'search')); ?>" class="frm-search">
		<label for="search_term">Search Term</label>
		<input type="text" required="required" id="search_term" name="search_term">
		<button type="submit" class="btn">Search</button>
		<a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'search')); ?>" title="Perform a search with more custom options available">Extended Seach Options</a>
</form>
