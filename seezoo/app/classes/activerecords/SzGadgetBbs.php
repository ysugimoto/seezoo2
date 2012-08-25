<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzGadgetBbsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_gadget_bbs';
	protected $_primary = 'sz_gadget_bbs_id';
	protected $_schemas = array(
		'sz_gadget_bbs_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR')
	); 
	
	public function isValidSzGadgetBbsId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}

}
