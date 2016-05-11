<?php
/*
 * @file DatetimeValueField.php
 *
 * Datetime value field.
 */
class DatetimeValueField extends ValueInstance
{
    private static $singular_name = 'Datetime Field';
    private static $plural_name = 'Datetime Fields';

    private static $db = array(
        'Value'     => 'SS_Datetime'
    );

    public function getFieldTemplate()
    {
        $name = $this->getFieldLabel();
       
		$field = new DatetimeField($name);
		$field->setConfig('datavalueformat', 'yyyy-MM-dd HH:mm:ss');
		$field->getDateField()->setConfig('showcalendar', true);
        if ($this->Value) {
            $field->setValue($this->Value);
        }

        return $field;
    }
}
