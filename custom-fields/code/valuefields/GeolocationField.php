<?php
/*
 * @file GeolocationField.php
 *
 * A geo location value field
 */
class GeolocationField extends ValueInstance
{
    private static $singular_name = 'Geolocation Field';
    private static $plural_name = 'Geolocation Fields';

    private static $db = array(
        'Value' => 'Varchar(256)'
    );

    public function getFieldTemplate()
    {
        $name = $this->getFieldLabel();
        $field = TextField::create($name)->setDescription('format: lat,lng');

        if ($this->Value) {
            $field->setValue($this->Value);
        }

        return $field;
    }
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		if (strpos($this->Value, ' ') !== false) {
			$this->Value = str_replace(' ', '', $this->Value);
		}		
	}
	
	public function test($value) {
		if (count(explode(',', $value)) != 2) {
			return false;
		}
		
		return true;
	}
}
