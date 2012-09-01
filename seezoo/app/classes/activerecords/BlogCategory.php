<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BlogCategoryActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'blog_category';
	protected $_primary = 'blog_category_id';
	protected $_schemas = array(
		'blog_category_id' => array('type' => 'INT'),
		'category_name' => array('type' => 'VARCHAR'),
		'is_use' => array('type' => 'INT')
	); 
	
	public function isValidBlogCategoryId($value) {
		return TRUE;
	}


	public function isValidCategoryName($value) {
		return TRUE;
	}


	public function isValidIsUse($value) {
		return TRUE;
	}

}
