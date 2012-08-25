<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzGadgetRssActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_gadget_rss';
	protected $_primary = 'sz_gadgt_rss_id';
	protected $_schemas = array(
		'sz_gadgt_rss_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR'),
		'rss_url' => array('type' => 'VARCHAR')
	); 
	
	public function isValidSzGadgtRssId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}


	public function isValidRssUrl($value) {
		return TRUE;
	}

}
