<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BtTextcontentActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'bt_textcontent';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'body' => array('type' => 'TEXT')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidBody($value) {
		return TRUE;
	}

}
