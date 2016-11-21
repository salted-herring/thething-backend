<?php use Ntb\RestAPI\BaseRestController as BaseRestController;
/**
 * @file FormController.php
 *
 * Controller to present the fields from a form.
 * */
class FormController extends BaseRestController {

    private static $allowed_actions = array (
        'get' => true,
    );

    public function get($request) {
        $data = CustomForm::get()->filter('URL', $request->param('ID'));
		Debugger::inspect($data);
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
