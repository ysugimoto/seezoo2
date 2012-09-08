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
}
