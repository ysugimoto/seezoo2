<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');
/**
 * ===============================================================================
 *
 * Seezoo 拡張ビュークラス
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class EX_View extends SZ_View
{
	public function __construct()
	{
		parent::__construct();
	}
	
	
	// ---------------------------------------------------------------
	
	
	/**
	 * Handle rendering view from template
	 * 
	 * @access public
	 * @param  string $dir  : template directory
	 * @param  string $viewType  : include file handle
	 */
	public function renderTemplate($dir, $viewType = 'view')
	{
		$assigns = array();
		// extra assign loaded helpers
		$assigns['Helper'] = Seezoo::$Importer->classes('Helpers');
		$SZ = Seezoo::getInstance();
		if ( isset($SZ->lead) )
		{
			$assigns['Lead'] = $SZ->lead;
			$assigns = array_merge($assigns, $SZ->lead->getAssignData());
		}
		$assigns  = array_merge($assigns, $this->_assignedVars);
		$viewFile = ROOTPATH . 'templates/' . $dir . '/' . $viewType . $this->getExtension();
		$dirBase  = $this->driver->getDirectoryBase();
		$this->driver->setDirectoryBase(ROOTPATH . 'templates/' . $dir . '/');
		
		// do render with driver
		$buffer = $this->driver->render($viewFile, $assigns, FALSE);
		$this->driver->setDirectorybase($dirBase);
	}
	
	
	// ---------------------------------------------------------------
	
	
	/**
	 * Handle rendering view from blocks
	 * 
	 * @access public
	 * @param  string $file  : load target file
	 * @param  array  $paras : assign vars array or stdClass
	 */
	public function renderBlockView($file, $params)
	{
		$assigns = array();
		// extra assign loaded helpers
		$assigns['Helper'] = Seezoo::$Importer->classes('Helpers');
		$SZ = Seezoo::getInstance();
		if ( isset($SZ->lead) )
		{
			$assigns['Lead'] = $SZ->lead;
			$assigns = array_merge($assigns, $SZ->lead->getAssignData());
		}
		$assigns  = array_merge($assigns, $this->_assignedVars);
		$dirBase  = $this->driver->getDirectoryBase();
		$this->driver->setDirectoryBase(dirname($file) . '/');
		
		// do render with driver
		$buffer = $this->driver->render($viewFile, $assigns, FALSE);
		$this->driver->setDirectorybase($dirBase);
	}
}
