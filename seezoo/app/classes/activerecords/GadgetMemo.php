<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class GadgetMemoActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'gadget_memo';
	protected $_primary = 'gadget_memo_id';
	protected $_schemas = array(
		'gadget_memo_id' => array('type' => 'INT'),
		'user_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR'),
		'data' => array('type' => 'TEXT'),
		'update_time' => array('type' => 'INT')
	); 
	
	public function isValidGadgetMemoId($value) {
		return TRUE;
	}


	public function isValidUserId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}


	public function isValidData($value) {
		return TRUE;
	}


	public function isValidUpdateTime($value) {
		return TRUE;
	}

}
