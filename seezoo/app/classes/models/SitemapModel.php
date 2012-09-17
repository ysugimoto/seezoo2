<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');
/**
 *  =========================================================
 * 
 * サイトマップ管理用モデルクラス
 *
 * @package seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *  =========================================================
 */
class SitemapModel extends SZ_Kennel
{
	public function getPageStructures()
	{
		// First, get top page data
		$sql = 
				'SELECT '
				.	'page_id, '
				.	'page_title, '
				.	'is_ssl_page, '
				.	'parent '
				.'FROM '
				.	$this->db->prefix().'page_versions '
				.'WHERE '
				.	'page_id = 1 '
				.'ORDER BY '
				.	'version_number DESC '
				.'LIMIT 1'
				;
		$top = $this->db->query($sql)->row();
		$top->childs = array();
		
		// Second, chid pages of toppage
		$sql =
				'SELECT '
				.	'DISTINCT PV.page_id, '
				.	'PV.page_title, '
				.	'PV.alias_to, '
				.	'PV.external_link, '
				.	'PV.target_blank, '
				.	'PV.is_ssl_page, '
				.	'PV.parent, '
				.	'PV.display_order, '
				.	'PV.is_system_page '
				.'FROM '
				.	$this->db->prefix().'page_versions as PV '
				.'LEFT OUTER JOIN ' . $this->db->prefix().'page_paths as PP ON ( '
				.	'PV.page_id = PP.page_id '
				.') '
				.'WHERE '
				.	'PV.page_id > 0 '
				.'AND '
				.	'PV.parent = 1 '
				.'AND '
				.	'( PP.page_id IS NULL OR PP.is_enabled = 1 ) '
				.'ORDER BY '
				.	'display_order ASC, '
				.	'version_number DESC '
				;
		$query = $this->db->query($sql);
		$cache = array();
		
		if ( $query->numRows() > 0 )
		{
			$childs = array();
			foreach ( $query->result() as $page )
			{
				if ( in_array($page->page_id, $cache) )
				{
					continue;
				}
				$page->childs = $this->_getChildPageCount($page->page_id);
				$top->childs[] = $page;
				$cache[] = $page->page_id;
			}
		}
		return $top;
	}

	protected function _getChildPageCount($pageID, $systemPage = FALSE)
	{
		if ( $systemPage === TRUE )
		{
			$sql =
					'SELECT '
					.	'page_id '
					.'FROM '
					.	$this->db->prefix().'page_versions '
					.'WHERE '
					.	'parent = ? '
					.'AND '
					.	'is_system_page = 1 '
					.'ORDER BY '
					.	'display_order ASC'
					;
		}
		else
		{
			$sql =
					'SELECT '
					.	'DISTINCT PV.page_id '
					.'FROM '
					.	$this->db->prefix().'page_versions as PV '
					.'JOIN ' . $this->db->prefix().'page_paths as PP ON ( '
					.	'PV.page_id = PP.page_id '
					.') '
					.'WHERE '
					.	'PV.parent = ? '
					.'AND '
					.	'PP.is_enabled = 1 '
					.'ORDER BY '
					.	'PV.display_order ASC, '
					.	'PV.version_number DESC'
					;	
		}
		
		$query = $this->db->query($sql, array($pageID));
		return $query->numRows();
	}
	
	public function deletePage($pageID)
	{
		// delete recursive
		$sql =
				'SELECT '
				.	'pv.page_id, '
				.	'pp.page_path '
				.'FROM '
				.	$this->db->prefix().'page_versions as pv '
				.'RIGHT OUTER JOIN ' . $this->db->prefix().'page_paths as pp ON ( '
				.	'pv.page_id = pp.page_id '
				.') '
				.'WHERE '
				.	'pv.parent = ? '
				.'AND '
				.	'is_system_page = 0'
				;
		$query = $this->db->query($sql, array($pageID));
		
		foreach ( $query as $page )
		{
			$this->_deletePage($page->page_id);
		}
		
		return ( $this->db->delete('page_versions', array('page_id' => $pageID))
		         && $this->db->delete('page_paths', array('page_id' => $pageID)) ) ? TRUE : FALSE;
	}
	
