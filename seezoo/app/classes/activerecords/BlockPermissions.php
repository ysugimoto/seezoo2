<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BlockPermissionsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'block_permissions';
	protected $_primary = 'block_permissions_id';
	protected $_schemas = array(
		'block_permissions_id' => array('type' => 'INT'),
		'block_id' => array('type' => 'INT'),
		'allow_view_id' => array('type' => 'VARCHAR'),
		'allow_edit_id' => array('type' => 'VARCHAR')
	); 
	
	public function isValidBlockPermissionsId($value) {
		return TRUE;
	}


	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidAllowViewId($value) {
		return TRUE;
	}


	public function isValidAllowEditId($value) {
		return TRUE;
	}

}
