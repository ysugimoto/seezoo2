<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class OptionsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'options';
	protected $_primary = 'option_id';
	protected $_schemas = array(
		'option_id' => array('type' => 'INT'),
		'name' => array('type' => 'VARCHAR'),
		'value' => array('type' => 'VARCHAR'),
		'handle_key' => array('type' => 'VARCHAR')
	); 
	
	public function isValidOptionId($value) {
		return TRUE;
	}


	public function isValidName($value) {
		return TRUE;
	}


	public function isValidValue($value) {
		return TRUE;
	}


	public function isValidHandleKey($value) {
		return TRUE;
	}

}
