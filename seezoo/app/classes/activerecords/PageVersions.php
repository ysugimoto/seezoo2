<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class PageVersionsActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'page_versions';
	protected $_primary = 'page_version_id';
	protected $_schemas = array(
		'page_version_id' => array('type' => 'INT'),
		'version_number' => array('type' => 'INT'),
		'page_id' => array('type' => 'INT'),
		'page_title' => array('type' => 'VARCHAR'),
		'template_id' => array('type' => 'INT'),
		'meta_title' => array('type' => 'VARCHAR'),
		'meta_keyword' => array('type' => 'VARCHAR'),
		'meta_description' => array('type' => 'TEXT'),
		'navigation_show' => array('type' => 'INT'),
		'parent' => array('type' => 'INT'),
		'display_order' => array('type' => 'INT'),
		'display_page_level' => array('type' => 'INT'),
		'version_date' => array('type' => 'DATETIME'),
		'created_user_id' => array('type' => 'INT'),
		'is_public' => array('type' => 'INT'),
		'public_datetime' => array('type' => 'DATETIME'),
		'approved_user_id' => array('type' => 'INT'),
		'version_comment' => array('type' => 'VARCHAR'),
		'is_system_page' => array('type' => 'INT'),
		'is_mobile_only' => array('type' => 'INT'),
		'is_ssl_page' => array('type' => 'TINYINT'),
		'alias_to' => array('type' => 'INT'),
		'page_description' => array('type' => 'VARCHAR'),
		'external_link' => array('type' => 'VARCHAR'),
		'target_blank' => array('type' => 'TINYINT')
	); 
	
	public function isValidPageVersionId($value) {
		return TRUE;
	}


	public function isValidVersionNumber($value) {
		return TRUE;
	}


	public function isValidPageId($value) {
		return TRUE;
	}


	public function isValidPageTitle($value) {
		return TRUE;
	}


	public function isValidTemplateId($value) {
		return TRUE;
	}


	public function isValidMetaTitle($value) {
		return TRUE;
	}


	public function isValidMetaKeyword($value) {
		return TRUE;
	}


	public function isValidMetaDescription($value) {
		return TRUE;
	}


	public function isValidNavigationShow($value) {
		return TRUE;
	}


	public function isValidParent($value) {
		return TRUE;
	}


	public function isValidDisplayOrder($value) {
		return TRUE;
	}


	public function isValidDisplayPageLevel($value) {
		return TRUE;
	}


	public function isValidVersionDate($value) {
		return TRUE;
	}


	public function isValidCreatedUserId($value) {
		return TRUE;
	}


	public function isValidIsPublic($value) {
		return TRUE;
	}


	public function isValidPublicDatetime($value) {
		return TRUE;
	}


	public function isValidApprovedUserId($value) {
		return TRUE;
	}


	public function isValidVersionComment($value) {
		return TRUE;
	}


	public function isValidIsSystemPage($value) {
		return TRUE;
	}


	public function isValidIsMobileOnly($value) {
		return TRUE;
	}


	public function isValidIsSslPage($value) {
		return TRUE;
	}


	public function isValidAliasTo($value) {
		return TRUE;
	}


	public function isValidPageDescription($value) {
		return TRUE;
	}


	public function isValidExternalLink($value) {
		return TRUE;
	}


	public function isValidTargetBlank($value) {
		return TRUE;
	}

}
