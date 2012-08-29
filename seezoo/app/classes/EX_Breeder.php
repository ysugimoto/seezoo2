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
		$this->pageData = new stdClass;
		$this->pageData->page_id = 1;
		$this->pageData->parent_id = 1;
		
		$this->view->assign(array('pageData' => $this->pageData));
	}
}
