<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
		<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('main');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		?>
	</head>
	<body>
		<?php echo $this->Session->flash(); ?>

		<?php echo $this->fetch('content'); ?>
	</body>
</html>
