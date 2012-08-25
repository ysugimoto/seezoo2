<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtAreaSplitterActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_area_splitter';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'as_relation_key' => array('type' => 'VARCHAR')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidAsRelationKey($value) {
		return TRUE;
	}

}
