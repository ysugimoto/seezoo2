<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzUsersActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_users';
	protected $_primary = 'user_id';
	protected $_schemas = array(
		'user_id' => array('type' => 'INT'),
		'user_name' => array('type' => 'VARCHAR'),
		'password' => array('type' => 'VARCHAR'),
		'hash' => array('type' => 'VARCHAR'),
		'email' => array('type' => 'VARCHAR'),
		'last_login' => array('type' => 'DATETIME'),
		'login_times' => array('type' => 'INT'),
		'admin_flag' => array('type' => 'INT'),
		'regist_time' => array('type' => 'DATETIME'),
		'remember_token' => array('type' => 'VARCHAR'),
		'image_data' => array('type' => 'VARCHAR'),
		'login_miss_count' => array('type' => 'TINYINT'),
		'is_admin_user' => array('type' => 'TINYINT')
	); 
	
	public function isValidUserId($value) {
		return TRUE;
	}


	public function isValidUserName($value) {
		return TRUE;
	}


	public function isValidPassword($value) {
		return TRUE;
	}


	public function isValidHash($value) {
		return TRUE;
	}


	public function isValidEmail($value) {
		return TRUE;
	}


	public function isValidLastLogin($value) {
		return TRUE;
	}


	public function isValidLoginTimes($value) {
		return TRUE;
	}


	public function isValidAdminFlag($value) {
		return TRUE;
	}


	public function isValidRegistTime($value) {
		return TRUE;
	}


	public function isValidRememberToken($value) {
		return TRUE;
	}


	public function isValidImageData($value) {
		return TRUE;
	}


	public function isValidLoginMissCount($value) {
		return TRUE;
	}


	public function isValidIsAdminUser($value) {
		return TRUE;
	}

}
