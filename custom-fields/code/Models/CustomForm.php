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
            $absoluteEndpoint = Director::absoluteURL(Config::inst()->get('CustomField', 'ApiEndpoint') . $this->URL);

            $endpoint = sprintf(
                '<a href="%s?accept=json" target="_blank">%s</a>',
                $absoluteEndpoint,
                $absoluteEndpoint
            );

            $fields->addFieldToTab(
                'Root.Main',
                LiteralField::create('Endpoint', 'Endpoint: ' . $endpoint)
            );
        }

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        $filter = new URLSegmentFilter();
        $this->URL = $filter->filter($this->Name);
    }
}
