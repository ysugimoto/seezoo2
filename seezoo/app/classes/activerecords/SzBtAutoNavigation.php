<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtAutoNavigationActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_auto_navigation';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'sort_order' => array('type' => 'INT'),
		'based_page_id' => array('type' => 'INT'),
		'subpage_level' => array('type' => 'INT'),
		'manual_selected_pages' => array('type' => 'VARCHAR'),
		'handle_class' => array('type' => 'VARCHAR'),
		'display_mode' => array('type' => 'INT'),
		'show_base_page' => array('type' => 'INT'),
		'current_class' => array('type' => 'VARCHAR')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidSortOrder($value) {
		return TRUE;
	}


	public function isValidBasedPageId($value) {
		return TRUE;
	}


	public function isValidSubpageLevel($value) {
		return TRUE;
	}


	public function isValidManualSelectedPages($value) {
		return TRUE;
	}


	public function isValidHandleClass($value) {
		return TRUE;
	}


	public function isValidDisplayMode($value) {
		return TRUE;
	}


	public function isValidShowBasePage($value) {
		return TRUE;
	}


	public function isValidCurrentClass($value) {
		return TRUE;
	}

}
