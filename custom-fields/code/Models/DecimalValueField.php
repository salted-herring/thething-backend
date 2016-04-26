<?php
/*
 * @file DecimalValueField.php
 *
 * A decimal field type
 */
class DecimalValueField extends ValueInstance
{

    private static $singular_name = 'Decimal Field';
    private static $plural_name = 'Decimal Fields';

    private static $db = array(
        'Value' => 'Decimal'
    );

    public function getFieldTemplate()
    {
        $field = NumericField::create($this->Name);

        if ($this->Value) {
            $field->setValue($this->Value);
        }

        return $field;
    }

    /**
     * Convert to decimal.
     * */
    public function onBeforeWrite() {
        parent::onBeforeWrite();

        $this->Value = (float)$this->Value;
    }
}
