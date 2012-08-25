<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBlogMenuActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_blog_menu';
	protected $_primary = 'sz_blog_menu_id';
	protected $_schemas = array(
		'sz_blog_menu_id' => array('type' => 'INT'),
		'is_hidden' => array('type' => 'TINYINT'),
		'display_order' => array('type' => 'INT'),
		'menu_type' => array('type' => 'VARCHAR'),
		'menu_title' => array('type' => 'VARCHAR'),
		'description' => array('type' => 'VARCHAR')
	); 
	
	public function isValidSzBlogMenuId($value) {
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
