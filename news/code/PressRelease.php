<?php

class PressRelease extends NewsArticle {
	public function getCMSFields() {

		$fields = parent::getCMSFields();
		
		$fields->removeFieldsfromTab('Root.Main', array('TemplateShape', 'AttachedImage'));

		return $fields;
	}
	
	public function isLatest() {
	   $articles = PressRelease::get();
	   if($articles) {
		   return $articles->first()->ID == $this->ID;
	   }
	   
	   return false;
   }
}


class PressRelease_Controller extends NewsArticle_Controller {
	
}