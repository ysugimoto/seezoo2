<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class PageApproveOrdersActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'page_approve_orders';
	protected $_primary = 'page_approve_orders_id';
	protected $_schemas = array(
		'page_approve_orders_id' => array('type' => 'INT'),
		'page_id' => array('type' => 'INT'),
		'version_number' => array('type' => 'INT'),
		'ordered_user_id' => array('type' => 'INT'),
		'ordered_date' => array('type' => 'DATETIME'),
		'status' => array('type' => 'INT'),
		'comment' => array('type' => 'TEXT'),
		'approved_user_id' => array('type' => 'INT'),
		'is_recieve_mail' => array('type' => 'TINYINT')
	); 
	
	public function isValidPageApproveOrdersId($value) {
		return TRUE;
	}


	public function isValidPageId($value) {
		return TRUE;
	}


	public function isValidVersionNumber($value) {
		return TRUE;
	}


	public function isValidOrderedUserId($value) {
		return TRUE;
	}


	public function isValidOrderedDate($value) {
		return TRUE;
	}


	public function isValidStatus($value) {
		return TRUE;
	}


	public function isValidComment($value) {
		return TRUE;
	}


	public function isValidApprovedUserId($value) {
		return TRUE;
	}


	public function isValidIsRecieveMail($value) {
		return TRUE;
	}

}
