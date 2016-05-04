<?php

class App extends DataObject {
	protected static $db = array(
		'AppName'		=>	'Varchar(128)',
		'AppDes'			=>	'Varchar(256)',
		'AppKey'			=>	'Varchar(256)',
		'AppSecret'		=>	'Varchar(256)',
		'Domains'		=>	'Text'
	);
	
	protected static $has_many = array(
		'EndPoint'		=>	'Endpoint'
	);
	
	public static $summary_fields = array(
		'AppName', 'AppKey'
	);
	
	public function title() {
		return $this->AppName;
	}
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('AppSecret');
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
	
	public function onBeforeWrite() {
		if (!$this->ID) {
			$app = Utilities::sanitiseClassName($this->AppName);
			$this->AppKey = $this->generate_key($app);
		}
		parent::onBeforeWrite();
	}
	
	public function fetch($params = array()) {
		$dataCollection = array();
		if ($endpoints = $this->EndPoint()) {
			$query = '';
			if (count($params) > 0) {
				foreach ($params as $key => $value) {
					$query .= '&' . $key . '=' . $value;
				}
			}
			
			foreach ($endpoints as $endpoint) {
				$api = $endpoint->url . '?' . $endpoint->key . '=' . $endpoint->value;
				if (strlen($query) > 0) {
					$api .= $query;
				}
				if ($data = DataLib::fetch($api)) {
					$dataCollection[] = json_decode($data);
				}
			}
		}
		
		return $dataCollection;
	}
	
	private function generate_key($app) {
		$generator = new RandomGenerator();
        $tokenString = $generator->randomToken();
		$e = PasswordEncryptor::create_for_algorithm('blowfish');
        $salt = $e->salt($tokenString);
        $token = sha1($e->encrypt($tokenString, $salt)) . substr(md5($this->Created.$app), 7);
        return $token;
	}
	
	/*private static function generate_key() {
        $generator = new RandomGenerator();
        $tokenString = $generator->randomToken();
        $e = PasswordEncryptor::create_for_algorithm('blowfish');
        $salt = $e->salt($tokenString);
        $token = sha1($e->encrypt($tokenString, $salt)) . substr(md5($this->Created.$this->ID), 7);
        return $token;
    }*/
}