<?php
/*
 * @file CustomField.php
 *
 * Base Custom Field.
 */
class CustomField extends DataObject {
    private static $db = array(
        'Title'             => 'Varchar(80)',
        'Label'             => 'Varchar(80)',
        'DataType'          => 'Varchar(30)'
    );

    private static $has_one = array(
        'Form'              => 'CustomForm'
    );

    private static $summary_fields = array(
        'Title',
        'displayDataType'
    );

    private static $field_labels = array(
        'displayDataType'   => 'Field Type'
    );

    private static $searchable_fields = array(
        'Title'
    );

    public function displayDataType()
    {
        foreach (Config::inst()->get('CustomField', 'Types') as $field) {
            foreach ($field as $key => $value) {
                if ($key == $this->DataType) {
                    return $value;
                }
            }
        }

        return $this->DataType;
    }


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->fieldByName('Root.Main.Title')->setDescription('The title must not contain any spaces.');

        $fields->fieldByName('Root.Main.Label')->setDescription('Leave blank to use the title field.');

        $values = array();

        foreach (Config::inst()->get('CustomField', 'Types') as $field) {
            foreach ($field as $key => $value) {
                $values[$key] = $value;
            }
        }

        $dd = DropdownField::create('DataType', 'Data Type', $values)
                ->setEmptyString('(Select one)');

        if ($this->DataType) {
	        $dd = $dd->performReadOnlyTransformation();
        } else {
	        $dd->setDescription('Once saved, the type cannot be changed.');
        }

        $fields->replaceField('DataType', $dd);

        return $fields;
    }
}
