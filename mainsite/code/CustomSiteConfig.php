<?php 
class CustomSiteConfig extends DataExtension { 
	
	public static $db = array(
		'GoogleSiteVerificationCode' => 'Varchar(128)',
		'GoogleAnalyticsCode' => 'Varchar(20)',
		'SiteVersion' => 'Varchar(10)',
		'GoogleCustomCode' => 'HTMLText',
		
		'OGTitle' => 'Varchar(255)',
        'OGDescription' => 'Varchar(255)',
        
        'FacebookURL' => 'Varchar(255)',
        'TwitterURL' => 'Varchar(255)',
        'PinterestURL' => 'Varchar(255)'
	);
	public static $has_one =  array(
		'OGImage' => 'Image'
	);
	
	public function updateCMSFields(FieldList $fields) {		
		$fields->addFieldToTab("Root.Google", new TextField('GoogleSiteVerificationCode', 'Google Site Verification Code'));
		$fields->addFieldToTab("Root.Google", new TextField('GoogleAnalyticsCode', 'Google Analytics Code'));
		$fields->addFieldToTab("Root.Google", new TextareaField('GoogleCustomCode', 'Custom Google Code'));
		
		$fields->addFieldToTab('Root.Main', new TextField('SiteVersion', 'Site Version'));
		
		$og = ToggleCompositeField::create(
			'OG',
			new LabelField('Open', 'Open Graph Tags - for Facebook sharing'),
			array(
				new TextField('OGTitle', 'Title'),
				new TextField('OGDescription', 'Description'),
				new UploadField('OGImage', 'Image')
			)
		);
		$og->setStartClosed(false);
		$fields->addFieldToTab('Root.Social', $og);
	}
	
}
