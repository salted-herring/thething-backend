<?php use Ntb\RestAPI\BaseRestController as BaseRestController;

/**
 * User controller is the controller for the member resource.
 */
class TestModelController extends BaseRestController {

    private static $allowed_actions = array (
        'get' => true,
    );

    public function get($request) {
        $data = TestModel::get();
        $meta = [
            'count' => $data->Count(),
            'timestamp' => time()
        ];

        $output = [
            'tests' => array_map(function($testModel) {
                return TestModelFormatter::format($testModel);
            }, $data->toArray()),
            'meta' => $meta
        ];

        return $output;
    }
}
