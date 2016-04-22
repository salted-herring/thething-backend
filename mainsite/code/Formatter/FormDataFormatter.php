<?php

class FormDataFormatter implements IRestSerializeFormatter {

    public static function format($data, $access=null, $fields=null) {
        $output = [
            'id' => $data->ID,
            'name' => $data->Name,
            'submissions' => null
        ];

        if ($data->Submissions() && $data->Submissions()->exists()) {
			$submissions = array();

			foreach ($data->Submissions() as $submission) {
				$current = array(
					'id'	=> $submission->ID
				);

				foreach ($submission->Fields() as $field) {
					$current[$field->Name] = array(
						'value'	=> $field->Value,
						'label'	=> $field->Label
					);
				}

				$submissions[] = $current;
			}

			$output['submissions'] = $submissions;
        }

        return $output;
    }
}
