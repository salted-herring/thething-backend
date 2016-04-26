<?php
/*
 * @file CustomField.php
 *
 * Base Custom Field.
 */
class CustomField extends DataObject {
    private static $db = array(
        'Title'             	=> 'Varchar(80)',
        'Label'             	=> 'Varchar(80)',
        'DataType'          	=> 'Varchar(30)'
    );

    private static $has_one = array(
        'Form'              	=> 'CustomForm'
    );

    private static $has_many = array(
	    'AdditionalData'		=> 'AdditionalData'
    );

    private static $summary_fields = array(
        'Title',
        'displayDataType'
    );

    private static $field_labels = array(
        'displayDataType'   	=> 'Field Type'
    );

    private static $searchable_fields = array(
        'Title'
    );

    public function displayDataType()
    {
        foreach (Config::inst()->get('CustomField', 'Types') as $field) {
            foreach ($field as $key => $value) {
                if ($key == $this->DataType) {
                    return $value;
                }
            }
        }

        return $this->DataType;
    }

    public function getFieldName()
    {
	    $filter = new URLSegmentFilter();
        return $filter->filter(strtolower($this->Title));
    }

    public function onBeforeWrite() {
	    parent::onBeforeWrite();

		$filter = new URLSegmentFilter();
        $this->Title = $filter->filter($this->Title);
    }


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('AdditionalData');

        $fields->fieldByName('Root.Main.Title')->setDescription('The title must not contain any spaces.');

        $fields->fieldByName('Root.Main.Label')->setDescription('Leave blank to use the title field.');

        $values = array();

        foreach (Config::inst()->get('CustomField', 'Types') as $field) {
            foreach ($field as $key => $value) {
                $values[$key] = $value;
            }
        }

        $dd = DropdownField::create('DataType', 'Data Type', $values)
                ->setEmptyString('(Select one)');

        if ($this->DataType) {
	        $dd = $dd->performReadOnlyTransformation();
        } else {
	        $dd->setDescription('Once saved, the type cannot be changed.');
        }

        $fields->replaceField('DataType', $dd);

        $additional = Config::inst()->get('CustomField', 'AdditionalData');

        if ($this->DataType) {
	        $additionalType = null;
	        $additionalValue = null;

	        foreach (Config::inst()->get('CustomField', 'AdditionalData') as $field) {
            	foreach ($field as $key => $value) {
                	if ($key == $this->DataType) {
	                	$additionalType = $value;
	                	break;
                	}
            	}
        	}

        	if (!is_null($additionalType)) {

	        	$gridField = new GridField('AdditionalData', 'Additional Data', $this->AdditionalData(),
					$config = GridFieldConfig_RelationEditor::create()
				);

				$gridField->getConfig()
					->removeComponentsByType('GridFieldAddNewButton')
					->removeComponentsByType('GridFieldAddExistingAutocompleter')
					->removeComponentsByType('GridFieldDeleteAction')
					->removeComponentsByType('GridFieldPaginator')
					->addComponent($multi = new GridFieldAddNewMultiClass())
					->addComponent(new GridFieldDeleteAction())
					->addComponent(new GridFieldPaginatorWithShowAll(30))
					->addComponent(new GridFieldOrderableRows('SortOrder'));

				$multi->setClasses(array(
					$additionalType
				));

				$additionalType::displayGridFieldConfig($gridField);

 				$fields->addFieldToTab('Root.Main', $gridField);
        	}
        }

        $fields->changeFieldOrder(array(
	        'FormID',
	        'DataType',
	        'Title',
	        'Label'
        ));

        return $fields;
    }
}
