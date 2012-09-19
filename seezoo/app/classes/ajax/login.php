<?php
/**
 * ===============================================================================
 * 
 * Seezoo ログインコントローラ (Ajax)
 * 
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */
class LoginController extends SZ_Breeder
{
	public function __construct()
	{
		parent::__construct();
		$this->import->model('AuthModel');
		$this->import->library('Session');
	}
	
	public function index_post()
	{
		$username = $this->request->post('username');
		$password = $this->request->post('password');
		$result   = $this->authModel->adminLogin($username, $password, FALSE);
		$respose  = array();
		
		if ( $result )
		{
			$token =sha1(uniqid(mt_ramd(), TRUE));
			$this->session->set('sz_token', $token);
			$response['status'] = 'success';
			$response['token']  = $token;
		}
		else
		{
			$response['status'] = 'error';
		}
		Seezoo::$Response->displayJSON($response);
	}
}
