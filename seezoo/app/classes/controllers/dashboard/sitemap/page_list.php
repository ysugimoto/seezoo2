<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 * 
 * seezoo dashboard 一般ページ管理コントローラ
 * 
 * @package seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */
class Page_listController extends EX_Breeder
{
	public static $pageTitle   = '一般ページ管理';
	public static $description = 'システムから生成されたページリストを表示します。';
	
	protected $dir = 'dashboard/sitemap/';
	
	public function __construct()
	{
		parent::__construct();
		$this->import->model('SitemapModel');
		$this->import->helper('form');
	}
	
	public function index()
	{
		$data        = new stdClass;
		$data->pages = $this->sitemapModel->getPageStructures();
		
		$this->view->assign($data);
		$this->view->render($this->dir . 'index');
	}
}
