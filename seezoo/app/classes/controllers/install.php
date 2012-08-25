<?php

/**
 * ===============================================================================
 *
 * Seezooインストールコントローラ
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class InstallController extends SZ_Breeder
{
	public function __construct()
	{
		parent::__construct();
		$this->import->model('InstallModel');
		$this->import->library('Session');
		$this->import->helper(array('install', 'form'));
		
		if ( $this->installModel->isAlreadyInstalled() )
		{
			exit('インストールは完了しています。');
		}
		
		$siteUri = $this->installModel->getInstallURI();
		$this->view->assign(array('siteUri' => $siteUri));
	}
	
	// --------------------------------------------------
	
	
	/**
	 * デフォルトメソッド
	 */
	public function index()
	{
		$initSet = ( $this->request->requestMethod === 'GET' ) ? TRUE : FALSE;
		$this->lead->call(array($initSet));
		$this->view->render('install/index');
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * インストール実行
	 */
	public function do_install_post()
	{
		if ( $this->lead->call() !== TRUE )
		{
			$this->view->render('install/index');
			return;
		}
		$this->view->render('install/complete');
	}
	
}
