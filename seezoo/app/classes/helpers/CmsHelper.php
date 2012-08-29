<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 * 
 * CMS機能統括ヘルパクラス
 * 
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */
class CmsHelper
{
	protected $_userID;
	protected $_userData;
	
	public function __construct()
	{
		$this->_userID = $this->getUserID();
		$this->_userData = $this->getUserData($this->_userID);
	}
	
	public function getUserID()
	{
		$sess = Seezoo::$Importer->library('Session');
		return (int)$sess->get('user_id');
	}
	
	public function getUserData($userID)
	{
		return ActiveRecord::finder('sz_users')
		         ->findByUserId($userID);
	}
	
	public function getMemberID()
	{
		$sess = Seezoo::$Importer->library('Session');
		return (int)$sess->get('member_id');
		
	}
	
	/**
	 * Page has permission
	 * 
	 * @param string [:]seperated string
	 * @param int $userID
	 * @return bool
	 */
	public function hasPermission($permission, $userID)
	{
		if ( ! $permission )
		{
			return FALSE;
		}
		else
		{
			$mark = ':' . $userID . ':';
			return strpos($permission, $mark) !== FALSE;
		}
	}
	
	/**
	 * Generate dashboard left menus
	 * 
	 * @return HTML string
	 */
	public function buildDashboardMenu($pageData)
	{
		$db  = Seezoo::$Importer->database();
		$sql = 
				'SELECT DISTINCT '
				.	'PP.page_path, '
				.	'PV.page_title, '
				.	'PV.page_description, '
				.	'PV.parent, '
				.	'PV.page_id, '
				.	'perms.allow_access_user '
				. 'FROM '
				.	$db->prefix().'page_versions as PV '
				. 'LEFT OUTER JOIN ' . $db->prefix().'page_permissions as perms ON ( '
				.	'PV.page_id = perms.page_id '
				.') '
				. 'RIGHT OUTER JOIN ' . $db->prefix().'page_paths as PP ON ( '
				.	'PV.page_id = PP.page_id '
				.') '
				. 'WHERE '
				.	'PP.page_path LIKE ? '
				.'AND '
				.	'PV.display_page_level = 0 '
				. 'ORDER BY '
				.	'display_order ASC'
				;

		$ch_sql =
				'SELECT '
				.	'PV.page_title, '
				.	'PV.page_description, '
				.	'PV.page_id, '
				.	'perms.allow_access_user, '
				.	'PP.page_path '
				. 'FROM '
				.	$db->prefix().'page_versions as PV '
				. 'RIGHT OUTER JOIN ' . $db->prefix().'page_paths as PP ON ( '
				.	'PV.page_id = PP.page_id '
				.') '
				. 'LEFT OUTER JOIN ' . $db->prefix().'page_permissions as perms ON ( '
				.	'PV.page_id = perms.page_id '
				.') '
				. 'WHERE '
				.	'PV.parent = ? '
				.'ORDER BY '
				.	'PV.display_order ASC'
				;
		$query       = $db->query($sql, array('dashboard/%'));
		$child_stack = array();
		$out         = array('<ul class="sideNav">');
		foreach ( $query->resultArray() as $v )
		{
			// page has child?
			$query2 = $db->query($ch_sql, array($v['page_id']));
			$arr    = array(
				'page'  => $v,
				'child' => ( $query2->numRows() > 0 ) ? $query2->resultArray() : FALSE
			);
			$child_stack[] = $arr;
		}
		// format HTML
		foreach ( $child_stack as $key => $value )
		{
			$out[] = $this->_buildDashboardMenuFormat($value['page'], $pageData);

			if ( $value['child']
			     && ( $value['page']['page_id'] == $pageData->page_id
			          || $value['page']['page_id'] == $pageData->parent_id) )
			{
				$out[] = '<ul>';
				foreach ( $value['child'] as $v )
				{
					$out[] = $this->_buildDashboardMenuFormat($v, $pageData);
					$out[] = '</li>';
				}
				$out[] = '</ul>';
			}
			$out[] = '</li>';
		}
		$out[] = '</ul>';

		return implode("\n", $out);
	}
	
	
	/**
	 * Format dashboard menu tree
	 * 
	 * @param array $page
	 * @param object $pageData
	 * @return string
	 */
	protected function _buildDashboardMenuFormat($page, $pageData)
	{
		// Are you a master user?
		if ( $this->_userData->user_id  > 1 )
		{
			// Do you have admin_permission?
			if ( $this->_userData->admin_flag == 0 )
			{
				// this page allow_access?
				if ( ! $this->hasPermission(
				                        $page['allow_access_user'],
				                        $this->_userData->user_id
				                        )
				)
				{
					return '';
				}
			}
		}

		$out[] = '<li id="dashboard_page_' . $page['page_id'] . '">'
		         .'<a href="' . page_link() . $page['page_path'] . '"';
		
		if ( $page['page_id'] == $pageData->page_id
		     || $page['page_id'] == $pageData->parent_id)
		{
			$out[] = ' class="active"';
		}
		$out[] = '>' . $page['page_title'] . '</a>';

		return implode('', $out);
	}
}
