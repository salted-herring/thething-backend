<?php

class APICache extends DataExtension {
	
	protected static $db = array(
		'CacheLength'	=>	'Int'
	);
	
	public function updateCMSFields(FieldList $fields) {
		$length = DropdownField::create('CacheLength', 'Cache Length', Config::inst()->get('APICache', 'CacheLength'))
                ->setEmptyString('(Default: 7 days)');
		$fields->addFieldToTab('Root.Main', $length);
	}
}