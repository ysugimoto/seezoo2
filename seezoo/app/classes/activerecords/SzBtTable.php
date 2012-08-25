<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtTableActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_table';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'table_data' => array('type' => 'TEXT')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidTableData($value) {
		return TRUE;
	}

}
