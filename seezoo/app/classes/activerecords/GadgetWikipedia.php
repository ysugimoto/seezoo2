<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class GadgetWikipediaActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'gadget_wikipedia';
	protected $_primary = 'gadget_wikipedia_id';
	protected $_schemas = array(
		'gadget_wikipedia_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR')
	); 
	
	public function isValidGadgetWikipediaId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}

}
