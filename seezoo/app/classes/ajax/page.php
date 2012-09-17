<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');
/**
 * ===============================================================================
 * 
 * seezoo-cms デフォルトAjaxリクエストコントローラ
 *
 * @package seezoo-cms core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class PageController extends EX_Breeder
{
	public function __construct()
	{
		parent::__construct();
		$this->import->helper('Form');
	}
	
	public function check_page_path_exists($token = FALSE)
	{
		$this->_ajaxTokenCheck($token);
		
		$pagePath  = rawurldecode(trim($this->request->post('path'), '/'));
		$pageID    = (int)$this->request->post('page_id');
		$PageModel = Seezoo::$Importer->model('PageModel');
		
		switch ( $PageModel->checkPagePathExists($pageID, $pagePath) )
		{
			case 'PAGE_EXISTS':
					echo '<p style="color:#c00">ページは既に存在します。</p>';
					break;
			case 'STATIC_EXISTS':
					echo '<p style="color:#c00">静的ページが存在します。</p>';
					break;
			default:
					echo '<p>ページパスは使用可能です。</p>';
					break;
		}
	}
	
	/**
	 * ページ選択API内でのページ検索実行メソッド
	 * @access public
	 * @param $token
	 */
	function search_page_sitemap($token = FALSE)
	{
		$this->_ajaxTokenCheck($token);
		
		$Sitemap   = Seezoo::$Importer->model('SitemapModel');
		$pageTitle = $this->request->post('page_title');
		$pagePath  = $this->request->post('page_path');
		$search    = $Sitemap->searchPage($pageTitle, $pagePath);
		
		$this->view->assign(array('pages' => $search));
		$this->view->render('ajax/search_page_result');
	}
	
	/**
	 * 新規ページ作成フォーム出力
	 * @access public
	 * @param $pageID
	 * @param $token
	 */
	function add_page($pageID, $token = FALSE)
	{
		$this->_ajaxTokenCheck($token);
		if ( ! $pageID || ! ctype_digit($pageID) )
		{
			echo 'access denied';
		}

		$PageModel     = Seezoo::$Importer->model('PageModel');
		$TemplateModel = Seezoo::$Importer->model('TemplateModel');
		$UserModel     = Seezoo::$Importer->model('UserModel');
		// load page create data
		$data = new stdClass;
		$data->page      = $PageModel->getPageObject($pageID);
		$data->templates = $TemplateModel->getTemplateList();
		$data->users     = $UserModel->getUserList();
		$data->token     = $token;
		
		/*
		$data->page_id = intval($pid);
		$data->token = $token;
		$data->default_template_id = $this->ajax_model->get_default_template_id();
		$data->templates = $this->ajax_model->get_template_list();
		$data->parent_path = $this->ajax_model->get_current_page_path($pid);
		$data->permission_list = $this->ajax_model->get_user_list();
		*/
		$this->view->assign($data);
		$this->view->render('ajax/add_page_form');
	}
}
