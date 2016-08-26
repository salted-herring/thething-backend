<?php
/**
 * @file AppAdminForm.php
 *
 * Action Form : managing apps
 * */

class AppAdminForm extends GridFieldDetailForm {
	
}

class AppAdminForm_ItemRequest extends GridFieldDetailForm_ItemRequest {
	
	private static $allowed_actions = array(
		'edit',
		'view',
		'ItemEditForm'
	);
	
	public function ItemEditForm() {
		$form = parent::ItemEditForm();
		
		if ($form instanceof Form) {
			$actions = $form->Actions();
			$fields = $form->Fields();
			
			if ($this->record->Submissions() && $this->record->Submissions()->exists()) {
				$btnPurgeSubmission = FormAction::create('purgeSubmissions', 'Purge Submissions');
				$btnPurgeSubmission->addExtraClass('ss-ui-action-destructive');
				$actions->push($btnPurgeSubmission);
			}
			$form->setActions($actions);
		}
		
		return $form;
	}
	
	public function purgeSubmissions($data, $form) {
		$record = $this->record;
		$submissions = $record->Submissions();
		foreach ($submissions as $submission) {
			$submission->delete();
		}
		
		return $this->edit(Controller::curr()->getRequest());
	} 
	
}