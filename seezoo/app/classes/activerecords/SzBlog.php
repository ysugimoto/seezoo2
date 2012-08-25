<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBlogActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_blog';
	protected $_primary = 'sz_blog_id';
	protected $_schemas = array(
		'sz_blog_id' => array('type' => 'INT'),
		'user_id' => array('type' => 'INT'),
		'sz_blog_category_id' => array('type' => 'INT'),
		'title' => array('type' => 'VARCHAR'),
		'body' => array('type' => 'TEXT'),
		'entry_date' => array('type' => 'DATETIME'),
		'update_date' => array('type' => 'DATETIME'),
		'is_accept_comment' => array('type' => 'INT'),
		'is_accept_trackback' => array('type' => 'INT'),
		'is_public' => array('type' => 'TINYINT'),
		'drafted_by' => array('type' => 'INT')
	); 
	
	public function isValidSzBlogId($value) {
		return TRUE;
	}


	public function isValidUserId($value) {
		return TRUE;
	}


	public function isValidSzBlogCategoryId($value) {
		return TRUE;
	}


	public function isValidTitle($value) {
		return TRUE;
	}


	public function isValidBody($value) {
		return TRUE;
	}


	public function isValidEntryDate($value) {
		return TRUE;
	}


	public function isValidUpdateDate($value) {
		return TRUE;
	}


	public function isValidIsAcceptComment($value) {
		return TRUE;
	}


	public function isValidIsAcceptTrackback($value) {
		return TRUE;
	}


	public function isValidIsPublic($value) {
		return TRUE;
	}


	public function isValidDraftedBy($value) {
		return TRUE;
	}

}
