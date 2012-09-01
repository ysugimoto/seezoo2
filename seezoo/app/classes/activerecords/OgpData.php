<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class OgpDataActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'ogp_data';
	protected $_primary = '';
	protected $_schemas = array(
		'is_enable' => array('type' => 'TINYINT'),
		'site_type' => array('type' => 'VARCHAR'),
		'file_id' => array('type' => 'INT'),
		'extra' => array('type' => 'TEXT')
	); 
	
	public function isValidIsEnable($value) {
		return TRUE;
	}


	public function isValidSiteType($value) {
		return TRUE;
	}


	public function isValidFileId($value) {
		return TRUE;
	}


	public function isValidExtra($value) {
		return TRUE;
	}

}
