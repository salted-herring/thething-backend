<?php
/*
 * @file BooleanValueField.php
 *
 * A boolean field type
 */
class BooleanValueField extends ValueInstance {

	private static $singular_name = 'Boolean Field';
	private static $plural_name = 'Boolean Fields';

	private static $db = array(
    	'Value' => 'Boolean'
	);

	public function getFieldTemplate() {
    	$field = CheckboxField::create($this->Name);

    	if ($this->Value) {
        	$field->setValue($this->Value);
    	}

    	return $field;
	}
}
