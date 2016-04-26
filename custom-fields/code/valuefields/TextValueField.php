<?php
/*
 * @file TextValueField.php
 *
 * A text value field
 */
class TextValueField extends ValueInstance {

	private static $singular_name = 'Text Field';
	private static $plural_name = 'Text Fields';

	private static $db = array(
    	'Value' => 'Varchar(256)'
	);

	public function getFieldTemplate() {
    	$field = TextField::create($name);

    	if ($this->Value) {
        	$field->setValue($this->Value);
    	}

    	return $field;
	}
}
