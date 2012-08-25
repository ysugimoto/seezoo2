<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class AreasActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'areas';
	protected $_primary = 'area_id';
	protected $_schemas = array(
		'area_id' => array('type' => 'INT'),
		'area_name' => array('type' => 'VARCHAR'),
		'page_id' => array('type' => 'INT'),
		'created_date' => array('type' => 'DATETIME')
	); 
	
	public function isValidAreaId($value) {
		return TRUE;
	}


	public function isValidAreaName($value) {
		return TRUE;
	}


	public function isValidPageId($value) {
		return TRUE;
	}


	public function isValidCreatedDate($value) {
		return TRUE;
	}

}
