<?php use Ntb\RestAPI\BaseRestController as BaseRestController;
/**
 * @file DataController.php
 *
 * Controller to present the data from forms.
 * */
class DataController extends BaseRestController {

    private static $allowed_actions = array (
        'get' => 'true'
    );

    private static $https_only = false;

    public function get($request) {
        $data = CustomForm::get()->filter('URL', $request->param('ID'));
        $meta = [
            'count' => $data->Count(),
            'timestamp' => time()
        ];

        $output = [
            'data' => array_map(function($model) {
                return FormDataFormatter::format($model);
            }, $data->toArray()),
            'meta' => $meta
        ];

        return $output;
    }

    // hack! Needs to be properly implemented!
    public function checkAccessAction($action) {
        return true;
    }
}
