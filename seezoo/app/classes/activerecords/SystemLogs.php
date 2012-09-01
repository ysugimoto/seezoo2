<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SystemLogsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'system_logs';
	protected $_primary = 'system_logs_id';
	protected $_schemas = array(
		'system_logs_id' => array('type' => 'INT'),
		'log_type' => array('type' => 'VARCHAR'),
		'severity' => array('type' => 'VARCHAR'),
		'log_text' => array('type' => 'TEXT'),
		'logged_date' => array('type' => 'DATETIME')
	); 
	
	public function isValidSystemLogsId($value) {
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
