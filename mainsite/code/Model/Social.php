<?php

class OpenGraph extends DataObject {
	private static $db = array(
		'OGTitle' => 'Varchar(255)',
		'OGDescription' => 'Varchar(255)'
	);
	private static $has_one =  array(
		'OGImage' => 'Image'
	);
	
	private static $belongs_to = array(
		'Page' => 'Page'
	);
}