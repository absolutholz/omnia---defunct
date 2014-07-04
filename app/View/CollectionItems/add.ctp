<?php 
	$this->assign('template', 'form');
	$this->assign('page', 'collectionitem_create');
?>

<header class="stripe">
	<?php 
		echo $this->element('breadcrumbs', array("crmbs" => array(
			array("href" => Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection['Collection']['id'])), "text" => $collection['Collection']['name'], "title" => $collection['Collection']['name'])
		))); 
	?>

	<h1>Create Collection Item</h1>
</header>

<?php
echo $this->Form->create('CollectionItem');
echo $this->Form->input('user_id', array("type" => 'hidden', "value" => $user_id));
echo $this->Form->input('collection_id', array("type" => 'hidden', "value" => $collection['Collection']['id']));
echo $this->Form->input('name');
echo $this->Form->input('notes', array("rows" => '3'));

$iter = 0;
foreach ($collection['Field'] as $field): 

		switch ($field['field_type_id']) {
			case 2: // address
				echo $this->Form->input('CollectionItemField.' . $iter . '.value', array("rows" => '3', "label" => $field['name']));
				break;
				
			case 3: // on/off
				echo $this->Form->checkbox('CollectionItemField.' . $iter . '.value', array("label" => $field['name']));
				echo $this->Form->label('CollectionItemField.' . $key . '.value', $field['name']); 
				break;

			case 4: // multiple values
				echo $this->Form->select('CollectionItemField.' . $iter . '.value', explode(';', $field['value']), array("label" => $field['name']));
				break;
				
			default:
				echo $this->Form->input('CollectionItemField.' . $iter . '.value', array("label" => $field['name']));
		}
		echo $this->Form->input('CollectionItemField.' . $iter . '.field_id', array("type" => 'hidden', "value" => $field['id']));

	$iter++;
endforeach;

echo $this->Form->end('Create');
?>