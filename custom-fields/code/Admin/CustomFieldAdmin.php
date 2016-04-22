<?php
    
class CustomFieldAdmin extends ModelAdmin {
   private static $managed_models = array('CustomForm');
   static $url_segment = 'custom-forms';
   static $menu_title = 'Custom Forms';
   
/*
   public function getEditForm($id = null, $fields = null) {
        $form = parent::getEditForm($id, $fields);
        $gridField = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
		$gridField->getConfig()
					->removeComponentsByType('GridFieldAddNewButton')
			        ->addComponent($multi = new GridFieldAddNewMultiClass())
			        ->removeComponentsByType('GridFieldPaginator')
			        ->addComponent(new GridFieldPaginatorWithShowAll(30));
		
        return $form;
    }
*/
}