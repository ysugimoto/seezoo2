<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 * 
 * 拡張ルータクラス
 * 
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===============================================================================
 */
class EX_Router extends SZ_Router
{	
	/**
	 * Customized Routing execute
	 * 
	 * @access public
	 * @return bool
	 */
	public function routing()
	{
		$REQ      = Seezoo::getRequest();
		$segments = $REQ->uriSegments($this->_level);
		$packages = Seezoo::getPackage();//$this->env->getConfig('package');
		$isRouted = FALSE;
		
		$this->requestMethod = $REQ->requestMethod;
		$this->directory     = '';
		
		// If URI segments is empty array ( default page ),
		// set default-controller and default method array.
		if ( count($segments) === 0 )
		{
			$segments = array($this->defaultController, 'index');
		}
		
		// loop the package routing
		foreach ( $packages as $pkg )
		{
			$pkg      = PKGPATH . rtrim($pkg, '/') . '/';
			$base     = $pkg . $this->detectDir;
			$detected = $this->_detectController($segments, $base);
			
			if ( is_array($detected) )
			{
				$this->_package   = $pkg;
				$this->_class     = $detected[0];
				$this->_method    = $detected[1];
				$this->_arguments = array_slice($detected, 2);
				$isRouted         = TRUE;
				$this->bootPackage($pkg);
				break;
			}
			$this->directory = '';
		}
		
		// If package controller doesn't exists,
		// routing from extensioned or default application path.
		if ( $isRouted === FALSE )
		{
			$this->directory = '';
			$dir      = $this->detectDir;
			$detected = $this->_detectController($segments, EXTPATH . $dir);
			
			if ( is_array($detected) )
			{
				$this->_package   = EXTPATH;
				$this->_class     = $detected[0];
				$this->_method    = $detected[1];
				$this->_arguments = array_slice($detected, 2);
				$isRouted = TRUE;
			}
			else
			{
				$apppath = APPPATH;
				$detected = $this->_detectController($segments, $apppath . $dir);
				
				if ( ! is_array($detected) )
				{
					if ( count($segments) === 0 )
					{
						$this->_package   = $apppath;
						$this->_directory = '';
						$this->_class     = $this->defaultController;
						$this->_method    = 'index';
						$this->_arguments = array();
						$isRouted = TRUE;
					}
				}
				else
				{
					$this->_package   = $apppath;
					$this->_class     = $detected[0];
					$this->_method    = $detected[1];
					$this->_arguments = array_slice($detected, 2);
					$isRouted = TRUE;
				}
			}
		}
		
		if ( $isRouted === TRUE )
		{
			// Routing succeed!
			Event::fire('routed', $this);
		}
		else
		{
			$this->_package   = APPPATH;
			$this->_directory = '';
			$this->_class     = 'page';
			$this->_method    = ltrim($REQ->getAccessPathInfo(), '/');
			$this->_arguments = array();
			$isRouted = TRUE;
		}
		return $isRouted;
	}
}
