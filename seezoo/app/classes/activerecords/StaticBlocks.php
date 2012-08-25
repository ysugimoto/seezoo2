<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class StaticBlocksActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'static_blocks';
	protected $_primary = 'static_block_id';
	protected $_schemas = array(
		'static_block_id' => array('type' => 'INT'),
		'block_id' => array('type' => 'INT'),
		'collection_name' => array('type' => 'VARCHAR'),
		'add_user_id' => array('type' => 'INT'),
		'tmp_static_from' => array('type' => 'INT'),
		'alias_name' => array('type' => 'VARCHAR')
	); 
	
	public function isValidStaticBlockId($value) {
		return TRUE;
	}


	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidCollectionName($value) {
		return TRUE;
	}


	public function isValidAddUserId($value) {
		return TRUE;
	}


	public function isValidTmpStaticFrom($value) {
		return TRUE;
	}


	public function isValidAliasName($value) {
		return TRUE;
	}

}
