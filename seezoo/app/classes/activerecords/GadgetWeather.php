<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class GadgetWeatherActiveRecord extends SZ_ActiveRecord
{
	protected $_table   = 'gadget_weather';
	protected $_primary = 'gadget_weather_id';
	protected $_schemas = array(
		'gadget_weather_id' => array('type' => 'INT'),
		'token' => array('type' => 'VARCHAR'),
		'city_id' => array('type' => 'INT')
	); 
	
	public function isValidGadgetWeatherId($value) {
		return TRUE;
	}


	public function isValidToken($value) {
		return TRUE;
	}


	public function isValidCityId($value) {
		return TRUE;
	}

}
