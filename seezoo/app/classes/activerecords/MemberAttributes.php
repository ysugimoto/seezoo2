<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class MemberAttributesActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'member_attributes';
	protected $_primary = 'member_attributes_id';
	protected $_schemas = array(
		'member_attributes_id' => array('type' => 'INT'),
		'attribute_name' => array('type' => 'VARCHAR'),
		'attribute_type' => array('type' => 'VARCHAR'),
		'rows' => array('type' => 'INT'),
		'cols' => array('type' => 'INT'),
		'options' => array('type' => 'VARCHAR'),
		'validate_rule' => array('type' => 'VARCHAR'),
		'is_use' => array('type' => 'TINYINT'),
		'display_order' => array('type' => 'INT'),
		'is_inputable' => array('type' => 'TINYINT')
	); 
	
	public function isValidMemberAttributesId($value) {
		return TRUE;
	}


	public function isValidAttributeName($value) {
		return TRUE;
	}


	public function isValidAttributeType($value) {
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


	public function isValidValidateRule($value) {
		return TRUE;
	}


	public function isValidIsUse($value) {
		return TRUE;
	}


	public function isValidDisplayOrder($value) {
		return TRUE;
	}


	public function isValidIsInputable($value) {
		return TRUE;
	}

}
