<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class FileGroupsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'file_groups';
	protected $_primary = 'file_groups_id';
	protected $_schemas = array(
		'file_groups_id' => array('type' => 'INT'),
		'group_name' => array('type' => 'VARCHAR'),
		'created_date' => array('type' => 'DATETIME')
	); 
	
	public function isValidFileGroupsId($value) {
		return TRUE;
	}


	public function isValidGroupName($value) {
		return TRUE;
	}


	public function isValidCreatedDate($value) {
		return TRUE;
	}

}