	public function getChildPages($pageID)
	{
		if ( $pageID == 'dashboard' )
		{
			$sql =
					'SELECT '
					.	'DISTINCT PV.page_id, '
					.	'PV.page_title, '
					.	'PV.alias_to, '
					.	'PV.parent, '
					.	'PV.external_link, '
					.	'PV.target_blank, '
					.	'PV.display_order, '
					.	'PV.is_ssl_page '
					.'FROM '
					.	$this->db->prefix().'page_versions as PV '
					.'LEFT OUTER JOIN ' . $this->db->prefix().'page_paths as PP ON ( '
					.	'PV.page_id = PP.page_id '
					.') '
					.'WHERE '
					.	'PV.display_page_level = 0 '
					.'AND '
					.	'PP.is_enabled = 1 '
					.'ORDER BY '
					.	'PV.display_order ASC'
					;
		}
		else
		{
			$sql =
					'SELECT '
					.	'pv.page_id, '
					.	'pv.page_title, '
					.	'pv.alias_to, '
					.	'pv.external_link, '
					.	'pv.target_blank, '
					.	'pv.parent, '
					.	'pv.display_order, '
					.	'pv.is_system_page, '
					.	'pv.is_ssl_page '
					.'FROM '
					.	$this->db->prefix().'page_versions as pv '
					.'JOIN ( '
					.	'SELECT '
					.		'TMPPPV.page_id, '
					.		'MAX(version_number) as version_number '
					.	'FROM '
					.		$this->db->prefix().'page_versions as TMPPPV '
					.	'GROUP BY '
					.		'TMPPPV.page_id '
					.') AS MAXPV ON ( '
					.	'pv.page_id = MAXPV.page_id '
					.'AND '
					.	'pv.version_number = MAXPV.version_number '
					.') '
					.'LEFT OUTER JOIN ' . $this->db->prefix().'page_paths as pp ON ( '
					.	'pv.page_id = pp.page_id '
					.') '
					.'WHERE '
					.	'pv.parent = ? '
					.'AND '
					.	'(pp.page_id IS NULL OR pp.is_enabled = 1) '
					.'ORDER BY '
					.	'pv.display_order ASC'
					;
		}
		$query = $this->db->query($sql, array($pageID));
		$cache = array();
		$pages = array();

		foreach ( $query->result() as $page )
		{
			if ( in_array($page->page_id, $cache) )
			{
				continue;
			}
			
			$page->childs = $this->_getChildPageCount($page->page_id, ( $pageID === 'dashboard' ) ? TRUE : FALSE);
			$pages[]      = $page;
			$cache[]      = $page->page_id;
		}
		return $pages;
	}
	
	public function movePage($from, $to)
	{
		$fromPage = ActiveRecord::finder('page_versions')
		             ->setOrderBy('version_number', 'DESC')
		             ->findByPageId($from, array('display_page_level'));
		$toPage   = ActiveRecord::finder('page_versions')
		             ->setOrderBy('version_number', 'DESC')
		             ->findByPageId($to);
		
		// Update move from page
		$fromPage->display_order      = $this->_getMaxDisplayOrder($to);
		$fromPage->parent             = $to;
		$fromPage->display_page_level = (int)$toPage->display_page_level + 1;
		if ( ! $fromPage->update() )
		{
			return FALSE;
		}
		
		// Update move from pagepath
		$movedPagePath = $this->_mergePagePath($from, $to);
		if ( ! $this->db->update('page_paths', array('page_path' => $movedPagePath), array('page_id' => $from)) )
		{
			return FALSE;
		}
		
		// Fix move from page childs pagepath
		if ( $this->_getChildPageCount($from) > 0 )
		{
			$this->_fixStrictChildPagePath($from, $movedPagePath);
		}
		return TRUE;
	}
	
	public function getMaxDisplayOrder($pageID)
	{
		$sql =
				'SELECT '
				.	'MAX(`display_order`) as m '
				.'FROM '
				.	$this->db->prefix().'page_versions '
				.'WHERE '
				.	'parent = ? '
				.'LIMIT 1'
				;
		$query  = $this->db->query($sql, array($pageID));
		$result = $query->row();
		return (int)$result->m + 1;
	}
	
	protected function _getDisplayPageLevel($pageID, $versionNumber)
	{
		$PV = ActiveRecord::finder('page_versions')
		       ->findByPageIdAndVersionNumber($pageID, $versionNumber, array('display_page_level'));
		
		if ( $PV && $PV->display_page_level > 0 )
		{
			return  (int)$PV->display_page_level;
		}
		return 0;
	}
	
