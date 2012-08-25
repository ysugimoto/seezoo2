<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class PagePathsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'page_paths';
	protected $_primary = 'page_path_id';
	protected $_schemas = array(
		'page_path_id' => array('type' => 'INT'),
		'page_path' => array('type' => 'VARCHAR'),
		'page_id' => array('type' => 'INT'),
		'plugin_id' => array('type' => 'INT'),
		'is_enabled' => array('type' => 'TINYINT')
	); 
	
	public function isValidPagePathId($value) {
		return TRUE;
	}


	public function isValidPagePath($value) {
		return TRUE;
	}


	public function isValidPageId($value) {
		return TRUE;
	}


	public function isValidPluginId($value) {
		return TRUE;
	}


	public function isValidIsEnabled($value) {
		return TRUE;
	}

}
