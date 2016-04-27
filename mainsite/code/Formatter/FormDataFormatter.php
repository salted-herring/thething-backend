<?php

class FormDataFormatter implements IRestSerializeFormatter
{

    public static function format($data, $access=null, $fields=null)
    {
        $output = [
            'id' => $data->ID,
            'name' => $data->Name,
            'submissions' => null
        ];

        if ($data->Submissions() && $data->Submissions()->exists()) {
            $submissions = array();

            foreach ($data->Submissions() as $submission) {
                $current = array(
                    'id'        => $submission->ID,
                    'fields'    => null
                );

                if ($submission->Fields()->count() > 0) {
                    $current['fields'] = array();
                }

                foreach ($submission->Fields() as $field) {
                    $realField = $field->Field();

                    $current['fields'][$field->Name] = array(
                        'type'  => $field->ClassName,
                        'value' => $field->getFieldValue(),
                        'label' => $realField ? $realField->Label : ''
                    );
                }

                $submissions[] = $current;
            }

            $output['submissions'] = $submissions;
        }

        return $output;
    }
}
