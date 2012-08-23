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
class LoginController extends EX_Breeder
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
		
		if ( defined('SEEZOO_SYSTEM_LOGIN_URI') )
		{
			if ( $this->request->segment(1) !== SEEZOO_SYSTEM_LOGIN_URI )
			{
				show_404();
			}
		}
	}
	
	public function index()
	{
		$this->authModel->checkRememberLoggedIn();
		$data = $this->gap->call();
		
		$data = new stdClass;
		$data->tokenName = $this->tokenName;
		$data->token     = $this->generateTicket($this->tokenName);
		$this->_validation();
		
		$this->view->assign($data);
		$this->view->render($this->dir . 'index');
	}
	
	public function do_login()
	{
		if ( FALSE === $this->checkTicket($this->tokenName) )
		{
			
		}
		
		$this->env->debugger(TRUE);
		
		$data = new stdClass;
		$this->_validation();
		if ( FALSE === $this->validation->run() )
		{
			$data->tokenName = $this->tokenName;
			$data->token     = $this->request->post($this->tokenName);
			$this->view->assign($data);
			$this->view->render($this->dir . 'index');
			return;
		}
		
		$username   = $this->validation->value('user_name');
		$password   = $this->validation->value('password');
		$remember   = $this->validation->value('remember');
		$resultPath = $this->authModel->adminLogin($username, $password, $remember);
		
		if ( $resultPath )
		{
			$this->response->redirect($resultPath);
		}
		else
		{
			$data->msg = 'ログインに失敗しました。ユーザ名とパスワードを確認してください。';
			$data->tokenName = $this->tokenName;
			$data->token     = $this->generateTicket($this->tokenName);
			$this->view->assign($data);
			$this->view->render($this->dir . 'index');
		}
	}
	
	protected function _validation($forgetPassword = FALSE)
	{
		$this->import->library('Validation');
		$this->validation->setErrorDelimiters('<span class="error">', '</span>');
		$conf = array(
			array(
				'field' => 'user_name',
				'rules' => ($forgetPassword) ?  '' : 'trim|required',
				'label' => 'ユーザー名'
			),
			array(
				'field' => 'password',
				'rules' => ($forgetPassword) ? '' : 'trim|required',
				'label' => 'パスワード'
			),
			array(
				'field' => 'forgotten_mail',
				'rules' => ($forgetPassword) ? 'trim|required|valid_email|max_length[255]|callback_already' : '',
				'label' => 'メールアドレス'
			)
		);
		
		$this->validation->setRules($conf);
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
