<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BlogInfoActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'blog_info';
	protected $_primary = '';
	protected $_schemas = array(
		'template_id' => array('type' => 'INT'),
		'entry_limit' => array('type' => 'INT'),
		'comment_limit' => array('type' => 'INT'),
		'is_enable' => array('type' => 'INT'),
		'page_title' => array('type' => 'VARCHAR'),
		'is_need_captcha' => array('type' => 'INT'),
		'is_auto_ping' => array('type' => 'INT'),
		'zenback_code' => array('type' => 'TEXT'),
		'rss_format' => array('type' => 'TINYINT')
	); 
	
	public function isValidTemplateId($value) {
		return TRUE;
	}


	public function isValidEntryLimit($value) {
		return TRUE;
	}


	public function isValidCommentLimit($value) {
		return TRUE;
	}


	public function isValidIsEnable($value) {
		return TRUE;
	}


	public function isValidPageTitle($value) {
		return TRUE;
	}


	public function isValidIsNeedCaptcha($value) {
		return TRUE;
	}


	public function isValidIsAutoPing($value) {
		return TRUE;
	}


	public function isValidZenbackCode($value) {
		return TRUE;
	}


	public function isValidRssFormat($value) {
		return TRUE;
	}

}
