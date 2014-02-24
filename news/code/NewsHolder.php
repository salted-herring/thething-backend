<?php

class NewsHolder extends Page {

	public function getCMSFields() {

		$fields = parent::getCMSFields();
		
		$fields->removeFieldfromTab('Root.Main', 'Content');

		$fields->addFieldToTab(
				'Root.Main',
				new LiteralField("addnew",
					"<p><a href='/admin/pages/add/AddForm?action_doAdd=1&ParentID=".$this->ID."&PageType=NewsArticle&SecurityID=".SecurityToken::getSecurityID()."' class='ss-ui-button ss-ui-action-constructive ui-button' style='font-size:130%' data-icon=add''>New News Article</span></a></p>"),'Title');

		return $fields;
	}

	// Only allows certain children to be created
	private static $allowed_children = array('NewsArticle', 'Page');
	private static $description = 'Holds News Article pages';	   		   
   	private static $icon = "basic-news/images/newspaper-page";
   	
   	public function stageChildren($showAll = false) { 
   		return $this->__call('stageChildren', array($showAll))->sort(array('MenuTitle'=>'DESC',"Created"=>'DESC')); 
   	}

   	public function init() {
   		RSSFeed::linkToFeed($this->Link("rss"), "News RSS Feed");
        parent::init();
   	}
   	
}


class NewsHolder_Controller extends Page_Controller {

	/*
private static $allowed_actions = array(
		"rss"
	);
*/

	function init() {
  		//RSSFeed::linkToFeed($this->Link() . "rss");	
  		/*
if(Director::fileExists(project() . "/css/news.css")) {
	         Requirements::css(project() . "/css/news.css");
	      }else{
	         Requirements::css("basic-news/css/news.css");
	      }
*/
	      
		  if($this->request->getVar('page')) {
			  $this->currentPage = $this->request->getVar('page');
		  }
		  
   		parent::init();	
	}
	
	/*
public function rss() {
		$config = SiteConfig::current_site_config(); 
        // Creates a new RSS Feed list
        $rss = new RSSFeed(
            $list = NewsArticle::get()->filter('ClassName', $this->ClassName), // an SS_List containing your feed items
            $link = $this->Link("rss"), // a HTTP link to this feed
            $title = $config->Title . " News", // title for this feed, displayed in RSS readers
            $description = "All the latest news from ". $config->Title . "." // description
        );
        // Outputs the RSS feed to the user.
        return $rss->outputToBrowser();
    }
*/
	
	// Provides Paginated List of NewsArticles
	function PaginatedPages() {
		$this->list = new PaginatedList(NewsArticle::get()->filter('ClassName', 'NewsArticle'), $this->request);
		$this->list->setPageLength(SiteConfig::current_site_config()->PaginationCount);
		$this->list->setPaginationGetVar('page');
		
		if($this->currentPage) {
			$this->list->setCurrentPage($this->currentPage);
		}
		return $this->list;
	}
	
	function NextPage() {
		if($this->currentPage && $this->list->CurrentPage() < $this->list->TotalPages()) {
			return '?page=' . ($this->currentPage + 1);
		}
		
		return '?page=2';
	}
	
	function PrevPage() {
		if($this->currentPage && $this->list->CurrentPage() > 0) {
			return '?page=' . ($this->currentPage - 1);
		}
		
		return '?page=1';
	}
}

?>