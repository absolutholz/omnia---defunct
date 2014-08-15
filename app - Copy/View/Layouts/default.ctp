<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $this->fetch('page-title'); ?></title>
        <meta name="description" content="<?php echo $this->fetch('page-description'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <?php echo $this->Html->css('main'); ?>
    </head>
    <body class="tpl-<?php echo $this->fetch('template'); ?> page-<?php echo $this->fetch('page'); ?>">

		<header class="page-head">
		<?php echo $this->element('header'); ?>
		</header>
		
		<section class="page-content">
		<?php echo $this->fetch('content'); ?>
		</section>

		<footer class="page-foot">
		<?php echo $this->element('footer'); ?>
		</footer>

		<script src="js/zepto.js"></script>
		<script src="js/main.js"></script>
		
    </body>
</html>
