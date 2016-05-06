<?php
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
	
	public function post($request) {
		$app		=	DataObject::get_one('SiteApp', array('AppKey' => $request->param('ID')));
		$submission	=	$request->postVar('submission');
		if (!empty($submission)) {
			$form_id = $submission['form_id'];
			$fields = $submission['fields'];
			
			
			$form = DataObject::get_one('CustomForm', array('ID' => $form_id));
			$record	 = new CustomSubmission();
			$record->FormID = $form_id;
			
			foreach ($fields as $name => $struct) {
				$field = $struct['type'];
				$value = $struct['value'];
				$field_object = new $field;
				$field_object->Name = $name;
				$field_object->Value = $value;
				$field_id = $field_object->write();
				$record->Fields()->add($field_id);
			}
			$record->write();
			
		}
		return $request->postVars();
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
				if (!empty($app->CustomFields())) {
					$fields = $app->CustomFields()->map('Title','DataType')->toArray();
					$output['app_structure'] = array(
						'form_id'	=>	$app->ID,
						'fields'	=>	$fields
					);
				}else{
					$output['app_structure'] = array();
				}
				
				return $output;
			}
			
			$output['app_data']	=	$app->fetch($params);
		}
        return $output;
		
    }
}
