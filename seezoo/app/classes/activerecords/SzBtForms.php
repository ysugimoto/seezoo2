<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtFormsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_forms';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'question_key' => array('type' => 'VARCHAR'),
		'form_title' => array('type' => 'VARCHAR'),
		'use_captcha' => array('type' => 'INT'),
		'is_remail' => array('type' => 'INT'),
		're_mail' => array('type' => 'VARCHAR'),
		'thanks_msg' => array('type' => 'TEXT'),
		'form_class_name' => array('type' => 'VARCHAR')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidQuestionKey($value) {
		return TRUE;
	}


	public function isValidFormTitle($value) {
		return TRUE;
	}


	public function isValidUseCaptcha($value) {
		return TRUE;
	}


	public function isValidIsRemail($value) {
		return TRUE;
	}


	public function isValidReMail($value) {
		return TRUE;
	}


	public function isValidThanksMsg($value) {
		return TRUE;
	}


	public function isValidFormClassName($value) {
		return TRUE;
	}

}
