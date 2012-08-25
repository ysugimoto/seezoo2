<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class DirectoriesActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'directories';
	protected $_primary = 'directories_id';
	protected $_schemas = array(
		'directories_id' => array('type' => 'INT'),
		'path_name' => array('type' => 'VARCHAR'),
		'parent_id' => array('type' => 'INT'),
		'dir_name' => array('type' => 'VARCHAR'),
		'created_date' => array('type' => 'DATETIME'),
		'access_permission' => array('type' => 'VARCHAR')
	); 
	
	public function isValidDirectoriesId($value) {
		return TRUE;
	}


	public function isValidPathName($value) {
		return TRUE;
	}


	public function isValidParentId($value) {
		return TRUE;
	}


	public function isValidDirName($value) {
		return TRUE;
	}


	public function isValidCreatedDate($value) {
		return TRUE;
	}


	public function isValidAccessPermission($value) {
		return TRUE;
	}

}
