<?php
/*
 * @file IntValueField.php
 *
 * An integer value field
 */
class IntValueField extends ValueInstance {

	private static $singular_name = 'Integer Field';
	private static $plural_name = 'Integer Fields';

	private static $db = array(
    	'Value' => 'Varchar(256)'
	);

	public function getFieldTemplate() {
    	$field = NumericField::create($this->Name);

    	if ($this->Value) {
        	$field->setValue($this->Value);
    	}

    	return $field;
	}
}
