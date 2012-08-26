<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ===============================================================================
 * 
 * Seezoo バックエンド基底クラス
 * 
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */
class Backend
{
	public function get_backend_name()
	{
		return (isset($this->backend_name)) ? $this->backend_name : '';
	}
	
	public function get_description()
	{
		return (isset($this->description)) ? $this->description : '';
	}
	
	public function run()
	{
		return 'not processed';
	}
}