<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzBtSlideshowActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_bt_slideshow';
	protected $_primary = 'block_id';
	protected $_schemas = array(
		'block_id' => array('type' => 'INT'),
		'slide_type' => array('type' => 'VARCHAR'),
		'delay_time' => array('type' => 'INT'),
		'play_type' => array('type' => 'INT'),
		'file_ids' => array('type' => 'VARCHAR'),
		'page_ids' => array('type' => 'VARCHAR'),
		'is_caption' => array('type' => 'TINYINT')
	); 
	
	public function isValidBlockId($value) {
		return TRUE;
	}


	public function isValidSlideType($value) {
		return TRUE;
	}


	public function isValidDelayTime($value) {
		return TRUE;
	}


	public function isValidPlayType($value) {
		return TRUE;
	}


	public function isValidFileIds($value) {
		return TRUE;
	}


	public function isValidPageIds($value) {
		return TRUE;
	}


	public function isValidIsCaption($value) {
		return TRUE;
	}

}
