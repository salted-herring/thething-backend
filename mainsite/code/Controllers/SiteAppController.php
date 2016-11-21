<?php use Ntb\RestAPI\BaseRestController as BaseRestController;
/**
 * @file SiteAppController.php
 *
 * Controller to present the data from forms.
 * */
class SiteAppController extends BaseRestController {

    private static $allowed_actions = array (
		'post'	=>	"->isAuthenticated",
        'get'	=>	true
    );

	private static $extensions = array(
		'APIControllerCache'
	);

	public function post($request) {
		$app		=	DataObject::get_one('SiteApp', array('AppKey' => $request->param('ID')));
		$submission	=	$request->postVar('submission');

		if (!empty($submission)) {
			$form_id = $submission['form_id'];
			if ($form = DataObject::get_one('CustomForm', array('ID' => $form_id))) {
				return $form->saveForeignData($submission['fields']);
			}

			return array('success' => false, 'message' => 'form doesn\'t exist');
		}

		return array('success' => false, 'message' => 'missing submission');
	}

	public function get($request) {

		$app		=	DataObject::get_one('SiteApp', array('AppKey' => $request->param('ID')));
		$params		=	$request->getVars();
		$output		=	array();

		if ($app) {
			//remove unneccessary params
			unset($params['url']);
			unset($params['accept']);

			$output['app_id']	=	$app->ID;
			$output['app_name']	=	$app->AppName;
			$output['app_desc']	=	$app->AppDes;

			if (!empty($params['get']) && $params['get'] == 'structure') {
				$output['app_structure'] = $app->getStructure();
				return $output;
			}

			$output['app_data']	=	$app->fetch($params);
		}
        return $output;

    }
}
