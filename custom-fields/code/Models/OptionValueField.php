<?php
/*
 * @file OptionValueField.php
 *
 * An option attached to a select field.
 */
class OptionValueField extends AdditionalData
{
	private static $singular_name = 'Option Field';
	private static $plural_name = 'Option Fields';

    private static $db = array(
        'Label' 	=> 'Varchar(128)',
        'Value'		=> 'Varchar(128)'
    );

    private static $summary_fields = array(
	    'Label',
	    'Value'
    );

    /**
	 * Return the grid display of the relationship.
	 * */
	public static function displayGridFieldConfig($gridField)
	{
		$gridField->setTitle('Options');
		$config = $gridField->getConfig();
		$dataColumns = $config->getComponentByType('GridFieldDataColumns');

        $dataColumns->setDisplayFields(array(
            'Label'	=> 'Label',
            'Value'	=> 'Value'
        ));
	}

	public function getTitle() {
		return $this->Label;
	}
}
