<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');
/**
 * ===============================================================================
 * 
 * Seezoo dashboard 管理トップコントローラリード
 * 
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */
class PageLead extends SZ_Lead
{
	public function index($msg)
	{
		$seezoo = SeezooCMS::getInstance();
		$userID = $this->session->get('user_id');
		$data = new stdClass;
		$data->editPageCount   = $this->dashboardModel->getEditPageCount($userID);
		$data->site            = $seezoo->getStatus('site');
		$data->approveOrders   = $this->dashboardModel->getApproveStatuses($userID);
		$data->approveRequest  = $this->dashboardModel->getApproveRequests($userID, $this->userModel->isAdmin($userID));
		$data->userData        = ActiveRecord::finder('users')
		                         ->findByUserId(1);
		$data->defaultTemplate = ActiveRecord::finder('templates')
		                         ->findByTemplateId($data->site->default_template_id);
		if ( ! empty($msg) )
		{
			$data->msg = $this->_makeMessage($msg);
		}
		return $data;
	}
	
	/**
	 * メッセージ生成
	 * @param $msg
	 * @access protected
	 */
	protected function _makeMessage($msg)
	{
		switch($msg)
		{
			case 'approve_success':
				return 'ページの承認ステータスを変更しました。';
			case 'approve_error':
				return '承認ステータスの変更に失敗しました。';
			default:
				return '';
		}
	}
}
