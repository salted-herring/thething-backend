<?php
/*
 * @file CustomSubmission.php
 *
 * Custom Submission
 */
class CustomSubmission extends DataObject {
    private static $db = array(
    );

    private static $has_one = array(
        'Form'  		=> 'CustomForm'
    );

    private static $has_many = array(
        'Fields'    	=> 'ValueInstance'
    );

    private static $summary_fields = array(
	    'ID',
	    'getSummary'
    );

    private static $field_labels = array(
	    'getSummary'	=>	'Summary'
    );


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('Fields');

        $form = $this->Form();
        $formFields = $form->CustomFields();

        foreach ($formFields as $f) {

	        $existing = $this->getExisting($f->Title);

	        if ($existing) {
		        $type = $existing;
	        } else {
		        $type = $f->DataType;
	            $type = new $type();
	            $type->Name = $f->Title;
	        }

            $fields->addFieldToTab('Root.Main', $type->getFieldTemplate());
        }

        return $fields;
    }

    public function onAfterWrite()
    {
        parent::onAfterWrite();

        $form = $this->Form();
        $formFields = $form->CustomFields();

        foreach ($formFields as $f) {

	        $existing = $this->getExisting($f->Title);

	        if ($existing) {
		        $type = $existing;
	        } else {
		        $type = $f->DataType;
	            $type = new $type();
	        }

	        if (array_key_exists($f->Title, $this->record)) {
	            $type->Name = $f->Title;
	            $type->Value = $this->record[$f->Title];
	            $type->SubmissionID = $this->ID;
	            $type->write();
           }
        }
    }

    private function getExisting($title = null)
    {
        $form = $this->Form();
        $formFields = $form->CustomFields()->filter('Title', $title);

        if ($formFields->Count() > 0) {
            $field = $formFields->first();

            $query = array(
	            'Name'			=> $title,
	            'ClassName'		=> $field->DataType,
	            'SubmissionID'	=> $this->ID
            );

            $actualField = DataObject::get_one('ValueInstance', $filter = $query);

            if ($actualField) {
	            $actualField = DataObject::get_by_id($field->DataType, $actualField->ID);
            }

            return $actualField;
        }

        return null;
    }

    public function getSummary() {
	    $fields = array();

	    foreach ($this->Fields() as $field) {
		    $fields[$field->Name] = $field->Value;
	    }

	    return json_encode($fields);
    }
}
