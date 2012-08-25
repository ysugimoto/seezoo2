<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BlockRelationsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'block_relations';
	protected $_primary = 'block_relations_id';
	protected $_schemas = array(
		'block_relations_id' => array('type' => 'INT'),
		'block_id' => array('type' => 'INT'),
		'slave_block_id' => array('type' => 'INT')
	); 
	
	public function isValidBlockRelationsId($value) {
		return TRUE;
	}


	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidSlaveBlockId($value) {
		return TRUE;
	}

}
