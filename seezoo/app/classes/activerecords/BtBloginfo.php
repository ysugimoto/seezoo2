<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BtBloginfoActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'bt_bloginfo';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'category' => array('type' => 'INT'),
		'view_count' => array('type' => 'INT'),
		'accept_comment_only' => array('type' => 'TINYINT'),
		'accept_trackback_only' => array('type' => 'TINYINT'),
		'posted_user' => array('type' => 'INT')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidCategory($value) {
		return TRUE;
	}


	public function isValidViewCount($value) {
		return TRUE;
	}


	public function isValidAcceptCommentOnly($value) {
		return TRUE;
	}


	public function isValidAcceptTrackbackOnly($value) {
		return TRUE;
	}


	public function isValidPostedUser($value) {
		return TRUE;
	}

}
