<?php
/*
 * @file HTMLTextValueField.php
 *
 * A text value field that accepts html
 */
class HTMLTextValueField extends ValueInstance {

	private static $singular_name = 'HTML Text Value Field';
	private static $plural_name = 'HTML Text Value Fields';

	private static $db = array(
    	'Value' => 'HTMLVarchar(256)'
	);

	public function getFieldTemplate() {
		$name = $this->getFieldLabel();
    	$field = HTMLEditorField::create($name);

    	if ($this->Value) {
        	$field->setValue($this->Value);
    	}

    	return $field;
	}
}
