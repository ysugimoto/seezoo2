<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BlogCommentActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'blog_comment';
	protected $_primary = 'blog_comment_id';
	protected $_schemas = array(
		'blog_comment_id' => array('type' => 'INT'),
		'blog_id' => array('type' => 'INT'),
		'post_date' => array('type' => 'DATETIME'),
		'name' => array('type' => 'VARCHAR'),
		'comment_body' => array('type' => 'TEXT')
	); 
	
	public function isValidBlogCommentId($value) {
		return TRUE;
	}


	public function isValidBlogId($value) {
		return TRUE;
	}


	public function isValidPostDate($value) {
		return TRUE;
	}


	public function isValidName($value) {
		return TRUE;
	}


	public function isValidCommentBody($value) {
		return TRUE;
	}

}
