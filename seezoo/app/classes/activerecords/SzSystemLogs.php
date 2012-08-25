<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzSystemLogsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_system_logs';
	protected $_primary = 'sz_system_logs_id';
	protected $_schemas = array(
		'sz_system_logs_id' => array('type' => 'INT'),
		'log_type' => array('type' => 'VARCHAR'),
		'severity' => array('type' => 'VARCHAR'),
		'log_text' => array('type' => 'TEXT'),
		'logged_date' => array('type' => 'DATETIME')
	); 
	
	public function isValidSzSystemLogsId($value) {
		return TRUE;
	}


	public function isValidLogType($value) {
		return TRUE;
	}


	public function isValidSeverity($value) {
		return TRUE;
	}


	public function isValidLogText($value) {
		return TRUE;
	}


	public function isValidLoggedDate($value) {
		return TRUE;
	}

}