	public function getCurrentVersion($pageID)
	{
		$sql =
				'SELECT '
				.	'MAX(version_number) as mv '
				.'FROM '
				.	$this->db->prefix().'page_versions '
				.'WHERE '
				.	'page_id = ? '
				.'LIMIT 1'
				;
		$query  = $this->db->query($sql, array($pageID));
		$result = $query->row();
		return (int)$result->mv;
	}
	
	protected function _mergePagePath($fromPageID, $toPageID)
	{
		$sql =
				'SELECT '
				.	'PP.page_path, '
				.	'PV.is_system_page '
				.'FROM '
				.	$this->db->prefix().'page_paths as PP '
				.'JOIN ( '
				.	'SELECT '
				.		'page_id, '
				.		'is_system_page '
				.	'FROM '
				.		$this->db->prefix().'page_versions '
				.') as PV ON ('
				.	'PV.page_id = PP.page_id '
				.') '
				.'WHERE '
				.	'PP.page_id = ? '
				.'LIMIT 1'
				;
		$from = $this->db->query($sql, array($fromPageID))->row();
		
		if ( $from->is_system_page > 0 )
		{
			return $from->page_path;
		}
		
		$fromPath  = $from->page_path;
		// merge to new path
		$splitPath = substr($fromPath, (int)strrpos($fromPath, '/'));
		
		if ( $toPageID == 1 )
		{
			// case toppage
			return trim($splitPath, '/');
		}
		$to     = $this->db->query($sql, array($toPageID))->row();
		$toPath = $to->page_path;
		
		// merge to new path
		//$split_path = substr($from_path, (int)strrpos($from_path, '/'));
		return trim($toPath, '/') . '/' . trim($splitPath, '/');
	}
	
	protected function _fixStrictChildPagePath($fromPageID, $parentPath)
	{
		$sql =
				'SELECT '
				.	'PV.page_id, '
				.	'PP.page_path '
				.'FROM '
				.	$this->db->prefix().'page_versions as PV '
				.'LEFT JOIN ' . $this->db->prefix().'page_paths as PP ON ('
				.	'PV.page_id = PP.page_id '
				.') '
				.'JOIN ( '
				.	'SELECT '
				.		'MPV.page_id,'
				.		'MAX(MPV.version_number) as version_number '
				.	'FROM '
				.		$this->db->prefix().'page_versions as MPV '
				.	'JOIN ( '
				.		'SELECT '
				.			'page_id '
				.		'FROM '
				.			$this->db->prefix().'page_versions '
				.		'WHERE '
				.			'parent = ? '
				.		'AND '
				.			'is_system_page = 0 '
				.	') as PPV ON ('
				.		'MPV.page_id = PPV.page_id '
				.	') '
				.') as MAXPV ON ( '
				.	'PV.version_number = MAXPV.version_number '
				.'AND '
				.	'PV.page_id = MAXPV.page_id '
				.') '
				;
		
		$query = $this->db->query($sql, array((int)$fromPageID));
		if ( $query && $query->numRows() > 0 )
		{
			foreach ( $query->result() as $row )
			{
				$pageID   = $row->page_id;
				$exp      = explode('/', $row->page_path);
				$newPath  = $parentPath . '/' . end($exp);
				$this->db->update('page_paths', array('page_path' => $newPath), array('page_id' => $pageID));
				// do recursive if child page exists
				if ( $this->_getChildPageCount($pageID) > 0 )
				{
					$this->_fixStrictChildPagePath($pageID, $newPath);
				}
			}
		}
	}
	
