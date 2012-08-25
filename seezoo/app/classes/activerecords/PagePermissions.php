<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class PagePermissionsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'page_permissions';
	protected $_primary = 'page_permissions_id';
	protected $_schemas = array(
		'page_permissions_id' => array('type' => 'INT'),
		'page_id' => array('type' => 'INT'),
		'allow_access_user' => array('type' => 'VARCHAR'),
		'allow_edit_user' => array('type' => 'VARCHAR'),
		'allow_approve_user' => array('type' => 'VARCHAR')
	); 
	
	public function isValidPagePermissionsId($value) {
		return TRUE;
	}


	public function isValidPageId($value) {
		return TRUE;
	}


	public function isValidAllowAccessUser($value) {
		return TRUE;
	}


	public function isValidAllowEditUser($value) {
		return TRUE;
	}


	public function isValidAllowApproveUser($value) {
		return TRUE;
	}

}
