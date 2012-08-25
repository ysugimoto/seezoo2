<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzGadgetMasterActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_gadget_master';
	protected $_primary = 'gadget_master_id';
	protected $_schemas = array(
		'gadget_master_id' => array('type' => 'INT'),
		'user_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR'),
		'gadget_id' => array('type' => 'INT'),
		'display_order' => array('type' => 'INT')
	); 
	
	public function isValidGadgetMasterId($value) {
		return TRUE;
	}


	public function isValidUserId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}


	public function isValidGadgetId($value) {
		return TRUE;
	}


	public function isValidDisplayOrder($value) {
		return TRUE;
	}

}
