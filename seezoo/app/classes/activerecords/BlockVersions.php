<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BlockVersionsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'block_versions';
	protected $_primary = 'block_version_id';
	protected $_schemas = array(
		'block_version_id' => array('type' => 'INT'),
		'block_id' => array('type' => 'INT'),
		'collection_name' => array('type' => 'VARCHAR'),
		'area_id' => array('type' => 'INT'),
		'display_order' => array('type' => 'INT'),
		'is_active' => array('type' => 'INT'),
		'version_date' => array('type' => 'DATETIME'),
		'version_number' => array('type' => 'INT'),
		'ct_handle' => array('type' => 'VARCHAR'),
		'slave_block_id' => array('type' => 'INT')
	); 
	
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


	public function isValidVersionDate($value) {
		return TRUE;
	}


	public function isValidVersionNumber($value) {
		return TRUE;
	}


	public function isValidCtHandle($value) {
		return TRUE;
	}


	public function isValidSlaveBlockId($value) {
		return TRUE;
	}

}
