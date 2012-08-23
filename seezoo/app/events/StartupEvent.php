<?php

class StartupEvent
{
	public function checkInstall()
	{
		$model = Seezoo::$Importer->model('InstallModel');
		$req = Seezoo::getRequest();
		if ( ! $model->isAlreadyInstalled() && ! preg_match('|^/install.*|', $req->getAccessPathInfo()) )
		{
			Seezoo::init(SZ_MODE_MVC, '/install');
			exit;
		}
		
		// Include system configuration
		require_once(APPPATH . 'config/seezoo.config.php');
	}
}
