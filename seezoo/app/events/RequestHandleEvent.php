<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 *
 * CMS用特殊リクエストハンドリングクラス
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class RequestHandleEvent
{
	/**
	 * Request instance
	 * @var SZ_Request
	 */
	protected $request;
	
	/**
	 * Environment instance
	 * @var SZ_Environment
	 */
	protected $env;
	
	/**
	 * Site settings
	 */
	protected $site;
	
	/**
	 * Session instance
	 * @var SZ_Session
	 */
	protected $session;
	
	/**
	 * InitModel instance
	 * @var InitModel
	 */
	protected $initModel;
	
	
	public function __construct()
	{
		$this->request   = Seezoo::getRequest();
		$this->env       = Seezoo::getENV();
		$this->initModel = Seezoo::$Importer->model('InitModel');
		
		$ut = new SeezooCMS;
		$this->site = $ut->getStatus('site');
	}
	
	public function handle()
	{
		if ( ! $this->initModel->isAlreadyInstalled() )
		{
			return;
		}
		
		// If requested from JavaScript-XMLHttpRequest,
		// set session update time enough long.
		if ( is_ajax_request() )
		{
			$this->env->setConfig('session_update_time', 7200);
		}
		$this->session = Seezoo::$Importer->library('Session'); 
		
		// Set CMS output mode
		$this->_setOutputMode();
		
		// SSL settings
		$ssl = ( $this->request->server('server_port') == '443'
		         || $this->request->server('https') == 'on' ) ? TRUE : FALSE;
		$ssl_url = ( $this->site && ! empty($this->site->ssl_base_url) )
		             ? $this->site->ssl_base_url
		             : $this->env->getConfig('base_url');
					 
		$this->env->setConfig('ssl_mode', $ssl);
		$this->env->setConfig('ssl_base_url', $ssl_url);
		
		$this->_handleRequest();
	}
	
	protected function _setOutputMode()
	{
		$mode   = 'pc';
		$mb     = ( $this->site ) ? $this->site->enable_mobile     : 0;
		$sp     = ( $this->site ) ? $this->site->enable_smartphone : 0;
		$mobile = Seezoo::$Importer->library('Mobile');
		
		$viewMode = $this->session->get('viewmode');
		if ( $sp > 0 && ($mobile->is_smartphone() || $viewMode === 'sp') )
		{
			$mode = 'sp';
		}
		else if ( $mb > 0 && ($mobile->is_mobile() && $viewMode === 'mb') )
		{
			$mode = 'mb';
		}
		
		$this->env->setConfig('final_output_mode', $mode);
		$this->env->setConfig('final_output_carrier', $mobile->carrier());
		define('SZ_OUTPUT_MODE', $mode);
	}
	
	protected function _handleRequest()
	{
		// is not logged in, stop process
		if ( ! $this->initModel->isLoggedIn() )
		{
			return;
		}
		
		// request mthod is not POST, stop process
		if ( $this->request->requestMethod !== 'POST' )
		{
			return;
		}
		
		// if no referer OR POST referer is no match, stop process
		$referer = $this->request->server('http_referer');
		if ( ! $referer
		     || ( strpos($referer, $this->env->getConfig('base_url')) === FALSE
		       && strpos($referer, $this->env->getConfig('ssl_base_url')) === FALSE) )
		{
			return;
		}
		
		// process parameter is not posted, stop process
		if ( ! $this->request->post('process') )
		{
			return;
		}
		
		// If these check has cleared, start process!
		
		$process = $this->request->post('process');
		if ( method_exists($this, $process) )
		{
			$this->_checkAjaxToken();
			$this->{$process}();
		}
	}
	
	protected function _checkAjaxToken()
	{
		$token = $this->request->post('sz_token');
		if ( ! $token || $token !== $this->session->get('sz_token') )
		{
			exit('不正なチケットが送信されました。');
		}
	}
	
	protected function block_add()
	{
		$page_id        = (int)$this->request->post('page_id');
		$collection_id  = (int)$this->request->post('cok_id');
		$block_id       = (int)$this->request->post('block_id');
		$version_number = (int)$this->request->post('version_number');
		$area           = $this->request->post('area');
		$user_id        = $this->session->get('user_id');
		
		// Is requested user edit mode?
		if ( ! $this->initModel->isEditMode($page_id, $user_id) )
		{
			return;
		}
		
		// Does post parameters enough?
		if ( ! $page_id || ! $area || ! $collection_id || ! $version_number )
		{
			exit('System Error!');
		}
		
		$Process    = Seezoo::$Importer->model('ProcessModel');
		$collection = $Process->getCollectionMaster($collection_id);
		$areaID     = $Process->getAreaID($area, $page_id, $version_number);
		
		// Does Collection had db_table and areaID exists
		if ( ! empty($collection->db_table) && $areaID )
		{
			if ( $Process->insertBlockData($areaID, $collection, $block_id) )
			{
				$Process->insertAreaData($block_id, $collection->collection_name, $areaID, $version_number);
			}
			else
			{
				$this->session->setFlash('sz_block_error', 1);
			}
		}
		$this->_redirectByPath($page_id);
	}

	protected function block_edit()
	{
		$block_id        = (int)$this->request->post('block_id');
		$slave_id        = (int)$this->request->post('slave_block_id');
		$page_id         = (int)$this->request->post('page_id');
		$collection_name = $this->request->post('colection_name');
		$user_id         = $this->session->get('user_id');
		
		if ( ! $this->initModel->isEditMode($page_id, $user_id) )
		{
			return;
		}
		
		if ( ! $block_id || ! $page_id || ! $collection_name )
		{
			exit('System Error!');
		}
		
		$Process = Seezoo::$Importer->model('ProcessModel');
		$block   = Block::load($collection_name, $block_id, TRUE);
		$block->bid            = $block_id;
		$block->slave_block_id = $slave_id;
		
		if ( FALSE === $Process->updateBlockData($block, $page_id) )
		{
			$this->session->setFlash('sz_block_error', 1);
		}
		$this->_redirectByPath($page_id);
	}
	
	protected function page_add($update = FALSE)
	{
		$Validation = $this->_validationPageAdd();
		if ( ! $Validation->run() )
		{
			echo $Validation->getErrors();
			return;
		}
		$dashboardRequest = ( (int)$this->request->post('from_po') === 1 ) ? TRUE : FALSE;
		$pageID           = (int)$this->request->post('page_id');
		$versionNumber    = (int)$this->request->post('version_number');
		$userID           = $this->session->get('user_id');
		
		$status = ActiveRecord::finder('pages')->findByPageId($pageID);
		if ( $dashboardRequest && $status->is_editting )
		{
			exit('editting');
		}
		
		// control page
		$page = $this->_controllPage($update, $dashboardRequest, $pageID, $versionNumber, $userID);
		
		if ( $page )
		{
			// control page path
			$pagePath   = $Validation->value('page_path');
			$this->_controllPagePath($update, $page->page_id, $pagePath);
			
			// control page permission
			$this->_controllPagePermission($update, $page->page_id);
			
			if ( $dashboardRequest )
			{
				$json = array(
					'page_title' => $page->poge_title,
					'page_id'    => $pageID
				);
				Seezoo::$Response->displayJSON($json);
			}
			else
			{
				$this->_redirectByPath($pageID);
			}
		}
		else
		{
			$msg = ( $update ) ? '編集' : '追加';
			exit('ページ' . $msg . 'に失敗しました。');
		}
	}
	
	protected function _controlPagePermission($update, $pageID)
	{
		// when from_po data is posted, requested by page_list dashboard.
		if ( $update )
		{
			$Permission = ActiveRecord::finder('page_permissions')->findByPageId($pageID);
		}
		else
		{
			$Permission = ActiveRecord::create('page_permissions');
		}
		$permissionList = array(
			'permission'         => 'allow_access_user',
			'permission_edit'    => 'allow_edit_user',
			'permission_approve' => 'allow_approve_user'
		);
		foreach ( $permissionList as $key => $value )
		{
			$perm = $this->request->post($key);
			$data = ( is_array($perm) ) ? sprintf(':%s:', implode(':', $perm)) : '';
			$Permission->{$value} = $data;
		}
		
		return ( $update )
		         ? $Permission->update()
		         : $Permission->insert();
	}
	
	protected function _controllPagePath($update, $pageID, $pagePath)
	{
		if ( $update )
		{
			// Don't update when page is system page
			if ( ! $this->request->post('is_system_page_path') )
			{
				$AR = ActiveRecord::finder('page_paths')
				      ->findByPageId($pageID);
				$AR->page_path = $pageID;
				return $AR->update();
			}
		}
		else
		{
			$AR = ActiveRecord::create('page_paths');
			$AR->page_id = $pageID;
			$AR->page_path = $pagePath;
			return $AR->insert();
		}
	}

	protected function _controllPage($update, $dashboardRequest, $pageID, $versionNumber, $userID)
	{
		if ( ! $update )
		{
			$Sitemap = Seezoo::$Importer->model('SitemapModel');
			$Page = ActiveRecord::create('page_versions');
			$Page->page_title         = $Validation->value('page_title');
			$Page->meta_title         = $Validation->value('meta_title');
			$Page->meta_keyword       = $Validation->value('meta_keyword');
			$Page->meta_description   = $Validation->value('meta_description');
			$Page->template_id        = (int)$this->request->post('template_id');
			$Page->navigation_show    = (int)$this->request->post('navigation_show');
			$Page->is_ssl_page        = (int)$this->request->post('is_ssl_page');
			$Page->is_mobile_only     = (int)$this->request->post('is_mobile_only');
			$Page->target_blank       = (int)$this->request->post('target_blank');
			$Page->parent             = $pageID;
			$Page->display_order      = $Sitemap->getMaxDisplayOrder($pageID) + 1;
			$Page->display_page_level = $Sitemap->getParentPageLevel($pageID) + 1;
			$Page->version_date       = date('Y-m-d H:i:s');
			$Page->created_user_id    = $userID;
			$Page->is_public          = 1;
			$Page->approved_user_id   = $userID;
			$Page->version_comment    = '初稿';
			$Page->public_datetime    = sprintf(
			                              '%s %s:%s:00',
			                              $Validation->value('public_ymd'),
			                              $Validation->value('public_time'),
			                              $Validation->value('public_minute')
			                            );
			
			$P = ActiveRecord::create('pages');
			$P->version_number = 1;
			$newPageID = $P->insert();
			
			$Page->page_id = $newPageID;
			$result = $Page->insert();
			return ( $result ) ? $Page : FALSE;
		}
		else
		{
			$destTable = ( $dashboardRequest ) ? 'page_versions' : 'pending_pages';
			$Page = ActiveRecord::finder($destTable)
			        ->findByPageIdAndVersionNumber($pageID, $versionNumber);
			$Page->page_title       = $Validation->value('page_title');
			$Page->meta_title       = $Validation->value('meta_title');
			$Page->meta_keyword     = $Validation->value('meta_keyword');
			$Page->meta_description = $Validation->value('meta_description');
			$Page->template_id      = (int)$this->request->post('template_id');
			$Page->navigation_show  = (int)$this->request->post('navigation_show');
			$Page->is_ssl_page      = (int)$this->request->post('is_ssl_page');
			$Page->is_mobile_only   = (int)$this->request->post('is_mobile_only');
			$Page->target_blank     = (int)$this->request->post('target_blank');
			
			$result = $Page->update();
			return ( $result ) ? $Page : FALSE;
		}
	}
	
	protected function page_edit()
	{
		$this->page_add(TRUE);
	}
	
	protected function external_page_add($update = FALSE)
	{
		if ( $this->request->post('from_po') != 1 )
		{
			exit('System error!');
		}
		
		$pageID    = $this->request->post('page_id');
		$PageModel = Seezoo::$Importer->model('PageModel');
		$status    = $PageModel->getPageStatus($pageID);
		
		if ( $status->is_editting > 0 )
		{
			exit('editting');
		}

		// is update?
		if ( $update === TRUE )
		{
			// when update, create pageversion.
			$PV = ActiveRecord::finder('page_versions')
			       ->findByPageIdAndVersionNumber($pageID, 1);
			$PV->page_title      = $this->request->post('page_title');
			$PV->external_link   = $this->request->post('external_link');
			$PV->navigation_show = ( $this->request->post('navigation_show') ) ? 1 : 0;
			$PV->target_blank    = ( $this->request->post('target_blank') ) ? 1 : 0;
			$PV->is_public       = 1;
			$result = $PV->update();
		}
		else
		{
			// else, simply create page and set version 1.
			$SitemapModel = Seezoo::$Importer->model('SitemapModel');
			$seezoo       = SeezooCMS::getInstance();
			
			$Page      = ActiveRecord::create('pages');
			$Page->version_number = 1;
			$newPageID = $Page->insert();
			
			$PV = ActiveRecord::create('page_versions');
			$PV->page_id          = $newPageID;
			$PV->page_title       = $this->request->post('page_title');
			$PV->external_link    = $this->request->post('external_link');
			$PV->navigation_show  = ( $this->request->post('navigation_show') ) ? 1 : 0;
			$PV->target_blank     = ( $this->request->post('target_blank') ) ? 1 : 0;
			$PV->is_public        = 1;
			$PV->parent           = $pageID;
			$PV->display_order    = $SitemapModel->getMaxDisplayOrder($pageID);
			$PV->version_date     = db_datetime();
			$PV->created_user_id  = $seezoo->getUserID();
			$PV->approved_user_id = $seezoo->getUserID();
			$PV->version_comment  = '初稿';
			
			$result = $PV->insert();
		}
		
		if ( $result )
		{
			// if create or update page is succeed, try update or insert page path
			if ( ! empty($PV->external_link) )
			{
				if ( $update )
				{
					$PP = ActiveRecord::finder('page_paths')
					       ->findByPagePathId($this->request->post('page_path_id'));
					$PP->page_path = $PV->external_link;
					$PP->update();
				}
				else
				{
					$PP = ActiveRecord::create('page_paths');
					$PP->page_path = $PV->external_link;
					$PP->page_id   = $result;
					$PP->insert();
				}
			}
			// if requested by dashboard, return JSON object string.
			// requested by ajax page_operator
			$response = array('page_title' => $PV->page_title, 'page_id' => $pageID);
			Seezoo::$Response->displayJSON($response);
		}
		// update or create page missed...
		else
		{
			$msg = ( $update === TRUE )
			         ? 'ページ編集に失敗しました。'
			         : 'ページ追加に失敗しました。';
			exit($msg);
		}
	}
	
	protected function external_page_edit()
	{
		$this->external_page_add(TRUE);
	}








	protected function _redirectByPath($page_id)
	{
		$Page     = Seezoo::$Importer->model('PageModel');
		$pageData = $Page->getPagePath($page_id);
		Seezoo::$Response->redirect(page_link() . $pageData->page_path);
	}
}
