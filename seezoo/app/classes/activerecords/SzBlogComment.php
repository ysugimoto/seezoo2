<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBlogCommentActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_blog_comment';
	protected $_primary = 'sz_blog_comment_id';
	protected $_schemas = array(
		'sz_blog_comment_id' => array('type' => 'INT'),
		'sz_blog_id' => array('type' => 'INT'),
		'post_date' => array('type' => 'DATETIME'),
		'name' => array('type' => 'VARCHAR'),
		'comment_body' => array('type' => 'TEXT')
	); 
	
	public function isValidSzBlogCommentId($value) {
		return TRUE;
	}


	public function isValidSzBlogId($value) {
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
