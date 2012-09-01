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
		$PageModel = Seezoo::$Importer->model('PageModel');
		$page      = $PageModel->detectPage($path);
		if ( $page )
		{
			$pageData = $PageModel->getPageObject($page->page_id);
			SeezooCMS::setStatus('page', $pageData);
		}
		
		$this->view->assign(array('seezoo' => SeezooCMS::getInstance()));
	}
}
