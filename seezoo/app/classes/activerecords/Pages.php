<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class PagesActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'pages';
	protected $_primary = 'page_id';
	protected $_schemas = array(
		'page_id' => array('type' => 'INT'),
		'version_number' => array('type' => 'INT'),
		'is_editting' => array('type' => 'INT'),
		'edit_user_id' => array('type' => 'INT'),
		'is_arranging' => array('type' => 'INT'),
		'edit_start_time' => array('type' => 'DATETIME')
	); 
	
	public function isValidPageId($value) {
		return TRUE;
	}


	public function isValidVersionNumber($value) {
		return TRUE;
	}


	public function isValidIsEditting($value) {
		return TRUE;
	}


	public function isValidEditUserId($value) {
		return TRUE;
	}


	public function isValidIsArranging($value) {
		return TRUE;
	}


	public function isValidEditStartTime($value) {
		return TRUE;
	}

}
