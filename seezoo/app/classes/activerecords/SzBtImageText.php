<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtImageTextActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_image_text';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'file_id' => array('type' => 'INT'),
		'text' => array('type' => 'TEXT'),
		'float_mode' => array('type' => 'VARCHAR')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidFileId($value) {
		return TRUE;
	}


	public function isValidText($value) {
		return TRUE;
	}


	public function isValidFloatMode($value) {
		return TRUE;
	}

}
