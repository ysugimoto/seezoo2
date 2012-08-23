<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 *
 * seezooログイン認証系モデルクラス
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class AuthModel extends SZ_Kennel
{
	protected $remmemberLoginCookieName = 'seezoo_remembers';
	protected $table = 'sz_users';
	protected $cryptAlgorithms = array(
		'$1$' => 'md5',
		'$2$' => 'sha1',
		'$3$' => 'sha256'
	);
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 * Check do you have remeber token?
	 * 
	 * @access public
	 * @return bool
	 */
	public function checkRememberLoggedIn()
	{
		$request = Seezoo::getRequest();
		$cookie  = $request->cookie($this->remmemberLoginCookieName);
		if ( $cookie )
		{
			return $this->doRememberLogin($cookie);
		}
		return FALSE;
	}
	
	
	/**
	 * Execute admin user login
	 * 
	 * @access public
	 * @param string $username
	 * @param string $password
	 * @param string $remember
	 * @return mixed
	 */
	public function adminLogin($username, $password, $remenber)
	{
		$condition = array(
			'user_name'        => $username,
			'login_miss_count' => '< 3' 
		);
		$user = $this->find('*', $condition);
		if ( ! $user )
		{
			return FALSE;
		}
		
		// Password matching
		if ( $this->isMatchedPassword($user, $password) === FALSE )
		{
			if ( $user->login_miss_count < 4 && $user->user_id > 1 )
			{
				$data = array(
					'login_miss_count' => (int)$user->login_miss_count + 1
				);
				$this->db->update(
							$this->table,
							$data,
							array('user_id' => $user->user_id)
						);
			}
			return FALSE;
		}
		
		// login process
		$data = array(
			'last_login'       => date('Y-m-d H:i:s'),
			'login_times'      => (int)$user->login_times + 1,
			'login_miss_count' => 0
		);
		$condition = array('user_id' => $user->user_id);
		$this->db->update($this->table, $data, $condition);
		
		// Save userID to sesssion
		$session = Seezoo::$Importer->library('session');
		$session->set('user_id', $user->user_id);
		
		// To disable all the editing status of past
		//$process = Seezoo::$Importer->model('ProcessModel');
		//$process->deleteAllEditStatus($user->user_id);
		
		return ( $user->admin_flag > 0 ) ? 'dashboard/panel' : '/';
	}
	
	/**
	 * Password Matching
	 * 
	 * @access protected
	 * @param DB_ResultObject $user
	 * @param string $password
	 * @return bool
	 */
	protected function isMatchedPassword($user, $password)
	{
		// Detect password encrypt algorithm
		$algorithmMark  = substr($user->password, 0, 3);
		$algorithm      = 'md5';
		$storedPassword = $user->password;
		$dashboard      = Seezoo::$Importer->model('DashboardModel');
		
		if ( isset($this->cryptAlgorithms[$algorithmMark]) )
		{
			$algorithm      = $this->cryptAlgorithms[$algorithmMark];
			$storedPassword = substr($user->possword, 3);
		}
		$password = $dashboard->stretchPassword($user->hash, $password, $algorithm);
		echo $password;
		return ( $password === $storedPassword ) ? TRUE : FALSE;
	}
	
	
	/**
	 * Check email record is already exists in User table?
	 */
	public function isEmailExists($email)
	{
		return ( $this->findOne('user_id', array('email' => $email)) ) ? TRUE : FALSE;
	}
		
}
