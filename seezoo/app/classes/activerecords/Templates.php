<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class TemplatesActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'templates';
	protected $_primary = 'template_id';
	protected $_schemas = array(
		'template_id' => array('type' => 'INT'),
		'template_name' => array('type' => 'VARCHAR'),
		'template_handle' => array('type' => 'VARCHAR'),
		'description' => array('type' => 'TEXT'),
		'advance_css' => array('type' => 'TEXT')
	); 
	
	public function isValidTemplateId($value) {
		return TRUE;
	}


	public function isValidTemplateName($value) {
		return TRUE;
	}


	public function isValidTemplateHandle($value) {
		return TRUE;
	}


	public function isValidDescription($value) {
		return TRUE;
	}


	public function isValidAdvanceCss($value) {
		return TRUE;
	}

}
