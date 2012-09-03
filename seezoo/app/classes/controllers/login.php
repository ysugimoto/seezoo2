<?php
/**
 * ===============================================================================
 * 
 * Seezoo ログインコントローラ
 * 
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */
class LoginController extends SZ_Breeder
{
	public static $pageTitle   = 'ログイン';
	public static $description = 'ログイン画面を表示します。';
	
	protected $tokenName = 'sz_login_token';
	protected $dir       = 'login/';
	protected $msg;
	
	public function __construct()
	{
		parent::__construct();
		$this->import->helper('form');
		$this->import->model('AuthModel');
		
		if ( $this->request->segment(1) !== SEEZOO_SYSTEM_LOGIN_URI )
		{
			show_404();
		}
		
	}
	
	public function index()
	{
		$this->authModel->checkRememberLoggedIn();
		$this->lead->call();
		
		$this->view->render($this->dir . 'index');
	}
	
	public function do_login_post()
	{
		$this->lead->call();
		
		$this->view->render($this->dir . 'index');
	}
	
	public function already($str)
	{
		$already = $this->authModel->isEmailExists($str);
		if ( $already )
		{
			$this->validation->setMessage('already', '入力されたメールアドレスは存在しません。');
			return FALSE;
		}
		return TRUE;
	}
}
