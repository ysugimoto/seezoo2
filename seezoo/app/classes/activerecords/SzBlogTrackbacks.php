<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBlogTrackbacksActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_blog_trackbacks';
	protected $_primary = 'sz_blog_trackbacks_id';
	protected $_schemas = array(
		'sz_blog_trackbacks_id' => array('type' => 'INT'),
		'title' => array('type' => 'VARCHAR'),
		'url' => array('type' => 'VARCHAR'),
		'blog_name' => array('type' => 'VARCHAR'),
		'excerpt' => array('type' => 'VARCHAR'),
		'ip_address' => array('type' => 'VARCHAR'),
		'received_date' => array('type' => 'DATETIME'),
		'is_allowed' => array('type' => 'TINYINT'),
		'sz_blog_id' => array('type' => 'INT')
	); 
	
	public function isValidSzBlogTrackbacksId($value) {
		return TRUE;
	}


	public function isValidTitle($value) {
		return TRUE;
	}


	public function isValidUrl($value) {
		return TRUE;
	}


	public function isValidBlogName($value) {
		return TRUE;
	}


	public function isValidExcerpt($value) {
		return TRUE;
	}


	public function isValidIpAddress($value) {
		return TRUE;
	}


	public function isValidReceivedDate($value) {
		return TRUE;
	}


	public function isValidIsAllowed($value) {
		return TRUE;
	}


	public function isValidSzBlogId($value) {
		return TRUE;
	}

}
