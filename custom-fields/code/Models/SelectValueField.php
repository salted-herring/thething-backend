<?php
/*
 * @file SelectValueField.php
 *
 * An select field.
 */
class SelectValueField extends ValueInstance {

	private static $singular_name = 'Select Field';
	private static $plural_name = 'Select Fields';

	private static $db = array(
		'Value'		=> 'Varchar(128)'
	);

	private static $has_many = array(
		'Options'	=> 'OptionValueField'
	);

	public function getFieldTemplate()
	{
		$realField = $this->Field();
		$options = null;
		$data = array();

		if ($realField->exists()) {
			$options = $realField->AdditionalData();
		}

		if (!is_null($options)) {
			$data = $options->map('Value', 'Label')->toArray();
		}

    	$field = DropdownField::create($this->Name, $this->Name, $data)
					->setEmptyString('(select one)');


    	return $field;
	}

	public function onBeforeWrite() {
		parent::onBeforeWrite();

		$this->Value = $this->Value;
	}


/*
	public function getCMSFields()
	{
		$fields = parent::getCMSFields();

		if ($this->exists()) {
			$fields->addFieldToTab('Root.Main',
				new GridField('Options', 'Option Fields', $this->Options())
			);
		}

		return $fields;
	}
*/
}
