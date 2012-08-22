<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ====================================================================
 * 
 * Seezoo-Framework
 * 
 * A simple MVC/action Framework on PHP 5.1.0 or newer
 * 
 * 
 * Application logger
 * 
 * @package  Seezoo-Framework
 * @category Classes
 * @author   Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * @license  MIT Licence
 * 
 * ====================================================================
 */

class SZ_Logger
{
	/**
	 * logfile save dest path
	 * @var string
	 */
	protected $_logPath;
	
	
	/**
	 * logging level
	 * @var int
	 */
	protected $_level;
	
	public function __construct()
	{
		$this->_level   = Seezoo::$config['logging_level'];
		$this->_logPath = ( Seezoo::$config['logging_save_dir'] )
		                    ? rtrim(Seezoo::$config['logging_save_dir'], '/') . '/'
		                    : '';
	}
	
	
	// ---------------------------------------------------------------
	
	
	/**
	 * write a log
	 * 
	 * @access public
	 * @param  string $msg
	 * @param  int    $level
	 */
	public function write($msg, $level = FALSE)
	{
		// Log level override?
		$level = ( $level === FALSE ) ? $this->_level : $level;
		
		if ( $level == SZ_LOG_LEVEL_DEPLOY
		     || ! is_dir($this->_logPath)
		     || ! is_writable($this->_logPath) )
		{
			return;
		}
		// log line format
		$msg .= ' --logged at ' . date('Y-m-d H:i:s');
		$logFile = $this->_logPath . 'log-' . date('Y-m-d') . '.log';
		if ( FALSE !== ($fp = fopen($logFile, 'ab')) )
		{
			flock($fp, LOCK_EX);
			@fwrite($fp, $msg . "\n");
			flock($fp, LOCK_UN);
			fclose($fp);
		}
	}
}