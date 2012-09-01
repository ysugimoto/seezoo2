<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BtTabRelationsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'bt_tab_relations';
	protected $_primary = 'tab_relation_id';
	protected $_schemas = array(
		'tab_relation_id' => array('type' => 'INT'),
		'tab_relation_key' => array('type' => 'VARCHAR'),
		'contents_name' => array('type' => 'VARCHAR')
	); 
	
	public function isValidTabRelationId($value) {
		return TRUE;
	}


	public function isValidTabRelationKey($value) {
		return TRUE;
	}


	public function isValidContentsName($value) {
		return TRUE;
	}

}
