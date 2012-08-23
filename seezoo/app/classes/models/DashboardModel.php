<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 *
 * Seezoo管理画面汎用モデルクラス
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class DashboardModel extends SZ_Kennel
{
	protected $useDatabase = TRUE;
	
	public function __construct($state = array())
	{
		if ( ! isset($state['disable_database']) )
		{
			parent::__construct();
		}
	}
	
	public function registAdminUser($dat)
	{
		$password = $this->enryptPassword($dat['admin_password']);
	}
	
	
	/**
	 * Encrypt password
	 * 
	 * @access public
	 * @param string $password
	 * @return array (hash, password)
	 */
	public function encryptPassword($password)
	{
		$hash     = sha1(uniqid(mt_rand(), TRUE));
		$password = $this->stretchPassword($hash, $password);
		return array(
			'hash'     => $hash,
			'password' => $password
		);
	}
	
	
	/**
	 * Password stretching
	 * 
	 * @access public
	 * @param string $hash
	 * @param string $password
	 * @param string algorithm
	 * @return string
	 */
	public function stretchPassword($hash, $password, $algorithm = 'md5')
	{
		$times = 1000;
		$hashFunction = ( function_exists($algorithm) ) ? $algorithm : FALSE;
		for ( $i = 0; $i < $times; ++$i )
		{
			$password = ( $hashFunction )
			              ? $hashFunction($hash . $password)
			              : hash($algorithm, $hash . $password);
		}
		return $password;
	}
}
