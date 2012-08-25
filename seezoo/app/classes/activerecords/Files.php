<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class FilesActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'files';
	protected $_primary = 'file_id';
	protected $_schemas = array(
		'file_id' => array('type' => 'INT'),
		'file_name' => array('type' => 'VARCHAR'),
		'crypt_name' => array('type' => 'VARCHAR'),
		'extension' => array('type' => 'VARCHAR'),
		'width' => array('type' => 'INT'),
		'height' => array('type' => 'INT'),
		'added_date' => array('type' => 'DATETIME'),
		'size' => array('type' => 'INT'),
		'file_group' => array('type' => 'VARCHAR'),
		'directories_id' => array('type' => 'INT'),
		'download_count' => array('type' => 'INT')
	); 
	
	public function isValidFileId($value) {
		return TRUE;
	}


	public function isValidFileName($value) {
		return TRUE;
	}


	public function isValidCryptName($value) {
		return TRUE;
	}


	public function isValidExtension($value) {
		return TRUE;
	}


	public function isValidWidth($value) {
		return TRUE;
	}


	public function isValidHeight($value) {
		return TRUE;
	}


	public function isValidAddedDate($value) {
		return TRUE;
	}


	public function isValidSize($value) {
		return TRUE;
	}


	public function isValidFileGroup($value) {
		return TRUE;
	}


	public function isValidDirectoriesId($value) {
		return TRUE;
	}


	public function isValidDownloadCount($value) {
		return TRUE;
	}

}
