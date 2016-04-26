<?php
/*
 * @file IntValueField.php
 *
 * An integer value field
 */
class IntValueField extends ValueInstance
{
    private static $singular_name = 'Integer Field';
    private static $plural_name = 'Integer Fields';

    private static $db = array(
        'Value' => 'Int'
    );

    public function getFieldTemplate()
    {
        $name = $this->getFieldLabel();
        $field = NumericField::create($name);

        if ($this->Value) {
            $field->setValue($this->Value);
        }

        return $field;
    }
}
