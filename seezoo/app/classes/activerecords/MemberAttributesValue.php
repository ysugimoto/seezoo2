<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class MemberAttributesValueActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'member_attributes_value';
	protected $_primary = '';
	protected $_schemas = array(
		'member_id' => array('type' => 'INT'),
		'member_attributes_id' => array('type' => 'INT'),
		'member_attributes_value' => array('type' => 'VARCHAR'),
		'member_attributes_value_text' => array('type' => 'TEXT')
	); 
	
	public function isValidMemberId($value) {
		return TRUE;
	}


	public function isValidMemberAttributesId($value) {
		return TRUE;
	}


	public function isValidMemberAttributesValue($value) {
		return TRUE;
	}


	public function isValidMemberAttributesValueText($value) {
		return TRUE;
	}

}
