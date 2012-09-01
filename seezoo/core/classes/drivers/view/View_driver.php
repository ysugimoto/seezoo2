<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ====================================================================
 * 
 * Seezoo-Framework
 * 
 * A simple MVC/action Framework on PHP 5.1.0 or newer
 * 
 * 
 * View Driver
 * 
 * @package  Seezoo-Framework
 * @category Drivers
 * @author   Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * @license  MIT Licence
 * 
 * ====================================================================
 */

abstract class SZ_View_driver
{
	/**
	 * application packages
	 * @var array
	 */
	protected $_packages;
	
	
	/**
	 * initialize buffer level
	 * @var int
	 */
	protected $_initBufLevel;
	
	
	/**
	 * rendered buffer
	 * @var string
	 */
	protected $_buffer;
	
	
	/**
	 * Temporary stacked view parameters
	 * @var array
	 */
	protected $_stackVars = array();
	
	
	/**
	 * Current loading file directory
	 */
	protected $_directoryBase;
	
	/**
	 * ===========================================-
	 * abstruct method rendering
	 * 
	 * @abstract render
	 * @param string $path
	 * @param mixed  $vars
	 * @param bool   $return
	 * ===========================================-
	 */
	abstract function render($path, $vars, $return);
	
	
	public function __construct()
	{
		$this->env = Seezoo::getENV();
		
		$this->_initBufLevel = ob_get_level();
		$this->filter        = Seezoo::$Importer->classes('Filter');
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Load a piece of view file
	 * @access public
	 * @param  string $path
	 */
	public function loadView($path)
	{
		$SZ          = Seezoo::getInstance();
		$ext         = pathinfo($path, PATHINFO_EXTENSION);
		$viewExt     = $SZ->view->getExtension();
		$path        = $this->_directoryBase . $path;
		$includeFile = ( empty($ext) ) ? $path . $viewExt : $path;
		
		if ( ! file_exists($includeFile) )
		{
			throw new Exception('Unable to load requested file: ' . $path);
		}
		
		if ( pathinfo($includeFile, PATHINFO_EXTENSION) === ltrim($viewExt, '.') )
		{
			$this->render($includeFile, $this->_stackVars, FALSE);
		}
		else
		{
			file_get_contents($includeFile);
		}
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Clean up the stacks 
	 * 
	 * @access public
	 */
	public function cleanUp()
	{
		if ( $this->_initBufLevel == ob_get_level() )
		{
			$this->_stackVars = array();
			$this->_directoryBase = NULL;
		}
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Set page flow 
	 * 
	 * @access public
	 * @param  array $flow
	 */
	public function setFlow($flow)
	{
		$this->flow->setFlow($flow);
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * add buffer
	 * 
	 * @access public
	 * @param  string $buffer
	 */
	public function addBuffer($buffer)
	{
		$this->_buffer .= $buffer;
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * get buffer
	 * 
	 * @access public
	 */
	public function getBuffer()
	{
		return $this->_buffer;
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * output buffer start
	 * 
	 * @access public
	 */
	public function bufferStart()
	{
		ob_start();
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * buffering end and get buffer
	 * 
	 * @access public
	 * @param  bool $addStack
	 */
	public function getBufferEnd($addStack = FALSE)
	{
		$buffer = ob_get_contents();
		@ob_end_clean();
		if ( $addStack === TRUE )
		{
			$this->addBuffer($buffer);
		}
		return $buffer;
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * replace output buffer
	 * 
	 * @access public
	 * @param  string $buf
	 */
	public function replaceBuffer($buf)
	{
		$this->_buffer = $buf;
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Set current loading directory
	 * 
	 * @access public
	 * @param  string $dir
	 */
	public function setDirectoryBase($dir)
	{
		$this->_directoryBase = $dir;
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Get current loading directory
	 * 
	 * @access public
	 */
	public function getDirectoryBase()
	{
		return $this->_directoryBase;
	}
}

