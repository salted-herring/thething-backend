<?php
/*
 * @file AdditionalData.php
 *
 * Designed to allow additional data to populate a relationship field like
 * a select list, radio option set or checkbox set.
 */
class AdditionalData extends DataObject
{
	private static $db = array(
		'SortOrder' => 'Int'
	);

	private static $has_one = array(
		'Field'	=> 'CustomField'
	);

	private static $default_sort = 'SortOrder ASC';

	/**
	 * Return the grid display of the relationship.
	 * */
	public static function displayGridFieldConfig($config)
	{

	}


	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->removeByName('SortOrder');

		return $fields;
	}
}
