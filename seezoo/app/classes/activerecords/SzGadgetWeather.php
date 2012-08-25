<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SzGadgetWeatherActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'sz_gadget_weather';
	protected $_primary = 'sz_gadget_weather_id';
	protected $_schemas = array(
		'sz_gadget_weather_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR'),
		'city_id' => array('type' => 'INT')
	); 
	
	public function isValidSzGadgetWeatherId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}


	public function isValidCityId($value) {
		return TRUE;
	}

}
