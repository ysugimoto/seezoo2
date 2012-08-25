<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class PendingBlocksActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'pending_blocks';
	protected $_primary = 'pending_block_id';
	protected $_schemas = array(
		'pending_block_id' => array('type' => 'INT'),
		'block_version_id' => array('type' => 'INT'),
		'block_id' => array('type' => 'INT'),
		'collection_name' => array('type' => 'VARCHAR'),
		'area_id' => array('type' => 'INT'),
		'display_order' => array('type' => 'INT'),
		'is_active' => array('type' => 'INT'),
		'version_number' => array('type' => 'INT'),
		'version_date' => array('type' => 'DATETIME'),
		'ct_handle' => array('type' => 'VARCHAR'),
		'slave_block_id' => array('type' => 'INT')
	); 
	
	public function isValidPendingBlockId($value) {
		return TRUE;
	}


	public function isValidBlockVersionId($value) {
		return TRUE;
	}


	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidCollectionName($value) {
		return TRUE;
	}


	public function isValidAreaId($value) {
		return TRUE;
	}


	public function isValidDisplayOrder($value) {
		return TRUE;
	}


	public function isValidIsActive($value) {
		return TRUE;
	}


	public function isValidVersionNumber($value) {
		return TRUE;
	}


	public function isValidVersionDate($value) {
		return TRUE;
	}


	public function isValidCtHandle($value) {
		return TRUE;
	}


	public function isValidSlaveBlockId($value) {
		return TRUE;
	}

}
