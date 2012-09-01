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
	public function __construct($state = array())
	{
		parent::__construct();
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
	
	public function getEditPageCount($userID)
	{
		$sql = 
			'SELECT '
			.	'COUNT(page_id) as p '
			.'FROM '
			.	$this->db->prefix() . 'pages '
			.'WHERE '
			.	'edit_user_id = ? '
			.'LIMIT 1'
			;
		$result = $this->db->query($sql, array($userID))->row();
		return $result->p;
	}
	
	public function getApproveStatuses($userID)
	{
		$sql = 
			'SELECT '
			.	'pao.*, '
			.	'pv.page_title, '
			.	'u.user_name '
			.'FROM '
			.	$this->db->prefix() . 'page_approve_orders as pao '
			.'RIGHT OUTER JOIN ' . $this->db->prefix() . 'page_versions as pv ON ('
			.	'pao.version_number = pv.version_number'
			.') '
			.'LEFT OUTER JOIN ' . $this->db->prefix() . 'users as u ON('
			.	'pao.approved_user_id = u.user_id'
			.') '
			.'WHERE '
			.	'pao.ordered_user_id = ? '
			.'LIMIT 1'
			;
		$query = $this->db->query($sql, array($userID));
		if ($query && $query->numRows() > 0)
		{
			return $query->result();
		}
		return array();
	}
	
	public function getApproveRequests($userID, $isAdmin = FALSE)
	{
		$sql = 'SELECT '
			.		'* '
			.	'FROM '
			.		$this->db->prefix() . 'page_approve_orders as pao '
			.	'RIGHT OUTER JOIN ' . $this->db->prefix() . 'page_permissions as pp '
			.		'USING(page_id) '
			.	'LEFT OUTER JOIN ' . $this->db->prefix() . 'page_versions as pv ON('
			.		'pao.version_number = pv.version_number'
			.	') '
			.	'RIGHT OUTER JOIN ' . $this->db->prefix() . 'users as u ON('
			.		'pao.ordered_user_id = u.user_id'
			.	') '
			.	'WHERE '
			.		'pao.status = 0'
			;
		$query = $this->db->query($sql);
		$ret   = array();
		if ( $query && $query->numRows() > 0 )
		{
			foreach ( $query->result() as $v )
			{
				if ( strpos($v->allow_approve_user, ':' . $userID . ':') !== FALSE )
				{
					$ret[] = $v;
				}
			}
		}
		return $ret;
	}
}
