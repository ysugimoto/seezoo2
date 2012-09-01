<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class BtFileDownloadActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'bt_file_download';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'file_id' => array('type' => 'INT'),
		'dl_text' => array('type' => 'VARCHAR')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidFileId($value) {
		return TRUE;
	}


	public function isValidDlText($value) {
		return TRUE;
	}

}
