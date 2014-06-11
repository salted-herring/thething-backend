<?php

class BlogCategoryAdmin extends DataExtension {
	public function updateCMSFields(FieldList $fields){
		$fields->removeByName('BlogCategories');
		$fields->removeByName('ExistingCategories');
		
		$fields->addFieldToTab('Root.Categories',$dd = new ListboxField('BlogCategories', 'Categories'));
		
		$dd->setSource(BlogCategory::get()->map('ID', 'Title')->toArray());
		$dd->setMultiple(true);
		
        // Try to fetch categories from cache                
       /*
 $categories = $this->getAllBlogCategories();
        if($categories->count() >= 1){
            $cacheKey = md5($categories->sort('LastEdited', 'DESC')->First()->LastEdited);
            $cache = SS_Cache::factory('BlogCategoriesList');
            if(!($categoryList = $cache->load($cacheKey))) {
                $categoryList = "<ul>";
                foreach ($categories->column('Title') as $title) {
                    $categoryList .= "<li>" .Convert::raw2xml($title). "</li>";
                }
                $categoryList .="</ul>";
                $cache->save($categoryList, $cacheKey);
            }
        } else {
            $categoryList="<ul><li>No categories exist. Categories can be added from the BlogTree or the BlogHolder page.</li></ul>";            
        }

        //categories tab
        $gridFieldConfig = GridFieldConfig_RelationEditor::create();
        $fields->addFieldToTab('Root.Categories', GridField::create('BlogCategories', 'Blog Categories', $this->owner->BlogCategories(), $gridFieldConfig));
        $fields->addFieldToTab('Root.Categories',
                ToggleCompositeField::create(
                        'ExistingCategories',
                        'View Existing Categories',
                        array(
                                new LiteralField("CategoryList", $categoryList)
                        )
                )->setHeadingLevel(4)
        );

        // Optionally default category to current holder
        if(Config::inst()->get('BlogCategory', 'limit_to_holder')) {
            $holder = $this->owner->Parent();
            $gridFieldConfig->getComponentByType('GridFieldDetailForm')
                ->setItemEditFormCallback(function($form, $component) use($holder) {
                    $form->Fields()->push(HiddenField::create('ParentID', false, $holder->ID));
                });
        }
*/
    }
}