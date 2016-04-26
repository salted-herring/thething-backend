<?php
/*
 * @file ValueInstance.php
 *
 * Instance value data for custom fields.
 */
class ValueInstance extends DataObject {

    private static $db = array(
        'Name'          => 'Varchar(100)'
    );


	private static $has_one = array(
    	'Submission'    => 'CustomSubmission',
    	'Field'			=> 'CustomField'
	);

	private static $summary_fields = array(
    	'Name',
    	'Value'
	);

	public function Value() {
    	return '';
	}

	public function cmsAdditions() {
		return false;
	}

	/**
	 * When saving from the CMS, we may need to override some values directly.
	 * */
	public function saveFromCMS($field, $record, $submission, $write = false)
	{
		$this->Name = $field->Title;
        $this->Value = $record[$field->Title];
        $this->SubmissionID = $submission;
        $this->FieldID = $field->ID;

        if ($write) {
	    	$this->write();
        }
	}

	public function getFieldTemplate()
	{

	}
}
