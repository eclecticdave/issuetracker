<?php
/** @see IssueTrackerAction **/
require_once dirname(__FILE__) . '/IssueTrackerAction.php';

/**
 * IssueTrackerActionView class.
 *
 * @category    Extensions
 * @package     IssueTracker
 * @author      Federico Cargnelutti
 * @copyright   Copyright (c) 2008 Federico Cargnelutti
 * @license     GNU General Public Licence 2.0 or later
 */
class IssueTrackerActionList extends IssueTrackerAction
{	
	/**
	 * Executes the action.
	 *
	 * @return void 
	 */
	public function listAction()
	{
		// Mediawiki globals
		global $wgUser, $wgRequest;
		
		$this->_setDefaultVars();
		$this->_setHookPreferences();
		
		// Conditions
		$conds['project_name'] = addslashes($this->project);
		$conds['deleted'] = 0;
		
		// Filters
		if ($this->filterBy !== null || $this->filterStatus !== null) {
			if ($wgUser->isLoggedIn()) {
				$filterByOptions = array('assined_to_me'=>'assignee', 'reported_by_me'=>'user_name');
				if ($this->filterBy !== null && array_key_exists($this->filterBy, $filterByOptions)) {
					$filterField = $filterByOptions[$this->filterBy];
					$conds[$filterField] = $wgUser->getName();
				}
			}
			
			if ($this->filterStatus !== null) {
				if (array_key_exists($this->filterStatus, $this->issueStatus)) {
					$conds['status'] = $this->filterStatus; 
				} elseif ($this->filterStatus == 'archived') {
					$conds['deleted'] = 1; 
				}
			}
		}
		
		$offset = $wgRequest->getInt('offset', 0);
		if ($this->searchString !== null) {
			$this->issues = $this->getModel('default')->getIssuesBySrting($this->searchString, $this->project, $offset);
		} else {
			$this->issues = $this->getModel('default')->getIssues($conds, $offset);
		}
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
		$this->pageKey        = $this->getNamespace('dbKey');
		$this->project        = $this->getNamespace('dbKey');		
		$this->pageTitle      = $this->getNamespace('text');
		$this->formAction     = $wgScript;
		$this->url            = $wgScript . '?title=' . $this->pageKey . '&bt_action=';
		$this->viewUrl        = $this->url . 'view&bt_issueid=';
		$this->addUrl         = $this->url . 'add';
		$this->editUrl        = $this->url . 'edit&bt_issueid=';
		$this->deleteUrl      = $this->url . 'archive&bt_issueid=';
		$this->isLoggedIn     = $wgUser->isLoggedIn();
		$this->isAllowed      = $wgUser->isAllowed('protect');
		$this->hasDeletePerms = $this->hasPermission('delete');
		$this->hasEditPerms   = $this->hasPermission('edit');
		$this->hasViewPerms   = $this->hasPermission('view');
		$this->search         = true;
		$this->filter         = true;
		$this->auth           = true;
		$this->issueType      = $this->_config->getIssueType();
		$this->issueStatus    = $this->_config->getIssueStatus();
		
		// Request vars
		$this->filterBy       = $wgRequest->getVal('bt_filter_by');
		$this->filterStatus   = $wgRequest->getVal('bt_filter_status');
		$this->searchString   = $wgRequest->getVal('bt_search_string');;
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
		if (array_key_exists('search', $this->_args) && $this->_args['search'] == 'false') {
			$this->search = false;
		}
		if (array_key_exists('filter', $this->_args) && $this->_args['filter'] == 'false') {
			$this->filter = false;
		}
		if (array_key_exists('authenticate', $this->_args) && $this->_args['authenticate'] == 'false') {
			$this->auth = false;
		}
	}
}
?>