	public function copyPage($from, $to, $alias = FALSE, $deepCopy = FALSE, $returnArray = FALSE)
	{
		// Return already that alias exists when create alias
		if ( $alias === TRUE && $this->_isAliasExists($to, $from) )
		{
			return 'already';
		}
		$fromCurrentVersion = $this->getCurrentVersion($from);
		$toCurrentVersion   = $this->getCurrentVersion($to);
		
		// get from data
		$fromPage     = $this->pageModel->getPageObject($from);
		$fields       = $this->db->fields('page_versions');
		$revertTables = array();
		
		// First insert alias page
		$Page = ActiveRecord::create('pages');
		$Page->version_number = 1;
		$pageID = $Page->insert();
		
		$revertTables[] = 'pages'; 
		
		// Insert prepare
		$PageVersions = ActiveRecord::create('page_versions');
		// Clone field data
		foreach ( $fields as $field )
		{
			if ( $field === 'page_version_id' )
			{
				continue;
			}
			$PageVersions->{$field} = ( isset($fromPage->{$field}) )
			                            ? $fromPage->{$field}
			                            : 0;
		}
		
		$seezoo   = SeezooCMS::getInstance();
		$userID   = $seezoo->getUserID();
		$datetime = db_datetime();
		
		// Override master data
		$PageVersions->page_id            = $pageID;
		$PageVersions->version_number     = 1;
		$PageVersions->parent             = $to;
		$PageVersions->is_public          = 1;
		$PageVersions->display_order      = $this->_getMaxDisplayOrder($to);
		$PageVersions->display_page_level = $this->_getDisplayPageLevel($to, $toCurrentVersion);
		$PageVersions->version_date       = $datetime;
		$PageVersions->public_datetime    = $datetime;
		$PageVersions->approved_user_id   = $userID;
		$PageVersions->created_user_id    = $userID;
		$PageVersions->version_comment    = ( $alias ) ? 'page_alias ' : 'copy of ' . $fromPage->page_title;
		$PageVersions->alias_to           = ( $alias ) ? $from : 0;
		
		// Add suffix if same page
		if ( $from == $to )
		{
			$PageVersions->page_title .= '〜コピー〜';
		}
		
		// save page version
		$inserted = $PageVersions->insert();
		if ( ! $inserted )
		{
			$this->_revertPageData($pageID, $revertTables);
			return FALSE;
		}
		else if ( $alias )
		{
			return TRUE;
		}
		
		$revertTables[] = 'page_versions';
		
		// copy process below --------------------------
		
		// craete new page path
		$toPage      = ActiveRecord::finder('page_path')->findByPageId($to);
		$toPagePath  = trail_slash($toPage->page_path);
		$currentPath = explode('/', $fromPage->page_path);
		$newPagePath = $toPagePath . end($currentPath);
		
		// get no-used page page with "_copy" suffix.
		while ( $this->isPagePathExists($newPagePath) )
		{
			$newPagePath .= '_copy';
		}
		
		$PagePath = ActiveRecord::create('page_paths');
		$PagePath->page_id   = $pageID;
		$PagePath->page_path = $newPagePath;
		
		if ( ! $PagePath->insert() )
		{
			// Revert inserted data
			$this->_revertPageData($pageID, $revertTables);
			return FALSE;
		}
		
		$revertTables[] = 'page_paths';
		
		// create page permkissions
		$PagePermission = ActiveRecord::create('page_permissions');
		$PagePermission->page_id            = $pageID;
		$PagePermission->allow_access_user  = $fromPage->allow_access_user;
		$PagePermission->allow_edit_user    = $fromPage->allow_edit_user;
		$PagePermission->allow_approve_user = $fromPage->allow_approve_user;
		
		if ( ! $PagePermission->insert() )
		{
			$this->_revertPageData($pageID, $revertTables);
			return FALSE;
		}
		
		// Duplecate area
		$this->duplicateArea($from, $pageID);
		
		// Is deep copy?
		if ( $deepCopy )
		{
			$sql =
				'SELECT '
				.	'page_id '
				.'FROM '
				.	$this->db->prefix().'page_versions '
				.'WHERE '
				.	'parent = ? '
				.'GROUP BY page_id'
				;
			$query = $this->db->query($sql, array($from));
			if ( $query && $query->numRows() > 0 )
			{
				// copy recursive
				foreach ( $query->result() as $child )
				{
					$this->copyPage($child->page_id, $pageID, FALSE, $deepCopy);
				}
			}
		}
		
		return ( $returnArray )
		         ? array('page_id' => $pageID, 'page_title' => $PageVersions->page_title)
		         : TRUE;
	}
	
	public function duplicateArea($fromPageID, $toPageID)
	{
		$Areas = ActiveRecord::finder('areas')->findAllByPageId($fromPageID);
		if ( $Areas )
		{
			foreach ( $Areas as $record )
			{
				$area = new Area($record->area_name, $record->area_id);
				$area->duplicate($toPageID);
			}
		}
	}
	
	protected function _isAliasExists($parentPageID, $aliasToPageID)
	{
		$Alias = ActiveRecord::finder('page_versions')
		           ->findByParentAndAliasTo($parentPageID, $aliasToPageID, array('page_version_id'));
		return ( $Alias ) ? TRUE : FALSE;
	}
	
