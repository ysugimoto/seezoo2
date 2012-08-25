<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class DraftBlocksActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'draft_blocks';
	protected $_primary = 'draft_blocks_id';
	protected $_schemas = array(
		'draft_blocks_id' => array('type' => 'INT'),
		'block_id' => array('type' => 'INT'),
		'collection_name' => array('type' => 'VARCHAR'),
		'drafted_user_id' => array('type' => 'INT'),
		'ct_handle' => array('type' => 'VARCHAR'),
		'alias_name' => array('type' => 'VARCHAR')
	); 
	
	public function isValidDraftBlocksId($value) {
		return TRUE;
	}


	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidCollectionName($value) {
		return TRUE;
	}


	public function isValidDraftedUserId($value) {
		return TRUE;
	}


	public function isValidCtHandle($value) {
		return TRUE;
	}


	public function isValidAliasName($value) {
		return TRUE;
	}

}
