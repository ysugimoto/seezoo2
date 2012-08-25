<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBackendActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_backend';
	protected $_primary = 'sz_backend_id';
	protected $_schemas = array(
		'sz_backend_id' => array('type' => 'INT'),
		'backend_handle' => array('type' => 'VARCHAR'),
		'backend_name' => array('type' => 'VARCHAR'),
		'description' => array('type' => 'VARCHAR'),
		'last_run' => array('type' => 'DATETIME'),
		'result' => array('type' => 'TEXT'),
		'is_process' => array('type' => 'INT')
	); 
	
	public function isValidSzBackendId($value) {
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
