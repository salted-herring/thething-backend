<?php use Ntb\RestAPI\IRestSerializeFormatter;

class RelationDataFormatter implements IRestSerializeFormatter
{

    public static function format($relation, $access=null, $fields=null)
	{
		$relationData = array();

		foreach ($relation as $item) {
			$current = array(
				'id'        => $item->ID,
				'fields'    => null
			);

			if ($item->Fields()->count() > 0) {
				$current['fields'] = array();
			}

			foreach ($item->Fields() as $field) {
				$realField = $field->Field();
				$current['fields'][$field->Name] = array(
					'type'  => $field->ClassName,
					'value' => $field->getFieldValue(),
					'label' => ($realField && $realField->Label)? $realField->Label : $field->Name
				);
			}

			$relationData[] = $current;
		}

		return $relationData;
	}
}
