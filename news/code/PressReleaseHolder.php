<?php
class PressReleaseHolder extends NewsHolder {
	public function getCMSFields() {

		$fields = parent::getCMSFields();
		
		$fields->removeFieldsfromTab('Root.Main', array('Content', 'addnew'));

		$fields->addFieldToTab(
				'Root.Main',
				new LiteralField("addnew",
					"<p><a href='/admin/pages/add/AddForm?action_doAdd=1&ParentID=".$this->ID."&PageType=PressRelease&SecurityID=".SecurityToken::getSecurityID()."' class='ss-ui-button ss-ui-action-constructive ui-button' style='font-size:130%' data-icon=add''>New Press Release</span></a></p>"),'Title');

		return $fields;
	}
}

class PressReleaseHolder_Controller extends NewsHolder_Controller {
	function PaginatedPages() {
		$this->list = new PaginatedList(NewsArticle::get()->filter('ClassName', 'PressRelease'), $this->request);
		$this->list->setPageLength(SiteConfig::current_site_config()->PaginationCount);
		$this->list->setPaginationGetVar('page');
		
		if($this->currentPage) {
			$this->list->setCurrentPage($this->currentPage);
		}
		return $this->list;
	}
}