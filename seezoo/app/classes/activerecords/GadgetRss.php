<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class GadgetRssActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'gadget_rss';
	protected $_primary = 'gadgt_rss_id';
	protected $_schemas = array(
		'gadgt_rss_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR'),
		'rss_url' => array('type' => 'VARCHAR')
	); 
	
	public function isValidGadgtRssId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}


	public function isValidRssUrl($value) {
		return TRUE;
	}

}
