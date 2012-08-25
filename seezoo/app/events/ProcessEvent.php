<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class ProcessEvent
{
	public function checkInstall()
	{
		$model = Seezoo::$Importer->model('InstallModel');
		$req = Seezoo::getRequest();
		if ( ! $model->isAlreadyInstalled()
		     && ! preg_match('|^/install.*|', $req->getAccessPathInfo()) )
		{
			Seezoo::init(SZ_MODE_MVC, '/install');
			exit;
		}
		
		// Include system configuration
		require_once(APPPATH . 'config/seezoo.config.php');
	}
	
	public function startUp()
	{
		$request = Seezoo::getRequest();
		if ( $request->segment(1, 0) !== 'flint' )
		{
			$this->_startUpCMS();
		}
		else
		{
			$this->_startUpFlint();
		}
	}
	
	protected function _startUpFlint()
	{
		Autoloader::register(APPPATH . 'cms/');
		ini_set('date.timezone', SEEZOO_TIMEZONE);
	}
	
	protected function _startUpCMS()
	{
		Autoloader::register(APPPATH . 'cms/');
		// TODO: implement
		// $this->_checkDeniedIP();
		$this->_setOptions();
		$this->_detectIE();
		
		// Site is maintenance?
		$site = SeezooOptions::get('site_info');
		if ( $site->is_maintenance > 0 )
		{
			exit('maintenance');
		}
		
		// Mobile input convert
		$mobile = Seezoo::$Importer->library('Mobile');
		if ( $mobile->is_mobile()
		     && strpos(SEEZOO_CONVERT_MOBILE_CARRIERS, $mobile->carrier()) !== FALSE)
		{
			$_GET    = $this->_convert_globals($_GET,    'UTF-8', SEEZOO_MOBILE_STRING_ENCODING);
			$_POST   = $this->_convert_globals($_POST,   'UTF-8', SEEZOO_MOBILE_STRING_ENCODING);
			$_COOKIE = $this->_convert_globals($_COOKIE, 'UTF-8', SEEZOO_MOBILE_STRING_ENCODING);
		}
		
		$env  = Seezoo::getENV();
		$env->setConfig('is_mobile',     $mobile->is_mobile());
		$env->setConfig('is_smartphone', $mobile->is_smartphone());
		$env->setConfig('enable_mod_rewrite', $this->_isModRewrite());
	}

	protected function _setModRewrite()
	{
		$req  = Seezoo::getRequest();
		$path = '';
		$modRewrite = FALSE;
		
		if ( $req->server('request_uri') )
		{
			$path = $req->server('request_uri');
		}
		
		if (strpos($req, 'index.php') === FALSE
			&& file_exists(FCPATH . '.htaccess') )
		{
			// if accessed uri has not index.php, works mod_rewrite!
			$modRewrite = TRUE;
		}
		return $modRewrite;
	}
	
	protected function _detectIE()
	{
		$request = Seezoo::getRequest();
		$ie  = FALSE;
		$ie6 = FALSE;
		$ie7 = FALSE;
		$png = '.png';
		$ua  = $request->server('http_user_agent');
		
		if ( $ua )
		{
			if ( preg_match('/msie 6.0/i', $ua) )
			{
				$ie  = 'ie6'; // Internet Explorer 6
				$png = '.gif';
				$ie6 = TRUE;
			}
			else if ( preg_match('/msie 7.0/i', $ua) )
			{
				$ie  = 'ie7'; // Internet Explorer 7
				$ie7 = TRUE;
			}
		}
		define('ADVANCE_UA', $ie);
		define('PNG', $png);
		define('IE6', $ie6);
		define('IE7', $ie7);
	}
	
	protected function _convert_globals($globals, $to, $from)
	{
		foreach ( $globals as $key => $value )
		{
			if ( is_array($value) )
			{
				$value = $this->_convert_globals($value, $to, $from);
			}
			else
			{
				if ( mb_check_encoding($value, $from) === TRUE )
				{
					$value = mb_convert_encoding($value, $to, $from);
				}
			}
			$globals[$key] = $value;
		}
		return $globals;
	}
	
	private function _setOptions()
	{
		SeezooOptions::init('common');
		if ( get_config('seezoo_installed') === TRUE )
		{
			$site = ActiveRecord::finder('site_info')->find();
			SeezooOptions::set('site_info', $site);
			define('SZ_LOGGING_LEVEL', (int)$site->log_level);
			define('SZ_DEBUG_LEVEL', (int)$site->debug_level);
			define('SITE_TITLE', $site->site_title);
			$ogp = ActiveRecord::finder('sz_ogp_data')->find();
			SeezooOptions::set('ogp_data', $ogp);
		}
		else
		{
			define('SZ_LOGGING_LEVEL', 0);
			define('SZ_DEBUG_LEVEL',   1);
		}
		// change error reporting level
		error_reporting(( SZ_DEBUG_LEVEL === 0 ) ? 0 : E_ALL);
	}
}
