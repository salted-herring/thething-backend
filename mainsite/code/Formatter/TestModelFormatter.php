<?php

class TestModelFormatter implements IRestSerializeFormatter {

    public static function format($data, $access=null, $fields=null) {
        $output = [
            'id' => $data->ID,
            'title' => $data->Title,
            'image' => null
        ];
        
        if ($data->Image() && $data->Image()->exists()) {
            $output['image'] = array(
                'id' => $data->Image()->ID,
                'url' => $data->Image()->getAbsoluteURL()
            );
        }
        
        return $output;
    }
}