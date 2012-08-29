<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 * 
 * Seezoo ログアウトコントローラ
 * 
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */
class LogoutController extends EX_Breeder
{
	public static $page_title = 'ログアウト';
	public static $description = 'ログアウトを行います。';
	
	/**
	 * コンストラクタ
	 */
	function __construct()
	{
		parent::__construct();
		$this->import->model('AuthModel');
	}
	
	/**
	 * デフォルトメソッド
	 */
	function index()
	{
		$this->authModel->logout();
		
		// 継続ログインクッキーも削除する
		$Cookie = Seezoo::$Importer->helper('Cookie');
		$Cookie->delete('seezoo_remembers');
		
		$this->response->redirect('/');
	}
	
	/**
	 * メンバーのログアウト
	 */
	function logout_member()
	{
		$this->authModel->memberLogout();
		
		// 継続ログインクッキーも削除する
		$Cookie = Seezoo::$Importer->helper('Cookie');
		$Cookie->delete('seezoo_remembers_member');
		
		$this->response->redirect('/');
	}
}