<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SiteInfoActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'site_info';
	protected $_primary = '';
	protected $_schemas = array(
		'site_title' => array('type' => 'VARCHAR'),
		'google_analytics' => array('type' => 'TEXT'),
		'is_maintenance' => array('type' => 'INT'),
		'default_template_id' => array('type' => 'INT'),
		'gmap_api_key' => array('type' => 'VARCHAR'),
		'system_mail_from' => array('type' => 'VARCHAR'),
		'enable_mod_rewrite' => array('type' => 'INT'),
		'enable_cache' => array('type' => 'INT'),
		'ssl_base_url' => array('type' => 'VARCHAR'),
		'log_level' => array('type' => 'TINYINT'),
		'is_accept_member_registration' => array('type' => 'TINYINT'),
		'debug_level' => array('type' => 'TINYINT'),
		'enable_mobile' => array('type' => 'TINYINT'),
		'enable_smartphone' => array('type' => 'TINYINT')
	); 
	
	public function isValidSiteTitle($value) {
		return TRUE;
	}


	public function isValidGoogleAnalytics($value) {
		return TRUE;
	}


	public function isValidIsMaintenance($value) {
		return TRUE;
	}


	public function isValidDefaultTemplateId($value) {
		return TRUE;
	}


	public function isValidGmapApiKey($value) {
		return TRUE;
	}


	public function isValidSystemMailFrom($value) {
		return TRUE;
	}


	public function isValidEnableModRewrite($value) {
		return TRUE;
	}


	public function isValidEnableCache($value) {
		return TRUE;
	}


	public function isValidSslBaseUrl($value) {
		return TRUE;
	}


	public function isValidLogLevel($value) {
		return TRUE;
	}


	public function isValidIsAcceptMemberRegistration($value) {
		return TRUE;
	}


	public function isValidDebugLevel($value) {
		return TRUE;
	}


	public function isValidEnableMobile($value) {
		return TRUE;
	}


	public function isValidEnableSmartphone($value) {
		return TRUE;
	}

}
