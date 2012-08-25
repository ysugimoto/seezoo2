<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');
/**
 * ===============================================================================
 *
 * Seezoo ページリード
 *
 * CMSの内部パラメータ設定
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class PageLead extends SZ_Lead
{

	protected $pageObject;
	protected $pageStatus;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function view($pageID)
	{
		$this->pageID = $pageID;
		
		// Check permission
		$this->_checkPermission();
		
		// Set page status
		$this->_setPageStatus();
	}
	
	protected function _setPageStatus()
	{
		$status = $this->initModel->getPageStatus($this->pageID);
		if ( (int)$status->is_editting === 0 )
		{
			$this->editMode = 'NO_EDIT';
		}
	}
}
