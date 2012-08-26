<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');
/**
 * ===============================================================================
 * 
 * Seezoo ログインリードクラス
 * 
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */
class LoginLead extends SZ_Lead
{
	protected $tokenName = 'sz_login_token';
	
	public function index($noValidation = FALSE)
	{
		$data            = new stdClass;
		$data->tokenName = $this->tokenName;
		$data->token     = $this->session->generateToken($this->tokenName);
		if ( ! $noValidation )
		{
			$Validation = $this->_validation();
		}
		
		return $data;
	}
	
	public function do_login_post()
	{
		$request = Seezoo::getRequest();
		if ( FALSE === $this->session->checkToken($this->tokenName, $request->post($this->tokenName)) )
		{
			exit('チケットが不正です。');
		}
		
		$Validation = $this->_validation();
		
		if ( FALSE === $Validation->run() )
		{
			return $this->index(TRUE);
		}
		
		$username = $Validation->value('user_name');
		$password = $Validation->value('password');
		$remember = $Validation->value('remember');
		$result   = $this->authModel->adminLogin($username, $password, $remember);
		
		if ( $result )
		{
			Seezoo::$Response->redirect($result);
		}
		
		$data            = new stdClass;
		$data->msg       = 'ログインに失敗しました。ユーザ名とパスワードを確認してください。';
		$data->tokenName = $this->tokenName;
		$data->token     = $this->session->generateToken($this->tokenName);
		return $data;
	}
	
	protected function _validation($forgetPassword = FALSE)
	{
		$Validation = Seezoo::$Importer->library('Validation');
		$Validation->delimiter('<span class="error">', '</span>');
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
		$Validation->setRules($conf);
		return $Validation;
	}
}
