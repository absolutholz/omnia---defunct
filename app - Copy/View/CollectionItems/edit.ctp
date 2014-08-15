<?php 
	$this->assign('template', 'form');
	$this->assign('page', 'collectionitem_update');
	//var_dump($collection_item);die;
?>

<header class="stripe">
	<?php 
		echo $this->element('breadcrumbs', array("crmbs" => array(
			array("href" => Router::url(array("controller" => 'collections', "action" => 'view', "collection_id" => $collection['Collection']['id'])), "text" => $collection['Collection']['name'], "title" => $collection['Collection']['name']),
			array("href" => Router::url(array("controller" => 'collection_items', "action" => 'view', "collection_id" => $collection['Collection']['id'], "collection_item_id" => $collection_item['CollectionItem']['id'])), "text" => $collection_item['CollectionItem']['name'], "title" => $collection_item['CollectionItem']['name'])
		))); 
	?>

	<h1><?php echo $collection_item['CollectionItem']['name']; ?></h1>
</header>

<?php
echo $this->Form->create('CollectionItem');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->input('user_id', array("type" => 'hidden'));
echo $this->Form->input('collection_id', array("type" => 'hidden'));
echo $this->Form->input('name');
echo $this->Form->input('notes', array("rows" => '3'));
/*
$iter = 0;
foreach ($collection['Field'] as $field): 

		switch ($field['field_type_id']) {
			case 2: // address
				echo $this->Form->input('CollectionItemField.' . $iter . '.value', array("rows" => '3', "label" => $field['name']));
				break;
				
			case 3: // on/off
				echo $this->Form->checkbox('CollectionItemField.' . $iter . '.value', array("label" => $field['name']));
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
*/
foreach ($collection['Field'] as $key => $field): 
//	$field = isset($collectionItemFieldValues[$collectionItemField['id']]) ? $collectionItemFieldValues[$collectionItemField['id']]: false;
	$item_field = isset($collection_item['CollectionItemField'][$key]) ? $collection_item['CollectionItemField'][$key] : false;
	
	if ($item_field) {
		// update existing value
		echo $this->Form->input('CollectionItemField.' . $key . '.id', array("type" => 'hidden', "value" => $item_field['id']));
		$value = $item_field['value'];
		/*if ($collectionItemField['field_type_id'] == '2' || $collectionItemField['field_type_id'] == '4') {
			echo $this->Form->input('CollectionItemField.' . $iter . '.value', array("type" => 'text', "label" => $collectionItemField['name'], "rows" => '3', "value" => $field['value']));
		} else {
			echo $this->Form->input('CollectionItemField.' . $iter . '.value', array("type" => 'text', "label" => $collectionItemField['name'], "value" => $field['value']));
		}*/

	} else {
		// insert new value
		echo $this->Form->input('CollectionItemField.' . $key . '.field_id', array("type" => 'hidden', "value" => $field['id']));
		$value = '';
		/*if ($collectionItemField['field_type_id'] == '2') {
			echo $this->Form->input('CollectionItemField.' . $iter . '.value', array("type" => 'text', "label" => $collectionItemField['name'], "rows" => '3', "value" => false));
		} else {
			echo $this->Form->input('CollectionItemField.' . $iter . '.value', array("type" => 'text', "label" => $collectionItemField['name'], "value" => false));
		}*/

	}
	
	switch ($field['field_type_id']) {
		case 2: // address
			echo $this->Form->input('CollectionItemField.' . $key . '.value', array("rows" => '3', "label" => $field['name']));
			break;
			
		case 3: // on/off
			echo $this->Form->checkbox('CollectionItemField.' . $key . '.value', array("value" => 1, "hiddenField" => false));
			echo $this->Form->label('CollectionItemField.' . $key . '.value', $field['name']); 
			break;

		case 4: // multiple values
			echo $this->Form->select('CollectionItemField.' . $key . '.value', explode(';', $field['value']), array("label" => $field['name']));
			break;
			
		default:
			echo $this->Form->input('CollectionItemField.' . $key . '.value', array("label" => $field['name']));
	}
endforeach; ?>

<button type="submit" class="btn">Save</button>

<?php echo $this->Form->end(); ?>