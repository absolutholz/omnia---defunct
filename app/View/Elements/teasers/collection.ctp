<?php //$this->extend('teasers/base'); 
	$collection_id = $collection['Collection']['id'];
	$collection_name = $collection['Collection']['name']; 
?>

<section class="tsr tsr-coll card">
	<a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id)); ?>" title="View <?php echo $collection_name; ?>">
		<h1><?php echo $collection_name; ?></h1>
		<h2><?php echo $collection['Collection']['description']; ?></h2>
		<em><?php echo $collection['Collection']['collection_item_count']; ?></em>
	</a>
	<ul class="actions">
		<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection_id)); ?>" title="View <?php echo $collection_name; ?>">View</a></li>
		<li><a href="<?php echo Router::url(array("controller" => 'collections', "action" => 'participate', "collection_id" => $collection_id)); ?>" title="Participate in <?php echo $collection_name; ?>">Participate</a></li>
	</ul>
</section>
