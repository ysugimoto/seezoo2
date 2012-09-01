<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class ActivationDataActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'activation_data';
	protected $_primary = 'activation_code';
	protected $_schemas = array(
		'activation_code' => array('type' => 'VARCHAR'),
		'member_id' => array('type' => 'INT'),
		'user_id' => array('type' => 'INT'),
		'email' => array('type' => 'VARCHAR'),
		'data' => array('type' => 'TEXT'),
		'activation_limit_time' => array('type' => 'DATETIME')
	); 
	
	public function isValidActivationCode($value) {
		return TRUE;
	}


	public function isValidMemberId($value) {
		return TRUE;
	}


	public function isValidUserId($value) {
		return TRUE;
	}


	public function isValidEmail($value) {
		return TRUE;
	}


	public function isValidData($value) {
		return TRUE;
	}


	public function isValidActivationLimitTime($value) {
		return TRUE;
	}

}
