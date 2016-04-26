<?php
/*
 * @file DateValueField.php
 *
 * Date value field.
 */
class DateValueField extends ValueInstance
{
    private static $singular_name = 'Date Field';
    private static $plural_name = 'Date Fields';

    private static $db = array(
        'Value'     => 'Date'
    );

    public function getFieldTemplate()
    {
        $name = $this->getFieldLabel();
        $field = DateField::create($name)
                    ->setConfig('showcalendar', true);

        if ($this->Value) {
            $field->setValue($this->Value);
        }

        return $field;
    }
}
