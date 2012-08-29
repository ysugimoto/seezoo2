<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 *
 * CMS起動プロセスハンドリングイベントクラス
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class ProcessEvent
{
	/**
	 * Request object
	 * @var SZ_Request
	 */
	protected $request;
	
	/**
	 * Environment object
	 * @var SZ_Environment
	 */
	protected $env;
	
	
	public function __construct()
	{
		$this->request = Seezoo::getRequest();
		$this->env     = Seezoo::getENV();
	}
	/**
	 * CMS is already installed?
	 * 
	 * @access public
	 */
	public function checkInstall()
	{
		$model = Seezoo::$Importer->model('InitModel');
		if ( ! $model->isAlreadyInstalled()
		     && ! preg_match('|^/install.*|', $this->request->getAccessPathInfo()) )
		{
			Seezoo::init(SZ_MODE_MVC, '/install');
			exit;
		}
		
		// Include system configuration
		require_once(APPPATH . 'config/seezoo.config.php');
		// CMS Basic utility functions
		require_once(APPPATH . 'cms/seezoo_functions.php');
	}
	
	
	/**
	 * CMS start-up
	 * 
	 * @access public
	 */
	public function startUp()
	{
		Autoloader::register(APPPATH . 'cms/classes/');
		ini_set('date.timezone', SEEZOO_TIMEZONE);
		
		// Does system request of flint execute?
		if ( $this->request->segment(1, 0) === 'flint' )
		{
			return;
		}
		
		// TODO: implement
		// $this->_checkDeniedIP();
		$this->_setOptions();
		$this->_detectIE();
		
		// Is site maintenance?
		$site = SeezooOptions::get('site_info');
		if ( $site && $site->is_maintenance > 0 )
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
		
		$this->env->setConfig('is_mobile',          $mobile->is_mobile());
		$this->env->setConfig('is_smartphone',      $mobile->is_smartphone());
		$this->env->setConfig('enable_mod_rewrite', $this->_isModRewrite());
	}
	
	
	/**
	 * Judge mod_rewrite is enable
	 * 
	 * @access protected
	 * @return bool
	 */
	protected function _isModRewrite()
	{
		$path = '';
		$modRewrite = FALSE;
		
		if ( $this->request->server('request_uri') )
		{
			$path = $this->request->server('request_uri');
		}
		
		if (strpos($path, 'index.php') === FALSE
			&& file_exists(ROOTPATH . '.htaccess') )
		{
			// if accessed uri has not index.php, works mod_rewrite!
			$modRewrite = TRUE;
		}
		return $modRewrite;
	}
	
	
	/**
	 * Detect Internet Explorer
	 * 
	 * @access protected
	 */
	protected function _detectIE()
	{
		$ie  = FALSE;
		$ie6 = FALSE;
		$ie7 = FALSE;
		$png = '.png';
		$ua  = $this->request->server('http_user_agent');
		
		if ( $ua )
		{
			$ua = strtolower($ua);
			if ( preg_match('/msie 6.0/', $ua) )
			{
				$ie  = 'ie6'; // Internet Explorer 6
				$png = '.gif';
				$ie6 = TRUE;
			}
			else if ( preg_match('/msie 7.0/', $ua) )
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
	
	
	/**
	 * Convert global variables for mobile input
	 * 
	 * @access protected
	 * @param array  $globals
	 * @param string $to
	 * @param string $from
	 * @return array
	 */
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
	
	
	/**
	 * Set CMS options
	 * 
	 * @access protected
	 */
	protected function _setOptions()
	{
		SeezooOptions::init('common');
		if ( $this->env->getConfig('seezoo_installed') === TRUE )
		{
			// set site info
			$site = ActiveRecord::finder('site_info')->find();
			SeezooOptions::set('site_info', $site);
			define('SZ_LOGGING_LEVEL', (int)$site->log_level);
			define('SZ_DEBUG_LEVEL', (int)$site->debug_level);
			define('SITE_TITLE', $site->site_title);
			
			// set OGP data
			$ogp = ActiveRecord::finder('sz_ogp_data')->find();
			SeezooOptions::set('ogp_data', $ogp);
		}
		else
		{
			define('SZ_LOGGING_LEVEL', 0);
			define('SZ_DEBUG_LEVEL',   1);
		}
		// change error reporting level
		//error_reporting(( SZ_DEBUG_LEVEL === 0 ) ? 0 : E_ALL);
	}
}
