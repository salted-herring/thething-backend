<?php
/**
 * @file DataController.php
 *
 * Controller to present the data from forms.
 * */
class DataController extends BaseRestController {

    private static $allowed_actions = array (
        'get' => true,
    );

    public function get($request) {
        $data = CustomForm::get();
        $meta = [
            'count' => $data->Count(),
            'timestamp' => time()
        ];

        $output = [
            'tests' => array_map(function($model) {
                return FormDataFormatter::format($model);
            }, $data->toArray()),
            'meta' => $meta
        ];

        return $output;
    }
}
