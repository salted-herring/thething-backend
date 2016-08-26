<?php
/*
 * @file CustomForm.php
 *
 * A custom form to hold multiple custom fields.
 */
class CustomForm extends DataObject
{
    private static $db = array(
        'Name'  => 'Varchar(100)',
        'URL'   => 'Varchar(256)'
    );

    private static $has_many = array(
        'CustomFields'  => 'CustomField',
        'Submissions'   => 'CustomSubmission'
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $url = $fields->fieldByName('Root.Main.URL');
        $fields->replaceField('URL', $url->performReadOnlyTransformation());

        if ($this->exists()) {
            $endpoint = $this->getEndpoint();
            $fields->addFieldToTab(
                'Root.Main',
                LiteralField::create('Endpoint', 'Endpoint: ' . $endpoint)
            );
        }

        return $fields;
    }

	protected function getEndpoint() {
		$absoluteEndpoint = Director::absoluteURL(Config::inst()->get($this->ClassName, 'ApiEndpoint') . $this->URL);

		return $this->linkEndpoint($absoluteEndpoint);
	}

	protected function linkEndpoint($url) {
		$endpoint = sprintf(
			'<a href="%s?accept=json" target="_blank">%s</a>',
			$url,
			$url
        );
		return $endpoint;
	}

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        $filter = new URLSegmentFilter();
        $this->URL = $filter->filter($this->Name);
    }
	
	public function saveForeignData($fields, $identifier = null, $params = null) {

		if (!empty($fields)) {
			$record	 = new CustomSubmission();
			$record->FormID = $this->ID;
			if (!empty($identifier)) {
				$record->identifier = $identifier;
			}
			
			if (!empty($params)) {
				$record->params = $params;
			}
			foreach ($fields as $name => $struct) {
				$field = $struct['type'];
				$value = $struct['value'];
				$field_object = new $field;
				$field_object->Name = $name;
				$field_object->Value = $value;
				$field_id = $field_object->write();
				$record->Fields()->add($field_id);
			}
			$record_id = $record->write();
			return array('success' => true, 'message' => 'data submitted', 'submission_id' => $record_id);
		}

		return array('success' => false, 'message' => 'missing field(s)');
	}

	public function getStructure() {
		if (!empty($this->CustomFields())) {
			$fields = $this->CustomFields()->map('Title','DataType')->toArray();
			foreach ($fields as $key => &$value) {
				$value = array(
					'type'	=>	$value,
					'value'	=>	null
				);
			}
			return array(
						'form_id'	=>	$this->ID,
						'fields'	=>	$fields
					);
		}

		return array();
	}
}
