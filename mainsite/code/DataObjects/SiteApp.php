<?php

class SiteApp extends CustomForm {
	protected static $db = array (
		'AppName'		=>	'Varchar(128)',
		'AppDes'			=>	'Varchar(256)',
		'AppKey'			=>	'Varchar(256)'
	);
	
	public static $summary_fields = array(
		'AppName', 'AppKey'
	);
	
	public function fetch($params = array()) {
		$data = array();
		
		return $data;
	}
	
	public function getTitle() {
		return $this->Title();
	}
	
	public function Title() {
		return $this->AppName;
	}
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeFieldsFromTab('Root.Main',
				array(
					'AppSecret',
					'Name',
					'URL'
				)
		);
		
		if (!$this->ID) {
			$fields->removeByName('AppKey');
		}else{
			$appname = $fields->fieldByName('Root.Main.AppName')->performReadonlyTransformation();
			$public = $fields->fieldByName('Root.Main.AppKey')->performReadonlyTransformation();
			$fields->addFieldToTab('Root.Main', $appname, 'AppDes');
			$fields->addFieldToTab('Root.Main', $public, 'AppDes');
		}
		return $fields;
	}
	
	public function onBeforeWrite() {
		if (!$this->ID) {
			$app = Utilities::sanitiseClassName($this->AppName);
			$this->AppKey = $this->generate_key($app);
		}
		parent::onBeforeWrite();
	}
	
	protected function validate() {
		$result = ValidationResult::create();
		if (!$this->ID) {
			if (DataObject::get_one('App', array('AppName'=>Utilities::sanitiseClassName($this->AppName)))) {
				$result->error('App name is not available');
			}
		}
		$this->extend('validate', $result);
		return $result;
	}
	
	private function generate_key($app) {
		//expose encrytion algorithm to public, so we can change it later (put in config.yml)
		$generator = new RandomGenerator();
        $tokenString = $generator->randomToken();
		$e = PasswordEncryptor::create_for_algorithm('blowfish');
        $salt = $e->salt($tokenString);
        $token = sha1($e->encrypt($tokenString, $salt)) . substr(md5($this->Created.$app), 7);
        return $token;
	}
	
	protected function getEndpoint() {
		$absoluteEndpoint = Director::absoluteURL(Config::inst()->get($this->ClassName, 'ApiEndpoint') . $this->AppKey);
		
		return $this->linkEndpoint($absoluteEndpoint);
	}
}