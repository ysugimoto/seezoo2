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

class PageController extends EX_Breeder
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
	
	public $pageObject;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->import->library('Session');
		$this->import->model(array('PageModel', 'InitModel', 'VersionModel', 'UserModel'));
		//$this->import->helper(array('core', 'utility'));
		
	}
	
	public function _mapping($method = 'index')
	{
		if ( $method === 'index' )
		{
			$page = $this->pageModel->detectPage(1);
			$this->_view($page);
			return;
		}
		else if ( method_exists($this, $method) )
		{
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
	
	protected function _view($page = FALSE)
	{
		if ( ! $page )
		{
			show_404();
		}
		$data = $this->lead->view($page);
		$this->view->assign($data);
		$this->view->renderTemplate($data->templateDir);
	}
	
	protected function _cmsRouting($uri, $quit = FALSE)
	{
		$page = $this->pageModel->detectPage($uri);
		if ( $page )
		{
			$this->_view($page);
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
