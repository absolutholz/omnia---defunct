<section class="tsr">
	<a href="<?php echo $href; ?>" title="<?php echo $title; ?>">
		<h1><?php echo $name; ?></h1>
	</a>
	<?php if (isset($actions) && !empty($actions)) {
		echo $this->element('actions', array("actions" => $actions)); 
	} ?>
</section>