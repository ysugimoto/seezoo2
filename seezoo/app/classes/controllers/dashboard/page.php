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

class PageController extends SZ_Breeder
{
	public static $pageTitle = '管理パネルトップ';
	
	public function __construct()
	{
		parent::__construct();
		$this->import->model(array('InitModel', 'DashboardModel', 'UserModel'));
		$this->import->library('Session');
		
		if ( ! $this->initModel->isLoggedIn() )
		{
			//Seezoo::$Response->redirect(SEEZOO_SYSTEM_LOGIN_URI);
		}
	}
	
	public function index($msg = '')
	{
		$this->lead->call(array($msg));
		$this->view->render('dashboard/panel');
	}
}
