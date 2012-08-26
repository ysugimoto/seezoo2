<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');
/**
 * ===============================================================================
 *
 * Seezoo ページコントローラ
 *
 * CMSルーティングと出力を行うコントローラ
 *
 * @default_controller
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */

class PageController extends SZ_Breeder
{
	public $pageID;
	public $versionNumber;
	public $isSslPage;
	public $pagePath;
	public $editMode;
	public $userID;
	public $siteData;
	public $templateID;
	public $relativeTemplatePath;
	public $isAdvanceCss;
	public $memberID;
	public $isLogin;
	public $isAdmin;
	public $isMaster;

	// defualt set properties
	public $cmsMode                    = TRUE;
	public $editMenu                   = '';
	public $isEnableEditUnlock         = FALSE;
	public $isEditTimeout              = FALSE;
	public $enableCache                = FALSE;
	public $pageIsMobile               = FALSE;
	public $additionalHeaderJavascript = array();
	public $additionalHeadeCss         = array();
	public $additionalFooterJavascript = array();
	public $additionalFooterElement    = array();
	
	
	public function __construct()
	{
		parent::__construct();
		$this->import->model(array('pageModel', 'initModel', 'permissionModel', 'versionModel'));
		//$this->import->helper(array('core', 'utility'));
		
	}
	
	public function _mapping($method = 'index')
	{
		if ( $method === 'index' )
		{
			$this->pageID = 1;
			$this->_view();
			return;
		}
		else if ( method_exists($this, $method) )
		{
			$this->pageID = $this->request->segment(3, 1);
			$this->{$method}();
			return;
		}
		
		$uriString = trim($this->request->getAccessPathInfo(), '/');
		$route     = ( defined('ROUTING_PRIORITY') ) ? ROUTING_PRIORITY : 'cms';
		
		if ( method_exists($this, '_' . $route . 'Routing') )
		{
			$this->{'_' . $route . 'Routing'}($uriString);
			return;
		}
		show_404();
	}
	
	protected function _viewStatic($path)
	{
		include($path);
	}
	
	protected function _view()
	{
		if ( ! $this->pageID )
		{
			show_404();
		}
		$this->lead->view($this->pageID);
		$this->view->renderTemplate($data->templateDir);
	}
	
	protected function _cmsRouting($uri, $quit = FALSE)
	{
		$pageID = $this->pageModel->detectPage($uri);
		if ( $pageID )
		{
			$this->pageID = $pageID;
			$this->_view();
			return;
		}
		( $quit === FALSE )
		  ? $this->_staticRouting($uri, TRUE)
		  : show_404();
	}
	
	protected function _staticRouting($uri, $quit = FALSE)
	{
		$uri = preg_replace('/\.html?$|\.php$/', '', $uri);
		// if static page exists, render that page
		if (file_exists(ROOTPATH . 'statics/' . $uri . '.html'))
		{
			$path = ROOTPATH . 'statics/' . $uri . '.html';
		}
		else if (file_exists(ROOTPATH . 'statics/' . $uri . '.php'))
		{
			$path = ROOTPATH . 'statics/' . $uri . '.php';
		}
		else
		{
			$path = FALSE;
		}
		
		if ( $path !== FALSE )
		{
			$this->_viewStatics($path);
			return;
		}
		( $quit === FALSE )
		  ? $this->_cmsRouting($uri, TRUE)
		  : show_404();
	}
	
}
