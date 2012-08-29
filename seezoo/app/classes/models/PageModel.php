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
	
	public function detectPage($path)
	{
		$sql =
			'SELECT '
			.	'PP.page_path, '
			.	'PERM.allow_edit_user, '
			.	'PERM.allow_access_user, '
			.	'PERM.allow_approve_user, '
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
			.	'( PP.page_id = ? OR PP.page_path = ? ) '
			.'LIMIT 1'
			;
		$query = $this->db->query($sql, array($path, $path));
		if ( $query->row() )
		{
			return $query->row();
		}
		return FALSE;
	}
	
	public function getPageObject($pageID, $versionMode)
	{
		$prefix = $this->db->prefix();
		// switch get page method by version mode
		if ( $versionMode === 'editting' )
		{
			// current editting version
			$sql =
					'SELECT '
					.	'* '
					.'FROM '
					.	$prefix.'pending_pages as pv '
					.'LEFT OUTER JOIN ' . $prefix.'templates as tpl ON ( '
					.	'pv.template_id = tpl.template_id '
					.') '
					.'WHERE '
					.	'page_id = ? '
					.'ORDER BY '
					.	'version_number DESC '
					.'LIMIT 1'
					;
			$query = $this->db->query($sql, array($pageID));
		}
		else if ( $versionMode === 'approve' )
		{
			// normal access view. get approved version
			$sql =
					'SELECT '
					.	'* '
					.'FROM '
					.	$prefix.'page_versions as pv '
					.'LEFT OUTER JOIN ' . $prefix.'templates as tpl ON ( '
					.	'pv.template_id = tpl.template_id '
					.') '
					.'WHERE '
					.	'page_id = ? '
					.'AND '
					.	'is_public = 1 '
					.'AND '
					.	'public_datetime < ? '
					.'ORDER BY '
					.	'version_number DESC '
					.'LIMIT 1'
					;
			$query = $this->db->query($sql, array($pageID, db_datetime()));
		}
		else if (is_numeric($ver))
		{
			// version string is numeric, get target version (this process works preview only.)
			$sql =
					'SELECT '
					.	'* '
					.'FROM '
					.	$prefix.'page_versions as pv '
					.'LEFT OUTER JOIN ' . $prefix.'templates as tpl ON ( '
					.	'pv.template_id = tpl.template_id '
					.') '
					.'WHERE '
					.	'page_id = ? '
					.'AND '
					.	'version_number = ? '
					.'LIMIT 1'
					;
			$query= $this->db->query($sql, array($pageID, (int)$versionMode));
		}
		else // recent
		{
			$sql =
					'SELECT '
					.	'* '
					.'FROM '
					.	$prefix.'page_versions as pv '
					.'LEFT OUTER JOIN ' . $prefix.'page_permissions as perms ON ( '
					.	'pv.page_id = perms.page_id '
					.') '
					.'LEFT OUTER JOIN ' . $prefix.'templates as tpl ON ( '
					.	'pv.template_id = tpl.template_id '
					.') '
					.'WHERE '
					.	'pv.page_id = ? '
					.'ORDER BY '
					.	'pv.version_number DESC '
					.'LIMIT 1'
					;
			$query = $this->db->query($sql, array($pageID));
		}

		if ( ! $query || ! $query->row() )
		{
			return FALSE;
		}
		return $query->row();
	}
}
