<header>
	<h1>Add Field</h1>
</header>

<?php
echo $this->Form->create('Field');
echo $this->Form->input('name');
echo $this->Form->input('field_type_id', array("type" => 'select', "options" => $field_types));
echo $this->Form->input('values', array("rows" => '3'));
echo $this->Form->input('is_groupable', array("type" => 'checkbox'));
echo $this->Form->input('is_required', array("type" => 'checkbox'));
echo $this->Form->end('Create');
?>