<?php
/*
 * @file CustomField.php
 *
 * Base Custom Field.
 */
class CustomField extends DataObject {
	private static $db = array(
    	'Title'     => 'Varchar(80)',
    	'Label'     => 'Varchar(80)'
	);
	
	private static $has_many = array(
    	'ValueInstances'    => 'ValueInstance'
	);
	
	private static $summary_fields = array(
    	'Title'
	);
	
	private static $searchable_fields = array(
    	'Title'
	);
	
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$fields->fieldByName('Root.Main.Label')->setDescription('Leave blank to use the title field.');
		
		return $fields;
	}
}