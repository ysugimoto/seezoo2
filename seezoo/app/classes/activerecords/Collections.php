<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class CollectionsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'collections';
	protected $_primary = 'collection_id';
	protected $_schemas = array(
		'collection_id' => array('type' => 'INT'),
		'collection_name' => array('type' => 'VARCHAR'),
		'interface_width' => array('type' => 'INT'),
		'interface_height' => array('type' => 'INT'),
		'description' => array('type' => 'VARCHAR'),
		'added_date' => array('type' => 'DATETIME'),
		'block_name' => array('type' => 'VARCHAR'),
		'db_table' => array('type' => 'VARCHAR'),
		'plugin_id' => array('type' => 'INT'),
		'is_enabled' => array('type' => 'TINYINT'),
		'pc_enabled' => array('type' => 'TINYINT'),
		'sp_enabled' => array('type' => 'TINYINT'),
		'mb_enabled' => array('type' => 'TINYINT')
	); 
	
	public function isValidCollectionId($value) {
		return TRUE;
	}


	public function isValidCollectionName($value) {
		return TRUE;
	}


	public function isValidInterfaceWidth($value) {
		return TRUE;
	}


	public function isValidInterfaceHeight($value) {
		return TRUE;
	}


	public function isValidDescription($value) {
		return TRUE;
	}


	public function isValidAddedDate($value) {
		return TRUE;
	}


	public function isValidBlockName($value) {
		return TRUE;
	}


	public function isValidDbTable($value) {
		return TRUE;
	}


	public function isValidPluginId($value) {
		return TRUE;
	}


	public function isValidIsEnabled($value) {
		return TRUE;
	}


	public function isValidPcEnabled($value) {
		return TRUE;
	}


	public function isValidSpEnabled($value) {
		return TRUE;
	}


	public function isValidMbEnabled($value) {
		return TRUE;
	}

}
