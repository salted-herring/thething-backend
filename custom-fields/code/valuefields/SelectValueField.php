<?php
/*
 * @file SelectValueField.php
 *
 * An select field.
 */
class SelectValueField extends ValueInstance
{
    private static $singular_name = 'Select Field';
    private static $plural_name = 'Select Fields';

    private static $db = array(
        'Value'             => 'Varchar(128)'
    );

    public function getFieldTemplate()
    {
        $realField = $this->Field();
        $options = null;
        $data = array();
        $name = $this->getFieldLabel();

        if ($realField->exists()) {
            $options = $realField->AdditionalData();
        }

        if (!is_null($options)) {
            $data = $options->map('Value', 'Label')->toArray();
        }

        $field = DropdownField::create($name, $name, $data)
                    ->setEmptyString('(select one)');

        if ($this->Value) {
            $field->setValue($this->Value);
        }

        return $field;
    }
}
