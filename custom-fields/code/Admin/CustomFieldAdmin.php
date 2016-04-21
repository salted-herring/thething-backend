<?php
    
class CustomFieldAdmin extends ModelAdmin {
   private static $managed_models = array('CustomField');
   static $url_segment = 'custom-fields';
   static $menu_title = 'Custom Fields';
   
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
}