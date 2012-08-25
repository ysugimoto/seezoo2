<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzGadgetWikipediaActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_gadget_wikipedia';
	protected $_primary = 'sz_gadget_wikipedia_id';
	protected $_schemas = array(
		'sz_gadget_wikipedia_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR')
	); 
	
	public function isValidSzGadgetWikipediaId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}

}
