<?php if (isset($crmbs)) { ?>
<nav>
	<ol class="breadcrumbs">
		<li>
			<a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'index')); ?>" title="Go back to the omnia homepage" class="crmb">Home</a>
		</li>
	<?php foreach ($crmbs as $crmb) { ?>
		<li>
			<a href="<?php echo $crmb['href']; ?>" title="<?php echo $crmb['title']; ?>" class="crmb"><?php echo $crmb['text']; ?></a>
		</li>
	<?php } ?>
</nav>
<?php } ?>
