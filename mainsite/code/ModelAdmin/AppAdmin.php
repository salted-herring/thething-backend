<?php

class AppAdmin extends ModelAdmin
{
    private static $managed_models = array('App');
    private static $url_segment = 'apps';
    private static $menu_title = 'Apps';
	
	public function getEditForm($id = null, $fields = null) {
		$form = parent::getEditForm($id, $fields);
		
		$grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
		
		$grid->getConfig()
			->removeComponentsByType('GridFieldPaginator')
			->removeComponentsByType('GridFieldDetailForm')
			->addComponents(
				new AppAdminForm(),
				new GridFieldPaginatorWithShowAll(30)
		);
		return $form;
	}
}
