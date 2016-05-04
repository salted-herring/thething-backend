<?php

class Endpoint extends DataObject {
	protected static $db = array(
		'url'	=>	'Varchar(256)',
		'key'	=>	'Varchar(64)',
		'value'	=>	'Varchar(256)'
	);
	
	protected static $has_one = array(
		'App'	=>	'App'
	);
	
	public static $summary_fields = array(
		'url','ApiKey'
	);
	
	public function ApiKey() {
		return $this->key . ': ' . $this->value;
	}
	
	public function title() {
		return $this->url;		
	}
}