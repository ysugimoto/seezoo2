<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class InstallLead extends SZ_Lead
{
	protected $tokenName          = 'sz_install_token';
	protected $requiredPHPVersion = '5.1.6';
	protected $dbError;
	
	public function index($isGet = FALSE)
	{
		$Cookie = Seezoo::$Importer->helper('Cookie');
		$ticket = sha1(uniqid(mt_rand(), TRUE));
		$Cookie->set($this->tokenName, $ticket);
		$data = new stdClass;
		$data->filePermissions = $this->installModel->checkFilePermissions();
		$data->hidden          = array($this->tokenName => $ticket);
		$data->siteUri         = $this->installModel->getInstallURI();
		$data->directory       = $this->installModel->getInstallDirectory();
		$data->dbError         = $this->dbError;
		
		// Validation setting
		if ( $isGet )
		{
			$Validation = $this->_validation();
			$Validation->getField('db_address')->setValue('localhost');
			$Validation->getField('site_uri')->setValue($data->siteUri);
		}
		
		// サーバ要件チェック
		$data->isModRewriteEnable = $this->installModel->checkModRewrite();
		$data->phpVersion         = version_compare(PHP_VERSION, $this->requiredPHPVersion, '>');
		
		// PHPモジュールチェック
		$data->isXml      = function_exists('simplexml_load_string');
		$data->isGd       = extension_loaded('gd');
		$data->isMbstring = extension_loaded('mbstring');
		$data->isJson     = ( extension_loaded('json_encode') ) ? 1     // bundled function
		                      : ( function_exists('json_encode') ) ? 2  // seezoo supported function
		                      : 3;
		return $data;
	}

	public function do_install_post()
	{
		$request = Seezoo::getRequest();
		$Cookie  = Seezoo::$Importer->helper('Cookie');
		$ticket  = $request->post($this->tokenName);
		if ( ! $ticket || $ticket !== $Cookie->get($this->tokenName) )
		{
			exit('チケットが不正です。');
		}
		
		$Validation = $this->_validation();
		// Check validation
		if ( ! $Validation->run() )
		{
			return $this->index();
		}
		
		$posts = $Validation->getValues();
		// Can we connect to database from input data?
		if ( ! $this->installModel->isDatabaseEnable($posts) )
		{
			$this->dbError = TRUE;
			return $this->index();
		}
		
		// Create database from static SQL
		if ( TRUE !== ($ret = $this->installModel->createSystemTable($posts)) )
		{
			var_dump($ret);
			exit;
		}
		
		$this->installModel->registInstalledAdminUser($posts);
		
		// close database force
		$this->installModel->closeInstallingDatabase();
		
		// Patch file
		$this->installModel->patchFiles($posts);
		//$this->view->assign(array('siteUri' => $this->installModel->getInstallURI()));
		$Cookie->delete($this->tokenName);
		return TRUE;
	}
	
	private function _validation()
	{
		$Validation = Seezoo::$Importer->library('Validation');
		$Validation->delimiter('<p class="errors">', '</p>');
		$Validation->importRulesXML(APPPATH . 'data/validation/install.xml');
		
		return $Validation;
	}

}