	public function isPagePathExists($pagePath)
	{
		$PP = ActiveRecord::finder('page_paths')
		       ->findByPagePath($pagePath);
		
		return ( $PP ) ? TRUE : FALSE;
	}
	
	private function _revertPageData($pageID, $tables)
	{
		foreach ( $tables as $table )
		{
			$this->db->delete($table, array('page_id' => $pageID));
		}
	}
	
	public function movePageOrder($from, $to, $method = 'upper')
	{
		$fromPage = ActiveRecord::finder('page_versions')
		              ->setOrderBy('version_number', 'DESC')
		              ->findByPageId($from, array('page_version_id', 'display_order'));
		$toPage   = ActiveRecord::finder('page_versions')
		              ->setOrderBy('version_number', 'DESC')
		              ->findByPageId($to, array('page_version_id', 'display_order'));
		
		// Is page found?
		if ( ! $fromPage || $toPage ) {
			return FALSE;
		}
		
		$destOrder = $toPage->display_order;
		
		// Guard process
		// If same display order, fix order
		if ( $fromPage->display_order == $toPage->display_order )
		{
			if ( $method === 'upper' )
			{
				$destOrder = ( $destOrder - 1 > 0 ) ? --$destOrder : 0;
			}
			else
			{
				++$destOrder;
			}
		}
		
		// temporary stack
		$fromOrder = $fromPage->display_order;
		// update
		$fromPage->display_order = $destOrder;
		$toPage->display_order   = $fromOrder;
		
		return ( $fromPage->update() && $toPage->update() ) ? TRUE : FALSE;
	}
	
	public function sortDisplayOrder($parentPageID, $orders = array())
	{
		foreach ( $orders as $order )
		{
			//$update = array('parent' => $master);
			$exp = explode(':', $order);
			if ( count($exp) === 2 )
			{
				if ( ! $this->db->update('page_versions', array('display_order' => $exp[1]), array('page_id' => $exp[0])) )
				{
					return FALSE;
				}
			}
		}
		return TRUE;
	}
	
	public function getExternalLinkPage($pageID)
	{
		$sql =
				'SELECT '
				.	'PV.page_title, '
				.	'PV.external_link, '
				.	'PV.target_blank, '
				.	'PV.navigation_show, '
				.	'PP.page_path_id '
				.'FROM '
				.	$this->db->prefix().'page_versions as PV '
				.'LEFT OUTER JOIN ' . $this->db->prefix().'page_paths as PP ON ( '
				.	'PV.page_id = PP.page_id '
				.') '
				.'WHERE '
				.	'PV.page_id = ? '
				.'LIMIT 1'
				;
		$query = $this->db->query($sql, array((int)$pageID));
		return ( $query && $query->row( )) ? $query->row() : FALSE;
	}
	
	// page search
	function searchPage($pageTitle = '', $pagePath = '', $frontendOnly = FALSE)
	{
		$bind = array('%' . $pageTitle . '%', '%' . $pagePath . '%');
		$sql  = 
				'SELECT '
				.	'DISTINCT(pv.page_id), '
				.	'pv.page_title, '
				.	'pp.page_path '
				.'FROM '
				.	'page_versions as pv '
				.'RIGHT OUTER JOIN page_paths as pp ON ('
				.	'pv.page_id = pp.page_id '
				.') '
				.'WHERE 1 '
				.'AND pv.page_title LIKE ? '
				.'AND pp.page_path LIKE ? '
				.'AND pv.alias_to = 0 '
				."AND (pv.external_link = '' OR pv.external_link IS NULL ) "
				;
		if ( $frontendOnly )
		{
			$bind[] = 'dashboard/%';
			$sql   .= 'AND pp.page_path NOT LIKE ? ' ;
		}
		$sql .=	'ORDER BY pv.version_number DESC, pv.page_id ASC ';
		
		// set bind query
		$query = $this->db->query($sql, $bind);
		$ret   = array();
		
		if ( $query->numRows() > 0 )
		{
			$stack = array(); // guard distinct array
			foreach ( $query->result() as $page )
			{
				if ( ! in_array($page->page_id, $stack) )
				{
					$ret[]   = $page;
					$stack[] = $page->page_id;
				}
			}
		}
		
		return $ret;
	}
}
