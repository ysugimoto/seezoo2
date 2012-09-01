<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class GadgetsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'gadgets';
	protected $_primary = 'gadget_id';
	protected $_schemas = array(
		'gadget_id' => array('type' => 'INT'),
		'gadget_name' => array('type' => 'VARCHAR'),
		'db_table' => array('type' => 'VARCHAR'),
		'display_gadget_name' => array('type' => 'VARCHAR'),
		'gadget_description' => array('type' => 'VARCHAR')
	); 
	
	public function isValidGadgetId($value) {
		return TRUE;
	}


	public function isValidGadgetName($value) {
		return TRUE;
	}


	public function isValidDbTable($value) {
		return TRUE;
	}


	public function isValidDisplayGadgetName($value) {
		return TRUE;
	}


	public function isValidGadgetDescription($value) {
		return TRUE;
	}

}
