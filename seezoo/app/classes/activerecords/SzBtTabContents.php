<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtTabContentsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_tab_contents';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'tab_relation_key' => array('type' => 'VARCHAR'),
		'single_contents' => array('type' => 'INT'),
		'link_inner' => array('type' => 'INT')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidTabRelationKey($value) {
		return TRUE;
	}


	public function isValidSingleContents($value) {
		return TRUE;
	}


	public function isValidLinkInner($value) {
		return TRUE;
	}

}
