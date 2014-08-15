<header>
	<?php $rights = true; if ($rights) { ?>
	<h1>Are you sure you want to delete this field?</h1>
	<?php } else { ?>
	<h1>You do not have the rights to delete this field. Care to send a message to the person who does asking them to do it for you?</h1>
	<?php } ?>
</header>

<?php
echo $this->Form->create('Field');
if (!$rights) {
	echo $this->Form->input('message', array("rows" => 3));
}
echo $this->Form->end('Delete Field');
?>
