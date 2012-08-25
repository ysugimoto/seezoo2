<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtVideoActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_video';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'file_id' => array('type' => 'INT'),
		'display_width' => array('type' => 'INT'),
		'display_height' => array('type' => 'INT')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidFileId($value) {
		return TRUE;
	}


	public function isValidDisplayWidth($value) {
		return TRUE;
	}


	public function isValidDisplayHeight($value) {
		return TRUE;
	}

}
