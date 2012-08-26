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
		
		$User = ActiveRecord::finder('user')
		        ->findByUserIs($userID, 'admin_flag');
		/*
		$sql = 'SELECT admin_flag FROM users WHERE user_id = ? LIMIT 1';
		$query = $this->db->query($sql, array((int)$uid));
		if ($query->num_rows() > 0)
		{
			$result = $query->row();
			if ((int)$result->admin_flag === 1)
			{
				return TRUE;
			}
		}*/
		return ( $User && $User->admin_flag > 0 ) ? TRUE : FALSE;
	}
}
