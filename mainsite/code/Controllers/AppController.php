<?php
/**
 * @file AppController.php
 *
 * Controller to present the data from forms.
 * */
class AppController extends BaseRestController {

    private static $allowed_actions = array (
        'get' => true,
    );

	public function get($request) {
		//Debugger::inspect($request->param('ID'));
		$app		=	DataObject::get_one('App', array('AppKey' => $request->param('ID')));
		$params		=	$request->getVars();
		$output		=	array();
		if ($app) {
			
			unset($params['url']);
			unset($params['accept']);
			
			$output['app_name']	=	$app->AppName;
			$output['app_id']	=	$app->ID;
			$output['app_data']	=	$app->fetch($params);
		}
        return $output;
    }
}
