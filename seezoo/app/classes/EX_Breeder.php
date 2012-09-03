<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 * 
 * 拡張コントローラクラス
 * 
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */
class EX_Breeder extends SZ_Breeder
{
	public $pageData;
	
	public function __construct($cmsExec = TRUE)
	{
		parent::__construct();
		if ( $cmsExec )
		{
			$this->_startUpCMS();
		}
	}
	
	protected function _startUpCMS()
	{
		$path = trim($this->request->getAccessPathInfo(), '/');
		// if " dashboard" access, check logged in
		if ( strpos($path, 'dashboard') === 0 )
		{
			$Init = Seezoo::$Importer->model('InitModel');
			if ( ! $Init->isLoggedIn() )
			{
				$this->response->redirect(SEEZOO_SYSTEM_LOGIN_URI . '?redirect=' . base64_encode($path));
			}
		}
		$PageModel = Seezoo::$Importer->model('PageModel');
		$page      = $PageModel->detectPage($path);
		if ( $page )
		{
			$pageData = $PageModel->getPageObject($page->page_id);
			SeezooCMS::setStatus('page', $pageData);
		}
		
		$this->view->assign(array('seezoo' => SeezooCMS::getInstance()));
	}
	
	protected function _ajaxTokenCheck($token = FALSE)
	{
		if ( ! $token )
		{
			$token = $this->request->post('sz_token');
		}
		
		$sess = Seezoo::$Importer->library('Session');
		if ( ! $token || $token !== $sess->get('sz_token') )
		{
			exit('access_denied');
		}
	}
}
