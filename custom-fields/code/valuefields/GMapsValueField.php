<?php
/*
 * @file GMapsValueField.php
 *
 * A google maps field
 */
class GMapsValueField extends ValueInstance
{
    private static $singular_name = 'Google Maps Field';
    private static $plural_name = 'Google Maps Fields';

    private static $db = array(
        'Value' => 'Varchar(256)'
    );

    private static $extensions = array(
        'GMapsObjectExtension'
    );

    public function getFieldTemplate()
    {
        $name = $this->getFieldLabel();
        $field = HiddenField::create($name);

        if ($this->Value) {
            $field->setValue($this->Value);
        }

        return $field;
    }

    public function cmsAdditions($fields)
    {
        $this->updateCMSFields($fields);
    }

    public function getFieldValue()
    {
        return array(
            'GMapLat'   => $this->GMapLat,
            'GMapLon'   => $this->GMapLon
        );
    }
}
