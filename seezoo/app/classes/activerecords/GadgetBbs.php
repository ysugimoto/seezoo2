<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class GadgetBbsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'gadget_bbs';
	protected $_primary = 'gadget_bbs_id';
	protected $_schemas = array(
		'gadget_bbs_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR')
	); 
	
	public function isValidGadgetBbsId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}

}
