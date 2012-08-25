<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzSessionsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_sessions';
	protected $_primary = 'session_id';
	protected $_schemas = array(
		'session_id' => array('type' => 'VARCHAR'),
		'session_mobile_id' => array('type' => 'VARCHAR'),
		'ip_address' => array('type' => 'VARCHAR'),
		'user_agent' => array('type' => 'VARCHAR'),
		'last_activity' => array('type' => 'INT'),
		'user_data' => array('type' => 'TEXT')
	); 
	
	public function isValidSessionId($value) {
		return TRUE;
	}


	public function isValidSessionMobileId($value) {
		return TRUE;
	}


	public function isValidIpAddress($value) {
		return TRUE;
	}


	public function isValidUserAgent($value) {
		return TRUE;
	}


	public function isValidLastActivity($value) {
		return TRUE;
	}


	public function isValidUserData($value) {
		return TRUE;
	}

}
