<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzMemberAttributesValueActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_member_attributes_value';
	protected $_primary = '';
	protected $_schemas = array(
		'sz_member_id' => array('type' => 'INT'),
		'sz_member_attributes_id' => array('type' => 'INT'),
		'sz_member_attributes_value' => array('type' => 'VARCHAR'),
		'sz_member_attributes_value_text' => array('type' => 'TEXT')
	); 
	
	public function isValidSzMemberId($value) {
		return TRUE;
	}


	public function isValidSzMemberAttributesId($value) {
		return TRUE;
	}


	public function isValidSzMemberAttributesValue($value) {
		return TRUE;
	}


	public function isValidSzMemberAttributesValueText($value) {
		return TRUE;
	}

}
