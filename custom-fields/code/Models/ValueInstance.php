<?php
/*
 * @file ValueInstance.php
 *
 * Instance value data for custom fields.
 */
class ValueInstance extends DataObject {
	private static $db = array(
    	'Value'         => 'Int'
	);
	
	private static $has_one = array(
    	'CustomField'   => 'CustomField'
	);
	
	private static $summary_fields = array(
    	'Value'
	);
}