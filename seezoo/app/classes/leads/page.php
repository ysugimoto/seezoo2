<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');
/**
 * ===============================================================================
 *
 * Seezoo ページリード
 *
 * CMSの内部パラメータ設定
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class PageLead extends SZ_Lead
{
	public $cms;
	public $page;
	public $pageData;
	protected $seezoo;
	
	public function __construct()
	{
		parent::__construct();
		$this->seezoo = SeezooCMS::getInstance();
		$this->userID = $this->seezoo->getUserID();
		$this->site   = $this->seezoo->getStatus('site');
	}
	
	public function view($page)
	{
		$this->page = $page;
		// Check permission
		$this->_checkPermission();
		// Set page status
		$this->_setEditStatus();
		// get detect verision mode
		$versionMode = $this->_getVersionMode();
		$pageData    = $this->pageModel->getPageObject($page->page_id, $versionMode);
		if ( ! $pageData )
		{
			show_404();
		}
		$this->pageData =& $pageData;
		
		// Is pagedata has redirect parameters? (alias, systempage, external)
		$this->_checkRedirectPage($pageData);
		
		// When approve output, set output cahce if enabled
		$cacheEnable = ( $this->site->enable_cache > 0 ) ? TRUE : FALSE;
		SeezooCMS::setStatus('enable_cache', $cacheEnable);
		$request = Seezoo::getRequest();
		if ( $cacheEnable === TRUE
		     && $request->requestMethod === 'GET' )
		{
			$cache = $this->initModel->getDisplayCacheData($pageData);
			if ( $cache )
			{
				Seezoo::$Response->display($cache);
				exit;
			}
		}
		
		// access page is mobile only?
		$this->isMobilePage = ( $pageData->is_mobile_only > 0 && $this->userID === 0 ) ? TRUE : FALSE;
		if ( $this->isMobilePage )
		{
			if ( ! get_config('is_mobile') )
			{
				Seezoo::init(SZ_MODE_MVC, 'page/mobile_only');
				exit;
			}
		}
		
		// set Absolute template path
		$pageData->templatePath         = trail_slash(base_link(SZ_TEMPLATE_DIR . $pageData->template_handle));
		$pageData->relativeTemplatePath = trail_slash(SZ_TEMPLATE_DIR . $pageData->template_handle);
		
		// build output view path
		if ( SZ_OUTPUT_MODE === 'mb'
		     && file_exists($pageData->relativeTemplatePath . 'mobile/view.php') )
		{
			$templatePath = $pageData->template_handle . '/mobile';
		}
		else if ( SZ_OUTPUT_MODE === 'sp'
		          && file_exists($pageData->relativeTemplatePath . 'smartphone/view.php') )
		{
			$templatePath = $pageData->template_handle . '/smartphone';
		}
		else
		{
			$templatePath = $pageData->template_handle;
			$this->isMobilePage = FALSE;
		}
		
		SeezooCMS::setStatus('page', $pageData);
		
		$data                = new stdClass;
		$data->page          = $pageData;
		$data->templateDir   = $templatePath;
		$data->template_path = $pageData->templatePath; // compatible
		$data->templatePath  = $pageData->templatePath; 
		return $data;
	}
	
	protected function _checkRedirectPage($pageData)
	{
		$path = FALSE;
		if ( (int)$pageData->alias_to > 0 )
		{
			$path = $this->pageModel->getPagePathByPageID($pageData->alias_to);
		}
		else if ( (int)$pageData->is_system_page > 0 )
		{
			$path = $this->pageModel->getPagePathByPageID($pageData->page_id);
		}
		else if ( ! empty($pageData->external_link) )
		{
			$path = $pageData->external_link;
		}
		
		if ( $path !== FALSE )
		{
			Seezoo::$Response->redirect($path);
		}
	}
	
	
	protected function _setEditStatus()
	{
		if ( (int)$this->page->is_editting === 0 )
		{
			$this->editMode = 'NO_EDIT';
		}
		else if ( $this->page->edit_user_id == $this->seezoo->getUserID() )
		{
			$this->editMode = 'EDIT_SELF';
		}
		else
		{
			// 編集途中で1時間以上経過した場合、他の編集権限を持つユーザーは
			// 編集ロックを開放することができるようにする
			if ( strtotime('-1 hour') >= strtotime($this->page->edit_start_time) )
			{
				$this->isEditTimeout = sha1(microtime());
				$this->session->set('sz_unlock_token', $this->isEditTimeout);
			}
			else if ( $this->seezoo->getUserID() === 1 )
			{
				$this->isEnableEditUnclock = TRUE;
			}
			$this->edtMode = 'EDIT_OTHER';
		}
		
		SeezooCMS::setStatus('edit_mode', $this->editMode);
	}
	
	protected function _getVersionMode()
	{
		$versionMode = 'approve';
		if ( $this->initModel->isLoggedIn() )
		{
			$versionMode = ( $this->editMode === 'EDIT_SELF' )
			                 ? 'editting'
			                 : 'current';
			return;
		}
		return $versionMode;
	}
	
	protected function _checkPermission()
	{
		// masterユーザーは常にTRUE
		if ( $this->userModel->isAdmin($this->userID) )
		{
			$this->canEdit = TRUE;
			return;
		}

		// permissionデータが見つからない場合は、一般ユーザー以外は許可とする
		if ( ! $this->page->allow_edit_user )
		{
			$this->canEdit = ( $this->initModel->isLoggedIn() ) ? TRUE : FALSE;
		}
		else
		{
			// permissionデータが見つかった場合は権限チェック
			if ( $this->seezoo->hasPagePermission($this->page->allow_access_user, $this->userID) === FALSE )
			{
				// memberとしてログインしていれば権限チェック
				if ( $this->seezoo->getMemberID() < 1
				     || strpos($permissions->allow_access_user, 'm') === FALSE )
				{
					// 権限が無ければpermission_denied
					Seezoo::init(SZ_MODE_MVC, 'page/permission_denied');
				}
			}
			$edit          = $this->seezoo->hasPagePermission($this->page->allow_edit_user, $this->userID);
			$memberEdit    = ( $this->seezoo->getMemberID() > 0 || $this->seezoo->hasPagePermission($this->page->allow_edit_user, 'm') ) ? TRUE : FALSE;
			$this->canEdit = ( $edit || $memberEdit ) ? TRUE : FALSE;
		}
		SeezooCMS::setStatus('can_edit', $this->canEdit);
	}

	public function getPageAddEditData($pageID, $isAddPage = TRUE)
	{
		$request       = Seezoo::getRequest();
		$PageModel     = Seezoo::$Importer->model('PageModel');
		$TemplateModel = Seezoo::$Importer->model('TemplateModel');
		$UserModel     = Seezoo::$Importer->model('UserModel');
		// load page create data
		$data = new stdClass;
		$data->page      = $PageModel->getPageObject($pageID);
		$data->templates = $TemplateModel->getTemplateList();
		$data->users     = $UserModel->getUserList();
		$data->add_flag  = $isAddPage;
		$data->fromPO    = ( strpos($request->server('http_referer'), 'dashboard/sitemap') !== FALSE )
		                     ? TRUE
		                     : FALSE;
		return $data;
	}
}
