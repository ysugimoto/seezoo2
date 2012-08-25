<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzGadgetBbsDataActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_gadget_bbs_data';
	protected $_primary = 'sz_gadget_bbs_data_id';
	protected $_schemas = array(
		'sz_gadget_bbs_data_id' => array('type' => 'INT'),
		'posted_user_id' => array('type' => 'INT'),
		'post_date' => array('type' => 'DATETIME'),
		'body' => array('type' => 'VARCHAR')
	); 
	
	public function isValidSzGadgetBbsDataId($value) {
		return TRUE;
	}


	public function isValidPostedUserId($value) {
		return TRUE;
	}


	public function isValidPostDate($value) {
		return TRUE;
	}


	public function isValidBody($value) {
		return TRUE;
	}

}
