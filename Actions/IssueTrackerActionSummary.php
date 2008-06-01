<?php
/** @see IssueTrackerAction **/
require_once dirname(__FILE__) . '/IssueTrackerAction.php';

/**
 * IssueTrackerActionSummary class.
 */
class IssueTrackerActionSummary extends IssueTrackerAction
{	
	/**
	 * Executes the action.
	 *
	 * @return void 
	 */
	public function summaryAction()
	{
		// Mediawiki globals
		global $wgUser, $wgRequest;
		
		$this->_setDefaultVars();
		$this->_setHookPreferences();

		// Validation
		if (strcasecmp($this->groupby, 'type') == 0) {
			$this->issueLookup      = $this->_config->getIssueType();
		}
		else if (strcasecmp($this->groupby, 'status') == 0) {
			$this->issueLookup    = $this->_config->getIssueStatus();
		}
		else if (isset($this->groupby)) {
			throw new Exception("Invalid groupby parameter: " . $this->groupby);
		}

		// Conditions
		$conds['project_name'] = addslashes($this->project);
		
		$row = $this->getModel('default')->getIssueSummary($conds);
		$this->issuecount = $row->fetchObject()->count;

		$this->issuesummary = $this->getModel('default')->getIssueSummary($conds, $this->groupby);
		$this->setOutput($this->render());
	}
	
	/**
	 * Sets the default vars.
	 *
	 * @return void 
	 */
	protected function _setDefaultVars()
	{
		// Mediawiki globals
		global $wgScript, $wgUser, $wgRequest;
		
		$this->action         = $this->getAction();
		$this->pageKey        = 'Special:IssueTracker/' . $this->getNamespace('dbKey');
		$this->project        = $this->getNamespace('dbKey');		
		$this->pageTitle      = $this->getNamespace('text');
		$this->url            = $wgScript . '?title=' . $this->pageKey . '&bt_action=list';
		$this->isAllowed      = $wgUser->isAllowed('protect');
		$this->hasSummaryPerms = $this->hasPermission('summary');
		
		// Request vars
		$this->groupby        = $wgRequest->getVal('bt_groupby');
	}
	
	/**
	 * Processes the tag attributes.
	 *
	 * @return void 
	 */
	protected function _setHookPreferences()
	{
		if (array_key_exists('project', $this->_args) && $this->_args['project'] !== '') {
			$this->project = $this->_args['project'];
		}
		if (array_key_exists('groupby', $this->_args) && $this->_args['groupby'] !== '') {
			$this->groupby = $this->_args['groupby'];
		}
	}
}
?>
