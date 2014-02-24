<?php

	class NewsArticle extends Page {
	
		private static $db = array(
			'Date' => 'Date',
			'IntroText' => 'Varchar(512)',
			'TemplateShape' => 'Enum("Rectangle,Circle")'
		);
		
		private static $has_one = array(
			'AttachedImage' => 'Image'
		);
		
		static $defaults = array(
			'ShowInMenus' => false
		);
		
		static $summary_fields = array(
			"Date" => "Date",
			"Title" => "Title"
		);
		
		static $default_sort = "Date DESC, Created DESC";
		static $default_parent = "news";
		static $description = 'An individual news item displayed on a News holder page';		
		static $singular_name = 'News Article';
		static $plural_name = 'News Articles'; 
		static $icon = "basic-news/images/newspaper";
		
		public function populateDefaults() {
  			$this->Date = date('Y-m-d');
  			parent::populateDefaults();
		}
					
		public function getCMSFields() {

	    	$this->beforeUpdateCMSFields(function($fields) {
				
		    	$datefield = new DateField('Date','Date (DD/MM/YYYY)');
				$datefield->setConfig('showcalendar', true);
				$datefield->setConfig('showdropdown', true);
				$datefield->setConfig('dateformat', 'dd/MM/YYYY');
				
				$fields->addFieldToTab('Root.Main', $datefield, 'Content');
				
				$intro = new TextareaField('IntroText', 'Blurb');
				$intro->setRightTitle('Short introduction blurb displayed on news landing page & top of article');
				
				$fields->addFieldToTab('Root.Main', $intro, 'Content');
				
/* 				$fields->addFieldToTab("Root.Main", new DropdownField('TemplateType', "Template Type", singleton('NewsArticle')->dbObject('TemplateType')->enumValues()), 'Content');  */
				$fields->addFieldToTab("Root.Main", new DropdownField('TemplateShape', "Template Shape", singleton('NewsArticle')->dbObject('TemplateShape')->enumValues()), 'Content'); 
				
				$image = new UploadField('AttachedImage', 'Main Image');
				$image->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
				$image->setConfig('allowedMaxFileNumber', 1);
				$image->setFolderName('Managed/NewsImages');
				$image->setRightTitle("Displayed to the right of the content in the main article, where it can be clicked to enlarge. <br />A thumbnail also appears next to the article summary on the main News page.");
				$fields->addFieldToTab('Root.Main', $image,"Content");	

			 });

			$fields = parent::getCMSFields();
			
			$fields->renameField("Title", "Headline");
		    $fields->removeFieldFromTab("Root.Main","MenuTitle");

	    	return $fields;
	   }
	   
	   function onBeforeWrite() {
			
			// Change MenuTitle, so date appears in CMS SiteTree
		   	$this->MenuTitle = $this->Date.": ".$this->Title;	
			
			// Move to News holder if created under something else
			if ($this->Parent()->ClassName != "NewsHolder") {
				$this->ParentID = NewsHolder::get()->first()->ID;
			}
			
			if(!$this->AuthorID) {
				$this->AuthorID = Member::currentUser()->ID;
			}
						
			parent::onBeforeWrite();
		}	
		
		public function getShortIntro() {
		   if(!$this->IntroText) {
			   return false;
		   }
		   
		   $words = explode(' ', $this->IntroText);
		   $str = '';
		   $limit = 100;
		   
		   foreach($words as $word) {
			   if(strlen($str) >= $limit) {
				   break;
			   }
			   
			   $str .= ' ' . $word;
		   }
		   
		   if(strlen($str) > $limit) {
			   $str .= '...';
		   }
		   
		   return $str;
	   }
	   
	   public function isLatest() {
		   $articles = NewsArticle::get()->exclude('ClassName', 'PressRelease');
		   if($articles) {
			   return $articles->first()->ID == $this->ID;
		   }
		   
		   return false;
	   }
	   
	   public function isSecondLatest() {
		   $articles = NewsArticle::get();
		   if($articles) {
		   		$count = $articles->Count();
		   		
		   		if($count > 1) {
			   		return $articles->offsetGet(1)->ID == $this->ID;
		   		}
		   }
		   
		   return false;
	   }
	}
	
	
	class NewsArticle_Controller extends Page_Controller {
	 	
	 	public function init() {
	      /*
if(Director::fileExists(project() . "/css/news.css")) {
	         Requirements::css(project() . "/css/news.css");
	      }else{
	         Requirements::css("basic-news/css/news.css");
	      }
*/
	      parent::init();  
	   }
	   
	   // Provides a resized image with the max width provided
	   public function ArticleImageSized($maxwidth = 250) {
		   if($this->AttachedImage()->getWidth() < $maxwidth) {
			   return $this->AttachedImage();
		   } else {
			   return $this->AttachedImage()->setWidth($maxwidth);
		   }
	   }
	   
	   public function NextArticle() {
		   $articles = NewsArticle::get()->filter('ClassName', 'NewsArticle');
		   $next = false;
		   $found = false;
		   
		 foreach($articles as $article) {
			 if($found) {
			       $next = $article;
				   break;
			   }
			   
			   if($article->ID == $this->ID) {
				   $found = true;
			   }
		   }
		   
		   return $next;
	   }
	   
	   public function PreviousArticle() {
	   		if($this->isLatest()) {
		   		return false;
	   		}
	   		
		   $articles = NewsArticle::get()->filter('ClassName', 'NewsArticle');
		   
		   $prev = false;
		   $found = false;
		   
		   foreach($articles as $article) {
			 if($found) {
				   break;
			   }
			   
			   if($article->ID == $this->ID) {
				   $found = true;
			   } else {
				   $prev = $article;
			   }
		   }
		   
		   return $prev;
	   }
	 
	}

?>