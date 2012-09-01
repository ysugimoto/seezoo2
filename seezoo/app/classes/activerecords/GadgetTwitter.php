<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class GadgetTwitterActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'gadget_twitter';
	protected $_primary = 'gadget_twitter_id';
	protected $_schemas = array(
		'gadget_twitter_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR'),
		'account_name' => array('type' => 'VARCHAR'),
		'update_time' => array('type' => 'INT'),
		'show_count' => array('type' => 'INT')
	); 
	
	public function isValidGadgetTwitterId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}


	public function isValidAccountName($value) {
		return TRUE;
	}


	public function isValidUpdateTime($value) {
		return TRUE;
	}


	public function isValidShowCount($value) {
		return TRUE;
	}

}
