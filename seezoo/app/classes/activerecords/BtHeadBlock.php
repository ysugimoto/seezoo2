<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BtHeadBlockActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'bt_head_block';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'head_level' => array('type' => 'VARCHAR'),
		'class_name' => array('type' => 'VARCHAR'),
		'text' => array('type' => 'VARCHAR'),
		'content_type' => array('type' => 'INT'),
		'content_file_id' => array('type' => 'INT'),
		'alt_text' => array('type' => 'INT')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidHeadLevel($value) {
		return TRUE;
	}


	public function isValidClassName($value) {
		return TRUE;
	}


	public function isValidText($value) {
		return TRUE;
	}


	public function isValidContentType($value) {
		return TRUE;
	}


	public function isValidContentFileId($value) {
		return TRUE;
	}


	public function isValidAltText($value) {
		return TRUE;
	}

}
