<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class GadgetTranslateActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'gadget_translate';
	protected $_primary = 'gadget_translate_id';
	protected $_schemas = array(
		'gadget_translate_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR')
	); 
	
	public function isValidGadgetTranslateId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}

}
