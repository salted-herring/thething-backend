<?php
/*
 * @file TextareaValueField.php
 *
 * A text area value field
 */
class TextareaValueField extends ValueInstance {

	private static $singular_name = 'Textarea Field';
	private static $plural_name = 'Textarea Fields';

	private static $db = array(
    	'Value' => 'Varchar(512)'
	);

	public function getFieldTemplate() {
    	$field = TextareaField::create($this->Name)
    				->setDescription('Limited to 512 characters.');

    	if ($this->Value) {
        	$field->setValue($this->Value);
    	}

    	return $field;
	}
}
