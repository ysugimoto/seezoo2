<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BtTwitterBlockActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'bt_twitter_block';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'user_name' => array('type' => 'VARCHAR'),
		'password' => array('type' => 'VARCHAR'),
		'view_type' => array('type' => 'INT'),
		'view_limit' => array('type' => 'INT')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidUserName($value) {
		return TRUE;
	}


	public function isValidPassword($value) {
		return TRUE;
	}


	public function isValidViewType($value) {
		return TRUE;
	}


	public function isValidViewLimit($value) {
		return TRUE;
	}

}
