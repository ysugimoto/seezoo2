<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtGooglemapBlockActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_googlemap_block';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'api_key' => array('type' => 'VARCHAR'),
		'zoom' => array('type' => 'INT'),
		'address' => array('type' => 'VARCHAR'),
		'lat' => array('type' => 'VARCHAR'),
		'lng' => array('type' => 'VARCHAR'),
		'width' => array('type' => 'INT'),
		'height' => array('type' => 'INT'),
		'version' => array('type' => 'TINYINT')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidApiKey($value) {
		return TRUE;
	}


	public function isValidZoom($value) {
		return TRUE;
	}


	public function isValidAddress($value) {
		return TRUE;
	}


	public function isValidLat($value) {
		return TRUE;
	}


	public function isValidLng($value) {
		return TRUE;
	}


	public function isValidWidth($value) {
		return TRUE;
	}


	public function isValidHeight($value) {
		return TRUE;
	}


	public function isValidVersion($value) {
		return TRUE;
	}

}
