<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzGadgetTranslateActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_gadget_translate';
	protected $_primary = 'sz_gadget_translate_id';
	protected $_schemas = array(
		'sz_gadget_translate_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR')
	); 
	
	public function isValidSzGadgetTranslateId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}

}
