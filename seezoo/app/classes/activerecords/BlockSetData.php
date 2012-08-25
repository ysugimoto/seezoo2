<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BlockSetDataActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'block_set_data';
	protected $_primary = 'block_set_data_id';
	protected $_schemas = array(
		'block_set_data_id' => array('type' => 'INT'),
		'block_set_master_id' => array('type' => 'INT'),
		'display_order' => array('type' => 'INT'),
		'block_id' => array('type' => 'INT')
	); 
	
	public function isValidBlockSetDataId($value) {
		return TRUE;
	}


	public function isValidBlockSetMasterId($value) {
		return TRUE;
	}


	public function isValidDisplayOrder($value) {
		return TRUE;
	}


	public function isValidBlockId($value) {
		return TRUE;
	}

}
