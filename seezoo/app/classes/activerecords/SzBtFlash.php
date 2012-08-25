<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtFlashActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_flash';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'file_id' => array('type' => 'INT')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidFileId($value) {
		return TRUE;
	}

}
