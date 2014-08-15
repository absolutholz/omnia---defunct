<section class="tsr tsr-<?php echo strtolower($this->fetch('tsr-type') . ' ' . $this->fetch('class')); ?>">
	<a href="<?php echo $this->fetch('tsr-url'); ?>" title="<?php echo $this->fetch('tsr-title'); ?>">
		<h1><?php echo $this->fetch('tsr-maintext'); ?></h1>
		<?php echo $this->fetch('content'); ?>
	</a>
	<?php echo $this->fetch('tsr-actions'); ?>
</section>