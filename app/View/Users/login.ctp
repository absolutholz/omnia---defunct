<?php 
	$this->assign('template', 'form');
	$this->assign('page', 'login');
?>

<header>
	<h1>User Login</h1>
</header>

<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Please enter your username and password'); ?></legend>
        <?php 
			echo $this->Form->input('username');
			echo $this->Form->input('password');
		?>
    </fieldset>
<?php echo $this->Form->input('rememberMe', array('type' => 'checkbox', 'label' => 'Remember me')); ?>
<button type="submit" class="btn">Login</button>
<?php echo $this->Form->end(); ?>

<footer>
	<div class="divider"><span>or</span></div>
	<a href="<?php echo Router::url(array('controller' => 'users', 'action' => 'create')); ?>" title="Create a new user" class="btn">Create New User</a>
</footer>
