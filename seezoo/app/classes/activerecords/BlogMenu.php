<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BlogMenuActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'blog_menu';
	protected $_primary = 'blog_menu_id';
	protected $_schemas = array(
		'blog_menu_id' => array('type' => 'INT'),
		'is_hidden' => array('type' => 'TINYINT'),
		'display_order' => array('type' => 'INT'),
		'menu_type' => array('type' => 'VARCHAR'),
		'menu_title' => array('type' => 'VARCHAR'),
		'description' => array('type' => 'VARCHAR')
	); 
	
	public function isValidBlogMenuId($value) {
		return TRUE;
	}


	public function isValidIsHidden($value) {
		return TRUE;
	}


	public function isValidDisplayOrder($value) {
		return TRUE;
	}


	public function isValidMenuType($value) {
		return TRUE;
	}


	public function isValidMenuTitle($value) {
		return TRUE;
	}


	public function isValidDescription($value) {
		return TRUE;
	}

}
