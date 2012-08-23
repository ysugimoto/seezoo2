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
	protected $tokenName          = 'sz_install_token';
	protected $requiredPHPVersion = '5.1.6';
	protected $dbError;
	
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
	}
	
	// --------------------------------------------------
	
	
	/**
	 * デフォルトメソッド
	 */
	public function index()
	{
		$ticket = $this->session->generateToken($this->tokenName);
		$data = new stdClass;
		$data->filePermissions = $this->installModel->checkFilePermissions();
		$data->hidden          = array($this->tokenName => $ticket);
		$data->siteUri         = $this->installModel->getInstallURI();
		$data->directory       = $this->installModel->getInstallDirectory();
		$data->dbError         = $this->dbError;
		
		// Validation setting
		if ( $this->request->requestMethod === 'GET' )
		{
			$this->_validation();
			$this->validation->getField('db_address')->setValue('localhost');
			$this->validation->getField('site_uri')->setValue($data->siteUri);
		}
		
		// サーバ要件チェック
		$data->isModRewriteEnable = $this->installModel->checkModRewrite();
		$data->phpVersion         = version_compare(PHP_VERSION, $this->requiredPHPVersion, '>');
		
		// PHPモジュールチェック
		$data->isXml      = function_exists('simplexml_load_string');
		$data->isGd       = extension_loaded('gd');
		$data->isMbstring = extension_loaded('mbstring');
		$data->isJson     = ( function_exists('json_encode') ) ? 1
		                      : ( class_exists('SZ_Compatible_JSON') ) ? 2
		                      : 3;
		$this->view->assign($data);
		$this->view->render('install/index');
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * インストール実行
	 */
	public function do_install_post()
	{
		if ( ! $this->session->checkToken(
		                             $this->tokenName,
		                             $this->request->post($this->tokenName))
		)
		{
			exit('チケットが不正です。');
		}
		
		$this->_validation();
		// Check validation
		if ( ! $this->validation->run() )
		{
			$this->index();
			return;
		}
		
		$posts = $this->validation->getValues();
		// Can we connect to database from input data?
		if ( ! $this->installModel->isDatabaseEnable($posts) )
		{
			$this->dbError = TRUE;
			$this->index();
			return;
		}
		
		// Create database from static SQL
		if ( TRUE !== ( $ret = $this->installModel->createSystemTable($posts)) )
		{
			var_dump($ret);
			exit;
		}
		
		$this->installModel->registInstalledAdminUser($posts);
		
		// close database force
		$this->installModel->closeInstallingDatabase();
		
		// Patch file
		$this->installModel->patchFiles($posts);
		$this->view->assign(array('siteUri' => $this->installModel->getInstallURI()));
		$this->view->render('install/complete');
	}
	
	protected function _validation()
	{
		$this->import->library('Validation');
		$this->validation->delimiter('<p class="errors">', '</p>');
		$this->validation->importRulesXML(APPPATH . 'data/validation/install.xml');
	}
	

}
