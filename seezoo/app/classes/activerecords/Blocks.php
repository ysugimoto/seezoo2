<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BlocksActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'blocks';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'collection_name' => array('type' => 'VARCHAR'),
		'is_active' => array('type' => 'INT'),
		'created_time' => array('type' => 'DATETIME')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidCollectionName($value) {
		return TRUE;
	}


	public function isValidIsActive($value) {
		return TRUE;
	}


	public function isValidCreatedTime($value) {
		return TRUE;
	}

}
