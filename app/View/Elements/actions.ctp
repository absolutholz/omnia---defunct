<ul class="actions">
	<?php foreach ($actions as $action) { ?>
	<li>
		<a href="<?php echo $action['href']; ?>" title="<?php echo $action['title']; ?>"><?php echo $action['text']; ?></a>
	</li>
	<?php } ?>
</ul>
