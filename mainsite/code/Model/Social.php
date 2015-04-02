<?php

class OpenGraph extends DataObject {
	private static $db = array(
		'OGTitle' => 'Varchar(255)',
        'OGDescription' => 'Varchar(255)'
	);
	private static $has_one =  array(
		'OGImage' => 'Image'/*
,
		'CustomSiteConfig' => 'CustomSiteConfig',
		'Page' => 'Page'
*/
	);
	
	private static $belongs_to = array(
		'Page' => 'Page'
	);
	
	/*
public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->push(new TextField('OGDescription'));
		
		return $fields;
	}
*/
}