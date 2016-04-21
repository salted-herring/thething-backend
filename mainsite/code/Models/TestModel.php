<?php
/*
 * @file TestModel.php
 *
 * Test Model.
 */
class TestModel extends DataObject {
	private static $db = array(
    	'Title'     => 'Varchar(100)',
    	'X'         => 'Int'
	);
	
	private static $has_one = array(
    	'Image'     => 'Image'
	);
	
	private static $api_access = true;
	
	private static $has_many = array(
	);
	
	private static $summary_fields = array(
	);
	
	private static $defaults = array(
	);
	
	private static $searchable_fields = array(
	);
}