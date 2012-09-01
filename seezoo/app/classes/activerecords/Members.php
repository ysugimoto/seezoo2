<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class MembersActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'members';
	protected $_primary = 'member_id';
	protected $_schemas = array(
		'member_id' => array('type' => 'INT'),
		'nick_name' => array('type' => 'VARCHAR'),
		'email' => array('type' => 'VARCHAR'),
		'password' => array('type' => 'VARCHAR'),
		'hash' => array('type' => 'VARCHAR'),
		'activation_code' => array('type' => 'VARCHAR'),
		'is_activate' => array('type' => 'TINYINT'),
		'relation_site_user' => array('type' => 'INT'),
		'login_miss_count' => array('type' => 'INT'),
		'login_times' => array('type' => 'INT'),
		'image_data' => array('type' => 'VARCHAR'),
		'joined_date' => array('type' => 'DATETIME'),
		'activation_limit_time' => array('type' => 'DATETIME'),
		'twitter_id' => array('type' => 'VARCHAR')
	); 
	
	public function isValidMemberId($value) {
		return TRUE;
	}


	public function isValidNickName($value) {
		return TRUE;
	}


	public function isValidEmail($value) {
		return TRUE;
	}


	public function isValidPassword($value) {
		return TRUE;
	}


	public function isValidHash($value) {
		return TRUE;
	}


	public function isValidActivationCode($value) {
		return TRUE;
	}


	public function isValidIsActivate($value) {
		return TRUE;
	}


	public function isValidRelationSiteUser($value) {
		return TRUE;
	}


	public function isValidLoginMissCount($value) {
		return TRUE;
	}


	public function isValidLoginTimes($value) {
		return TRUE;
	}


	public function isValidImageData($value) {
		return TRUE;
	}


	public function isValidJoinedDate($value) {
		return TRUE;
	}


	public function isValidActivationLimitTime($value) {
		return TRUE;
	}


	public function isValidTwitterId($value) {
		return TRUE;
	}

}
