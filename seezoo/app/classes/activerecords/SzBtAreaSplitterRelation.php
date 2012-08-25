<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtAreaSplitterRelationActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_area_splitter_relation';
	protected $_primary = '';
	protected $_schemas = array(
		'as_relation_key' => array('type' => 'VARCHAR'),
		'contents_name' => array('type' => 'VARCHAR'),
		'contents_per' => array('type' => 'INT')
	); 
	
	public function isValidAsRelationKey($value) {
		return TRUE;
	}


	public function isValidContentsName($value) {
		return TRUE;
	}


	public function isValidContentsPer($value) {
		return TRUE;
	}

}
