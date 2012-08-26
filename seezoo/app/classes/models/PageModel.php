<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');
/**
 * ===============================================================================
 * 
 * Seezoo ページ管理モデル
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class PageModel extends SZ_Kennel
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getPageStatus($pageID)
	{
		$sql =
			'SELECT '
			.	'PP.page_path, '
			.	'PERM.allow_edit_user, '
			.	'PERM.allow_access_user, '
			.	'PERM.allow_approvve_user, '
			.	'P.* '
			.'FROM '
			.	$this->db->prefix() . 'pages as P '
			.'LEFT JOIN ' . $this->db->prefix() . 'page_paths as PP ON ( '
			.	'P.page_id = PP.page_id '
			.') '
			.'LEFT OUTER JOIN ' . $this->db->prefix() . 'page_permissions as PERM ON ( '
			.	'P.page_id = PERM.page_id '
			.') '
			.'WHERE '
			.	'P.page_id = ? '
			.'LIMIT 1'
			;
		$query = $this->db->query($sql, array($pageID));
		return ( $query ) ? $query->row() : FALSE;
	}
}
