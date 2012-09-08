<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 * 
 * seezoo dashboard 一般ページ管理Ajaxコントローラ
 * 
 * @package seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */
class Page_listController extends EX_Breeder
{
	protected $dir = 'dashboard/sitemap/';
	
	public function __construct()
	{
		parent::__construct();
		$this->import->model('SitemapModel');
		$this->import->library('Session');
		$this->import->helper('form');
	}
	
	public function delete($pageID, $token = FALSE)
	{
		$this->_ajaxTokenCheck($token);
		
		echo ( $this->sitemapModel->deletePage($pageID) )
		       ? 'complete'
		       : 'error';
	}
	
	public function get_child($pageID)
	{
		$data = new stdClass;
		$data->childs = $this->sitemapModel->getChildPages($pageID);
		
		$this->view->render($this->dir . 'parts/child_list', $data);
	}
	
	public function get_child_block($pageID)
	{
		$data = new stdClass;
		$data->childs = $this->sitemapModel->getChildBlock($pageID);
		
		$this->view->render($this->dir . 'parts/child_list_block', $data);
	}
	
	public function ajax_arrange_moveto_post()
	{
		$this->_ajaxTokenCheck();
		$from = (int)$this->request->post('from');
		$to   = (int)$this->request->post('to');
		
		echo ( $this->sitemapModel->movePage($from, $to) )
		       ? 'complete'
		       :'error';
	}
	
	public function ajax_arrange_aliasto()
	{
		$this->_ajaxTokenCheck();
		$from   = (int)$this->request->post('from');
		$to     = (int)$this->request->post('to');
		$result = $this->sitemapModel->copyPage($from, $to, TRUE);
		
		if ( $result )
		{
			echo ( $result === 'already' ) ? $ret : 'complete';
		}
		else
		{
			echo 'error';
		}
	}
	
	public function ajax_arrange_copyto()
	{
		$this->_ajaxTokenCheck();
		$from      = (int)$this->request->post('from');
		$to        = (int)$this->request->post('to');
		$recursive = ( $this->request->post('recursive') > 0 ) ? TRUE : FALSE; 
		
		echo ( $this->sitemapModel->copyPage($from, $to, FALSE, $recursive) )
		       ? 'complete'
		       : 'error';
	}
	
	public function ajax_arrange_copyto_same_level()
	{
		$this->_ajaxTokenCheck();
		$from      = (int)$this->request->post('from');
		$to        = (int)$this->request->post('to');
		$recursive = ( $this->request->post('recursive') > 0 ) ? TRUE : FALSE;
		$result    = $this->sitemapModel->copyPage($from, $to, FALSE, $recursive, TRUE);
		
		echo ( $result && is_array($result) )
		       ? json_encode($result)
		       : 'error';
	}
	
	public function move_page_one($token = FALSE)
	{
		$this->_ajaxTokenCheck($token);
		$from   = (int)$this->request->post('from');
		$to     = (int)$this->request->post('to');
		$method = $this->request->post('method');
		
		echo ( $this->sitemapModel->movePageOrder($from, $to, $method) )
		       ? 'complete'
		       : 'error';
	}
	
	public function refresh($token = FALSE)
	{
		$this->_ajaxTokenCheck($token);
		$data          = new stdClass;
		$data->pages   = $this->sitemapModel->getPageStructures();
		$data->is_open = explode('|', $this->request->post('open'));
		
		$this->view->render($this->dir . 'parts/page_structure', $dsta);
	}
	
	public function sort_display_order($token = FALSE)
	{
		$this->_ajaxTokenCheck($token);
		$parent = $this->request->post('master');
		$orders = $this->request->post('order');
		
		echo ( $this->sitemapModel->sortDisplayOrder($parent, $orders) )
		       ? 'complete'
		       : 'error';
	}
	
	
	/**
	 * 外部リンク作成
	 * @access public
	 * @param $pageID
	 * @param $token
	 */
	function add_external_link($pageID, $token = FALSE)
	{
		$this->_ajaxTokenCheck($token);
		if ( ! $pageID || ! ctype_digit($pageID) )
		{
			echo 'access denied';
		}
		
		$TemplateModel = Seezoo::$Importer->model('TemplateModel');
		$PageModel     = Seezoo::$Importer->model('PageModel');
		$UserModel     = Seezoo::$Importer->model('UserModel');
		
		// load page create data
		$data                  = new stdClass;
		$data->page_id         = (int)$pageID;
		$data->token           = $token;
		$data->templates       = $TemplateModel->getTemplateList();
		$data->parent_path     = $PageModel->getPagePathByPageID($pageID);
		$data->permission_list = $UserModel->getUserList();
		
		$this->view->render('ajax/add_external_link', $data);
	}
	
	/**
	 * 外部リンク編集
	 * @access public
	 * @param int $pageID
	 * @param string $token
	 */
	function external_page_config_from_operator($pid, $token)
	{
		$this->_ajaxTokenCheck($token);
		if ( ! $pageID || ! ctype_digit($pageID) )
		{
			echo 'access_denied';
		}
		
		$SitemapModel = Seezoo::$Importer->model('SitemapModel');
		
		$data          = new stdClass;
		$data->page_id = (int)$pageID;
		$data->token   = $token;
		$data->page    = $SitemapModel->getExternalLinkPage($pageID);
		if ( ! $data->page )
		{
			exit('編集対象のページが見つかりませんでした');
		}
		
		$this->view->render('ajax/edit_external_link', $data);
	}
	
	/**
	 * ページ設定
	 * @access public
	 * @param int $pageID
	 * @param string $token
	 */
	function page_config_from_operator($pageID, $token = FALSE)
	{
		$this->_ajaxTokenCheck($token);
		if ( ! $pageID || ! ctype_digit($pageID) )
		{
			echo 'access_denied';
			return;
		}
		
		$TemplateModel = Seezoo::$Importer->model('TemplateModel');
		$PageModel     = Seezoo::$Importer->model('PageModel');
		$UserModel     = Seezoo::$Importer->model('UserModel');
		$SitemapModel  = Seezoo::$Importer->model('SitemapModel');
		
		$data            = new stdClass;
		$data->token     = $token;
		$data->templates = $TemplateModel->getTemplateList();
		$data->page      = $PageModel->getPageObject($pageID, 'approve');
		$data->user_list = $UserModel->getUserList();
		$data->seezoo    = SeezooCMS::getInstance();
		
		$page            = $PageModel->getPageObject($pageID, 'approve');
		$topPagePath     = ActiveRecord::finder('page_paths')->findByPageId(1);
		$grep            = '|^' . preg_quote(trail_slash($topPagePath->page_path)) . '|';
		$page->page_path = preg_replace($grep, '', $page->page_path);
		$data->page      = $page;
		
		$this->view->assign($data);
		$this->view->render('ajax/edit_page_form');
	}
}
