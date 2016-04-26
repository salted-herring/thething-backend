<?php
/*
 * @file HTMLTextAreaValueField.php
 *
 * A text area field that accepts html
 */
class HTMLTextAreaValueField extends ValueInstance
{
    private static $singular_name = 'HTML Textarea Value Field';
    private static $plural_name = 'HTML Textarea Value Fields';

    private static $db = array(
        'Value' => 'HTMLVarchar(512)'
    );

    public function getFieldTemplate()
    {
        $name = $this->getFieldLabel();
        $field = TextareaField::create($name)
                    ->setDescription('Limited to 512 characters.');

        if ($this->Value) {
            $field->setValue($this->Value);
        }

        return $field;
    }
}
