<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BackendActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'backend';
	protected $_primary = 'backend_id';
	protected $_schemas = array(
		'backend_id' => array('type' => 'INT'),
		'backend_handle' => array('type' => 'VARCHAR'),
		'backend_name' => array('type' => 'VARCHAR'),
		'description' => array('type' => 'VARCHAR'),
		'last_run' => array('type' => 'DATETIME'),
		'result' => array('type' => 'TEXT'),
		'is_process' => array('type' => 'INT')
	); 
	
	public function isValidBackendId($value) {
		return TRUE;
	}


	public function isValidBackendHandle($value) {
		return TRUE;
	}


	public function isValidBackendName($value) {
		return TRUE;
	}


	public function isValidDescription($value) {
		return TRUE;
	}


	public function isValidLastRun($value) {
		return TRUE;
	}


	public function isValidResult($value) {
		return TRUE;
	}


	public function isValidIsProcess($value) {
		return TRUE;
	}

}
