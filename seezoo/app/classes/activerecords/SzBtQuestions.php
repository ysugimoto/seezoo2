<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtQuestionsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_questions';
	protected $_primary = 'question_id';
	protected $_schemas = array(
		'question_id' => array('type' => 'INT'),
		'question_key' => array('type' => 'VARCHAR'),
		'question_name' => array('type' => 'VARCHAR'),
		'question_type' => array('type' => 'VARCHAR'),
		'validate_rules' => array('type' => 'VARCHAR'),
		'rows' => array('type' => 'INT'),
		'cols' => array('type' => 'INT'),
		'options' => array('type' => 'VARCHAR'),
		'accept_ext' => array('type' => 'VARCHAR'),
		'max_file_size' => array('type' => 'INT'),
		'display_order' => array('type' => 'INT'),
		'is_active' => array('type' => 'INT'),
		'class_name' => array('type' => 'VARCHAR'),
		'caption' => array('type' => 'VARCHAR')
	); 
	
	public function isValidQuestionId($value) {
		return TRUE;
	}


	public function isValidQuestionKey($value) {
		return TRUE;
	}


	public function isValidQuestionName($value) {
		return TRUE;
	}


	public function isValidQuestionType($value) {
		return TRUE;
	}


	public function isValidValidateRules($value) {
		return TRUE;
	}


	public function isValidRows($value) {
		return TRUE;
	}


	public function isValidCols($value) {
		return TRUE;
	}


	public function isValidOptions($value) {
		return TRUE;
	}


	public function isValidAcceptExt($value) {
		return TRUE;
	}


	public function isValidMaxFileSize($value) {
		return TRUE;
	}


	public function isValidDisplayOrder($value) {
		return TRUE;
	}


	public function isValidIsActive($value) {
		return TRUE;
	}


	public function isValidClassName($value) {
		return TRUE;
	}


	public function isValidCaption($value) {
		return TRUE;
	}

}
