<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SearchIndexActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'search_index';
	protected $_primary = 'page_id';
	protected $_schemas = array(
		'page_id' => array('type' => 'INT'),
		'page_path' => array('type' => 'VARCHAR'),
		'page_title' => array('type' => 'VARCHAR'),
		'indexed_words' => array('type' => 'TEXT')
	); 
	
	public function isValidPageId($value) {
		return TRUE;
	}


	public function isValidPagePath($value) {
		return TRUE;
	}


	public function isValidPageTitle($value) {
		return TRUE;
	}


	public function isValidIndexedWords($value) {
		return TRUE;
	}

}
