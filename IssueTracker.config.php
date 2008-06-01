<?php
/**
 * Issue Tracking System
 * 
 * Configuration class for the IssueTracker extension.
 *
 * @category    Extensions
 * @package     IssueTracker
 * @author      Federico Cargnelutti
 * @copyright   Copyright (c) 2008 Federico Cargnelutti
 * @license     GNU General Public Licence 2.0 or later
 */
class IssueTrackerConfig
{
	/**
	 * Actions.
	 * @var array
	 */
	protected $_permissions = null;
	
	/**
	 * Issue type array.
	 * @var array
	 */
	protected $_issueType = null;
	
	/**
	 * Issue status array.
	 * @var array
	 */
	protected $_issueStatus = null;


	/**
	 * This is the action used whenever the bt_action parameter is not present
	 * @var string
	 */
	public $defaultAction = 'summary';

	/**
	 * ...
	 * 
	 * @return void
	 */
	public function setPermissions()
	{
		$perms['list']     = array('group' => '*');
		$perms['view']     = array('group' => '*');
		$perms['add']      = array('group' => '*');
		$perms['edit']     = array('group' => '*');
		$perms['archive']  = array('group' => '*');
		$perms['delete']   = array('group' => '*');
		$perms['assign']   = array('group' => 'sysop');
		$perms['assignee'] = array('group' => 'sysop');
		$perms['summary']  = array('group' => '*');
		
		$this->_permissions = $perms;
	}
	
	/**
	 * Returns the permission array.
	 *
	 * @param string $action
	 * @return array self::$_permissions
	 */
	public function getPermissions($action = null)
	{
		if ($this->_permissions === null) {
			$this->setPermissions();
		}
		
		if ($action !== null && array_key_exists($action, $this->_permissions)) {
			return $this->_permissions[$action];
		} else {
			return $this->_permissions;
		}
	}
	
	/**
	 * Sets the issue type array.
	 * 
	 * An issue's type expresses what kind of issue it is and also allows custom 
	 * name and color to be added to an issue.
	 *
	 * @param array $type
	 * @return void
	 */
	public function setIssueType($type = array()) 
	{
		$type['t_bug'] = array('name' => 'Bug', 'colour' => 'FFDFDF');
		$type['t_fea'] = array('name' => 'New Feature', 'colour' => 'E1FFDF');
		$type['t_imp'] = array('name' => 'Improvement', 'colour' => 'FFFFCF');
		$type['t_doc'] = array('name' => 'Doc', 'colour' => 'F9F9F9');
		$type['t_fee'] = array('name' => 'Feedback', 'colour' => 'E5D4E7');
		$type['t_tes'] = array('name' => 'Test', 'colour' => 'DFE2FF');
		
		$this->_issueType = $type;
	}
	
	/**
	 * Returns the issue type array.
	 *
	 * @return array self::$_issueType
	 */
	public function getIssueType() 
	{
		if ($this->_issueType === null) {
			$this->setIssueType();
		}
		return $this->_issueType;
	}
	
	/**
	 * Sets the issue status array.
	 *
	 * @param array $status
	 * @return void
	 */
	public function setIssueStatus($status = array()) 
	{
		$status['s_new'] = array('name' => 'New', 'colour' => 'F9F9F9');
		$status['s_asi'] = array('name' => 'Assigned', 'colour' => 'F9F9F9');
		$status['s_res'] = array('name' => 'Resolved', 'colour' => 'B8EFB3');
		$status['s_clo'] = array('name' => 'Closed', 'colour' => 'F9F9F9');
		
		$this->_issueStatus = $status;
	}
	
	/**
	 * Returns the issue status array.
	 *
	 *@return array self::$_issueStatus
	 */
	public function getIssueStatus() 
	{
		if ($this->_issueStatus === null) {
			$this->setIssueStatus();
		}
		return $this->_issueStatus;
	}
}
