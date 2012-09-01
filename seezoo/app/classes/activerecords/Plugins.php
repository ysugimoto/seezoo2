<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class PluginsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'plugins';
	protected $_primary = 'plugin_id';
	protected $_schemas = array(
		'plugin_id' => array('type' => 'INT'),
		'plugin_name' => array('type' => 'VARCHAR'),
		'plugin_handle' => array('type' => 'VARCHAR'),
		'description' => array('type' => 'VARCHAR'),
		'added_datetime' => array('type' => 'DATETIME'),
		'is_enabled' => array('type' => 'TINYINT')
	); 
	
	public function isValidPluginId($value) {
		return TRUE;
	}


	public function isValidPluginName($value) {
		return TRUE;
	}


	public function isValidPluginHandle($value) {
		return TRUE;
	}


	public function isValidDescription($value) {
		return TRUE;
	}


	public function isValidAddedDatetime($value) {
		return TRUE;
	}


	public function isValidIsEnabled($value) {
		return TRUE;
	}

}
