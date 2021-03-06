<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 *
 * seezoo初期化/状態取得用汎用モデルクラス
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class InitModel extends SZ_Kennel
{
	public function isLoggedIn()
	{
		$session = Seezoo::$Importer->library('Session');
		return ( $session->get('user_id') ) ? TRUE : FALSE;
	}
	
	/**
	 * Check system is already installed
	 * 
	 * @access public
	 * @return bool
	 */
	public function isAlreadyInstalled()
	{
		$env = Seezoo::getENV();
		return ( $env->getConfig('seezoo_installed') ) ? TRUE : FALSE;
	}
}
