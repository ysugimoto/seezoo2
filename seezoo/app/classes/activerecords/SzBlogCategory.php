<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBlogCategoryActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_blog_category';
	protected $_primary = 'sz_blog_category_id';
	protected $_schemas = array(
		'sz_blog_category_id' => array('type' => 'INT'),
		'category_name' => array('type' => 'VARCHAR'),
		'is_use' => array('type' => 'INT')
	); 
	
	public function isValidSzBlogCategoryId($value) {
		return TRUE;
	}


	public function isValidCategoryName($value) {
		return TRUE;
	}


	public function isValidIsUse($value) {
		return TRUE;
	}

}
