<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BtImageBlockActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'bt_image_block';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'file_id' => array('type' => 'INT'),
		'alt' => array('type' => 'VARCHAR'),
		'link_to' => array('type' => 'VARCHAR'),
		'action_method' => array('type' => 'VARCHAR'),
		'action_file_id' => array('type' => 'INT'),
		'hover_file_id' => array('type' => 'INT'),
		'link_type' => array('type' => 'INT'),
		'link_to_page_id' => array('type' => 'INT')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidFileId($value) {
		return TRUE;
	}


	public function isValidAlt($value) {
		return TRUE;
	}


	public function isValidLinkTo($value) {
		return TRUE;
	}


	public function isValidActionMethod($value) {
		return TRUE;
	}


	public function isValidActionFileId($value) {
		return TRUE;
	}


	public function isValidHoverFileId($value) {
		return TRUE;
	}


	public function isValidLinkType($value) {
		return TRUE;
	}


	public function isValidLinkToPageId($value) {
		return TRUE;
	}

}
