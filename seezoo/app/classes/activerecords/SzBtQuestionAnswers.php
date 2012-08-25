<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtQuestionAnswersActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_question_answers';
	protected $_primary = '';
	protected $_schemas = array(
		'question_key' => array('type' => 'VARCHAR'),
		'question_id' => array('type' => 'INT'),
		'answer' => array('type' => 'VARCHAR'),
		'answer_text' => array('type' => 'TEXT'),
		'post_date' => array('type' => 'DATETIME')
	); 
	
	public function isValidQuestionKey($value) {
		return TRUE;
	}


	public function isValidQuestionId($value) {
		return TRUE;
	}


	public function isValidAnswer($value) {
		return TRUE;
	}


	public function isValidAnswerText($value) {
		return TRUE;
	}


	public function isValidPostDate($value) {
		return TRUE;
	}

}
