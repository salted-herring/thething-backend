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
    	'Submission'    => 'CustomSubmission'
	);

	private static $summary_fields = array(
    	'Name',
    	'Value'
	);

	public function Value() {
    	return '';
	}
}
