<?php
/*
 * @file App.php
 *
 * create a REST-ful app, and hook endpoints
 */
class App extends CustomForm {
	protected static $db = array(
		'AppName'		=>	'Varchar(128)',
		'AppDes'			=>	'Varchar(256)',
		'AppKey'			=>	'Varchar(256)',
		'AppSecret'		=>	'Varchar(256)',
		'Domains'		=>	'Text',
		//endpoint,
		'SaveData'		=>	'Boolean',
		'source'			=>	'Varchar(256)',
		'url'			=>	'Varchar(256)',
		'key'			=>	'Varchar(64)',
		'value'			=>	'Varchar(256)',
		'map'			=>	'Text',
		'UseRaw'			=>	'Boolean',
		'UniqueField'	=>	'Varchar(100)'
	);
	
	protected static $extensions = array(
		'APICache'
	);
	
	/*protected static $has_many = array(
		'EndPoint'		=>	'Endpoint'
	);*/
	
	public static $summary_fields = array(
		'AppName', 'AppKey'
	);
	
	public function getTitle() {
		return $this->title();
	}
	
	public function title() {
		return $this->AppName;
	}
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		//$fields->removeByName();
		$fields->removeFieldsFromTab('Root.Main',
				array(
					'AppSecret',
					'Name',
					'URL'
				)
		);
		
		$fields->addFieldsToTab('Root.Endpoint',
				array(
					$fields->fieldByName('Root.Main.source'),
					$fields->fieldByName('Root.Main.url'),
					$fields->fieldByName('Root.Main.key'),
					$fields->fieldByName('Root.Main.value'),
					$fields->fieldByName('Root.Main.UseRaw'),
					$fields->fieldByName('Root.Main.map')
				)
		);		
		
		if (!$this->ID) {
			$fields->removeByName('AppKey');
			$fields->removeByName('SaveData');
		}else{
			$appname = $fields->fieldByName('Root.Main.AppName')->performReadonlyTransformation();
			$public = $fields->fieldByName('Root.Main.AppKey')->performReadonlyTransformation();
			$fields->addFieldToTab('Root.Main', $appname, 'AppDes');
			$fields->addFieldToTab('Root.Main', $public, 'AppDes');
		}
		$fields->removeByName('UniqueField');
		if ($this->exists()) {
			$unique = DropdownField::create('UniqueField','Unique field', $this->CustomFields()->map('Title','Title'))->setEmptyString('- Select one -');
			$fields->addFieldToTab('Root.CustomFields', $unique);
		}
		
		return $fields;
	}
	
	protected function validate() {
		$result = ValidationResult::create();
		if (!$this->ID) {
			if (DataObject::get_one('App', array('AppName'=>Utilities::sanitiseClassName($this->AppName)))) {
				$result->error('App name is not available');
			}
		}else{
			if ($this->SaveData && (!$this->CustomFields()->exists() || $this->CustomFields()->count() == 0)) {
				$result->error('You have no field to contain the foreign data');
			}
		}
		$this->extend('validate', $result);
		return $result;
	}
	
	protected function getEndpoint() {
		$absoluteEndpoint = Director::absoluteURL(Config::inst()->get($this->ClassName, 'ApiEndpoint') . $this->AppKey);
		
		return $this->linkEndpoint($absoluteEndpoint);
	}
	
	public function onBeforeWrite() {
		if (!$this->ID) {
			$app = Utilities::sanitiseClassName($this->AppName);
			$this->AppKey = $this->generate_key($app);
		}
		parent::onBeforeWrite();
	}
	
	public function fetch($params = array()) {
		if ($this->SaveData && $this->Submissions()->count() > 0) {
			
			$submission = $this->Submissions();
			if (count($params) > 0) {
				$query = Utilities::paramStringify($params, '&');
			}
			
			
			$data = RelationDataFormatter::format($this->Submissions());
			
			return $data;
		}
		
		if (!empty($this->url)) {
			$query = Utilities::paramStringify($params, '&');
			
			$data = $this->doFetch($query);
			if (is_string($data)) {
				$data = json_decode($data);
			}
			
			if ($this->SaveData && !empty($this->UniqueField)) {
				$this->doSaveData($data, $query);
			}
			
			return $data;
		}
		
		return array();
	}
	
	private function canSaveItem($item) {
		$name = $this->UniqueField;
		if (!empty($name) && !empty($item[$name])) {
			
			$key = md5($name . $item[$name]);
			$submissions = $this->Submissions()->filter(array('identifiler',$key));
			return $submission->count > 0 ? false : $key;
		}
		
		return false;
	}
	
	private function doSaveData($data, $query) {
		if (!empty($data)) {
			foreach ($data as $item) {
				if ($identifier = $this->canSaveItem($item)) {
					$formSheet = $this->getStructure();
					$formSheet = $formSheet['fields'];
					foreach ($item as $field => $value) {
						if (!empty($formSheet[$field])) {
							$formSheet[$field]['value'] = $value;
						}
					}
					$this->saveForeignData($formSheet, $identifier, $query);
				}
			}
		}
	}
	
	private function doFetch($query) {
		$api = $this->url;
		if ($this->key && $this->value) {
		 	$api .= '?' . $this->key . '=' . $this->value;
		}
		
		if (strlen($query) > 0) {
			$api .= $query;
		}
		
		$data = DataLib::fetch($api);
		
		if (!$this->UseRaw && !empty($this->map) && !empty($data)) {
			return $this->translate($data);
		}
		
		return $data;
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
	
	private function translate($data) {
		try {
			$data = $this->findSource($data);
			$scheme = $this->makeScheme();
			$processors = $this->initProcessors();
			foreach ($data as &$item) {
				$item = is_object($item) ? ((array) $item) : $item;
				$item = array_intersect_key($item, $scheme);
				foreach ($scheme as $oldKey => $newKey) {
					$item[$newKey] = $item[$oldKey];
					if (!empty($processors[$oldKey])) {
						$processor = new $processors[$oldKey];
						$processor->process($item[$newKey]);
					}
					if ($oldKey != $newKey) {
						unset($item[$oldKey]);
					}
				}
			}
			return $data;
		} catch (Exception $e) {
			return $data;
		}
		
		return $data;
	}
	
	private function findSource($data) {
		$data = json_decode($data);
		$map = json_decode($this->map);
		$ladders = explode('.', $map->data_source);
		foreach ($ladders as $ladder) {
			$data = $data->$ladder;
		}
		
		return $data;
	}
	
	private function makeScheme() {
		$scheme = array();
		$map = json_decode($this->map);
		$fields = (array) $map->field_mappings;
		foreach ($fields as $key => $field) {
			$scheme[$key] = $field->field;
		}
		
		return $scheme;
	}
	
	private function initProcessors() {
		$processors = array();
		$map = json_decode($this->map);
		$fields = (array) $map->field_mappings;
		foreach ($fields as $key => $field) {
			if (!empty($field->processor)) {
				$processors[$key] = $field->processor;
			}
		}
		
		return $processors;
	}
}