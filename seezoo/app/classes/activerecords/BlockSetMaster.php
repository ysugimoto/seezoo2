<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BlockSetMasterActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'block_set_master';
	protected $_primary = 'block_set_master_id';
	protected $_schemas = array(
		'block_set_master_id' => array('type' => 'INT'),
		'master_name' => array('type' => 'VARCHAR'),
		'create_date' => array('type' => 'DATETIME')
	); 
	
	public function isValidBlockSetMasterId($value) {
		return TRUE;
	}


	public function isValidMasterName($value) {
		return TRUE;
	}


	public function isValidCreateDate($value) {
		return TRUE;
	}

}
