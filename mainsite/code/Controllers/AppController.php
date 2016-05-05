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
		
		$app		=	DataObject::get_one('App', array('AppKey' => $request->param('ID')));
		$params		=	$request->getVars();
		$output		=	array();
		
		if ($app) {
			
			// compare host against domain list
			$match = false;
			$origin = $request->getHeader('Host');
			$domains = explode("\r\n", $app->Domains);
			foreach ($domains as $domain) {
				$domain = trim($domain);
				if (fnmatch($domain, $origin)) {
					$match = true;
					break;
				}
			}
			
			if (!$match) {
				$err = $this->httpError(403, 'Host disallowed');
				return $err;
			}
			
			//remove unneccessary params
			unset($params['url']);
			unset($params['accept']);
			
			$output['app_name']	=	$app->AppName;
			$output['app_id']	=	$app->ID;
			$output['app_data']	=	$app->fetch($params);
		}
        return $output;
		
    }
}
