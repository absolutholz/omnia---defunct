<meter value="<?php echo $value; ?>" max="<?php echo $max; ?>">
	<div class="progress">
	<?php if ($max && $value) { ?>
		<span class="meter" style="width: <?php echo ($value / $max) * 100; ?>%;"></span>
	<?php } else { ?>
		<span class="meter"></span>
	<?php } ?>
	</div>
</meter>