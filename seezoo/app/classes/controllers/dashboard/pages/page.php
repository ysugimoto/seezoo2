<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 * 
 * Seezoo dashboard 管理トップコントローラ
 * 
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */

class PageController extends EX_Breeder
{
	public static $pageTitle = 'ページ管理';
	
	public function __construct()
	{
		parent::__construct();
		$this->import->model('PageModel');
		$seezoo       = SeezooCMS::getInstance();
		$redirectPath = $this->pageModel->getFirstChildPage($seezoo->page->page_id);
		
		$this->response->redirect($redirectPath->page_path);
	}
	
	public function index($msg = '')
	{
		$this->lead->call(array($msg));
		$this->view->render('dashboard/panel');
	}
}
