<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 *
 * Seezooユーザ管理系モデルクラス
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class UserModel extends SZ_Kennel
{
	public function isAdmin($userID = 0)
	{
		if ( $userID == 0 )
		{
			$sess   = Seezoo::$Importer->library('Session');
			$userID = $sess->get('user_id');
			if ( $userID == 0 )
			{
				return FALSE;
			}
		}
		else if ( $userID == 1 )
		{
			return TRUE; // master user
		}
		
		$User = ActiveRecord::finder('users')
		        ->findByUserId($userID, 'admin_flag');
		
		return ( $User && $User->admin_flag > 0 ) ? TRUE : FALSE;
	}
	
	public function getUserList()
	{
		$Users = ActiveRecord::finder('users')
		          ->findAll();
		$userList = array();
		
		// create virtual data
		$user = new stdClass;
		$user->user_name  = '一般ユーザー';
		$user->admin_flag = 0;
		$userList[0] = $user;
		$user = new stdClass;
		$user->user_name  = '登録メンバー';
		$user->admin_flag = 0;
		$userList['m'] = $user;
		
		foreach ( $Users as $u )
		{
			$userList[$u->user_id] = $u;
		}
		return $userList;
	}
}
