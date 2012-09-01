<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BlogPingListActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'blog_ping_list';
	protected $_primary = 'blog_ping_list_id';
	protected $_schemas = array(
		'blog_ping_list_id' => array('type' => 'INT'),
		'ping_server' => array('type' => 'VARCHAR'),
		'ping_name' => array('type' => 'VARCHAR')
	); 
	
	public function isValidBlogPingListId($value) {
		return TRUE;
	}


	public function isValidPingServer($value) {
		return TRUE;
	}


	public function isValidPingName($value) {
		return TRUE;
	}

}
