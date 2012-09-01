<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 *
 * Seezooインストールモデルクラス
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
class InstallModel extends SZ_Kennel
{
	protected $_db;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 * Check system required file/directory permission
	 * 
	 * @access public
	 * @return array
	 */
	public function checkFilePermissions()
	{
		$filePaths = $this->getSystemRequiredFiles();
		$ret = array();
		foreach ( $filePaths as $path )
		{
			$ret[$path] = ( file_exists($path) )
			                ? really_writable($path)
			                : FALSE;
		}
		return $ret;
	}
	
	
	/**
	 * Get system required files/directories
	 * 
	 * @access protected
	 * @return array
	 */
	protected function getSystemRequiredFiles()
	{
		return array(
			APPPATH . 'config/',
			APPPATH . 'config/config.php',
			APPPATH . 'config/database.php',
			ROOTPATH . 'files',
			ROOTPATH . 'files/captcha',
			ROOTPATH . 'files/data',
			ROOTPATH . 'files/favicon',
			ROOTPATH . 'files/page_caches',
			ROOTPATH . 'files/temporary',
			ROOTPATH . 'files/thumbnail',
			ROOTPATH . 'files/upload_tmp',
		);
	}
	
	
	/**
	 * Check mod_rewrite module enables
	 * 
	 * @access public
	 * @return int
	 */
	public function checkModRewrite()
	{
		if ( function_exists('apache_get_modules') )
		{
			$apache = apache_get_modules();
			return (int)in_array('mod_rewrite', $apache);
		}
		return 2;
	}
	
	
	/**
	 * Get install target URI
	 * 
	 * @access public
	 * @return string
	 */
	public function getInstallURI()
	{
		$request = Seezoo::getRequest();
		// Try to get HostName from HTTP_HOST.
		// If HTTP_HOST don't have value or set, get from SERVER_NAME.
		$serverName = ( $request->server('http_host') )
		                 ? $request->server('http_host') 
		                 : $request->server('server_name');
		$serverPort = $request->server('server_port');
		
		// Does $server_name has post information?
		if ( ($point = strpos($serverName, ':')) !== -1 )
		{
			$sever_name = substr($serverName, 0, $point);
		}

		$dir = rtrim($this->getInstallDirectory(), '/');
		$sep = ( $serverPort == 80 ) ? '' : ':' . $serverPort;
		return 'http://' . $serverName . $sep . $dir . '/';
	}
	
	
	/**
	 * Get install target Directory
	 * 
	 * @access public
	 * @return string
	 */
	public function getInstallDirectory()
	{
		$request    = Seezoo::getRequest();
		$requestURI = ( $request->server('script_name') )
		                ? $request->server('script_name')
		                : $request->server('php_self');
		$split = explode('/', $requestURI);
		
		if ( ! is_array($split) || count($split) === 0 )
		{
			return $requestURI;
		}
		
		do
		{
			$found = preg_match(
						'/' . preg_quote(DISPATCHER, '/') . '/',
						array_pop($split)
					);
		}
		while ( ! $found && count($split) > 0 );
		
		return implode('/', $split) . '/';
	}
	
	
	/**
	 * Check inputed DB data is able to connect
	 * 
	 * @access public
	 * @param array $dat
	 * @return bool
	 */
	public function isDatabaseEnable($dat)
	{
		// Sorry, mysql only...
		$dsn = sprintf('mysql:host=%s;dbname=%s;por=%s', $dat['db_address'], $dat['db_name'], $dat['db_port']);
		// try database connection
		try
		{
			$this->_db = new PDO($dsn, $dat['db_username'], $dat['db_password'] );
			$result   = TRUE;
		}
		catch ( PDOException $e )
		{
			$result   = FALSE;
		}
		catch ( Exception $e )
		{
			throw $e;
		}
		return $result;
	}
	
	
	/**
	 * Create tables from static SQL file
	 */
	public function createSystemTable($dat)
	{
		if ( ! $this->_db )
		{
			return 'Database not connected.';
		}
		else if ( ! file_exists(APPPATH . 'data/seezoo.install.sql') )
		{
			return 'Install SQL file is not exists.';
		}
		$SQL = explode(";\n", file_get_contents(APPPATH . 'data/seezoo.install.sql'));
		foreach ( $SQL as $sql )
		{
			// remove comment
			$sql = preg_replace('/--.*/', '', $sql);
			// remove carrige return
			$sql = preg_replace('/\n/', '', $sql);
			if ( $sql === '' )
			{
				continue;
			}
			$sql = str_replace('{DBPREFIX}', $dat['db_prefix'], $sql);
			$query = $this->_db->query($sql);
			if ( ! $query )
			{
				return 'Install SQL query failed: ' . $sql;
			}
		}
		
		// Set site title
		$statement = $this->_db->prepare("UPDATE {$dat['db_prefix']}site_info set site_title = ?;");
		$statement->bindValue(1, $dat['site_name'], PDO::PARAM_STR);
		$query = $statement->execute();
		if ( ! $query )
		{
			return 'Faild to Set site_title.';
		}
		return TRUE;
	}
	
	
	/**
	 * Close database handle for opened that installing system
	 * 
	 * @access public
	 * @return void
	 */
	public function closeInstallingDatabase()
	{
		if ( $this->_db )
		{
			unset($this->_db);
		}
	}
	
	
	/**
	 * Regist system master user
	 * 
	 * @access public
	 * @param array $dat
	 * @return void
	 */
	public function registInstalledAdminUser($dat)
	{
		if ( ! $this->_db )
		{
			return 'Database not connected.';
		}
		$encrypted = $this->dashboardModel->encryptPassword($dat['admin_password']);
		$sql =
			"INSERT INTO {$dat['db_prefix']}users "
			. "(user_name, password, hash, email, admin_flag, regist_time, is_admin_user) "
			. "VALUES (?, ?, ?, ?, ?, ?, ?)";
		$index     = 0;
		
		$statement = $this->_db->prepare($sql);
		$statement->bindValue(++$index, $dat['admin_username'], PDO::PARAM_STR);
		$statement->bindValue(++$index, $encrypted['password'], PDO::PARAM_STR);
		$statement->bindValue(++$index, $encrypted['hash'],     PDO::PARAM_STR);
		$statement->bindValue(++$index, $dat['admin_email'],    PDO::PARAM_STR);
		$statement->bindValue(++$index, 1,                      PDO::PARAM_INT);
		$statement->bindValue(++$index, date('Y-m-d H:i:s'),    PDO::PARAM_STR);
		$statement->bindValue(++$index, 1,                      PDO::PARAM_INT);
		
		return $statement->execute();
	}
	
	
	/**
	 * Patch files
	 * 
	 * @access public
	 * @param array dat
	 * @return void
	 */
	public function patchFiles($dat)
	{
		foreach ( $this->getSystemRequiredFiles() as $file )
		{
			if ( is_dir($file) || ! file_exists($file) )
			{
				continue;
			}
			$writeContent = $this->_patchDryRun($file, $dat);
			if ( $writeContent === FALSE )
			{
				throw new RuntimeException('Faild patch configuration file: ' . $file);
			}
			
			$fp = fopen($file, 'wb');
			flock($fp, LOCK_EX);
			fwrite($fp, $writeContent);
			flock($fp, LOCK_UN);
			fclose($fp);
			
			$writeContent = NULL;
		}
	}
	
	
	/**
	 * Execute patch
	 * 
	 * @access protected
	 * @param string file
	 * @param array $dat
	 * @return mixed
	 */
	protected function _patchDryRun($file, $dat)
	{
		$fileName = basename($file);
		$buffer   = file_get_contents($file);
		
		if ( $fileName === 'config.php' )
		{
			$buffer = preg_replace(
				'/(\$config\[\'base_url\'\]\s*=\s*[\'"])[a-z\/\.-_:]*([\'"];)/',
				"\${1}{$dat['site_uri']}\${2}",
				$buffer
			);
			$buffer = preg_replace(
				'/(\$config\[\'session_store_type\'\]\s*=\s*[\'"])[a-z\/\.-_:]*([\'"];)/',
				"\${1}database\${2}",
				$buffer
			);
			$buffer = preg_replace(
				'/(\$config\[\'base_url\'\]\s*=\s*[\'"])[a-z\/\.-_:]*([\'"];)/',
				"\${1}{$dat['site_uri']}\${2}",
				$buffer
			);
			$buffer .= "\n" . '$config[\'seezoo_installed\'] = TRUE;';
			$buffer .= "\n" . '$config[\'seezoo_current_version\'] = \'' . SEEZOO_VERSION . '\';';
		}
		else if ( $fileName === 'database.php' )
		{
			$buffer = preg_replace(
				'/(\$database\[\'default\'\]\[\'host\'\]\s*=\s*[\'"])[a-z\/\.-_:]*([\'"];)/',
				"\${1}{$dat['db_address']}\${2}",
				$buffer
			);
			$buffer = preg_replace(
				'/(\$database\[\'default\'\]\[\'port\'\]\s*=\s*[\'"])[a-z\/\.-_:]*([\'"];)/',
				"\${1}{$dat['db_port']}\${2}",
				$buffer
			);
			$buffer = preg_replace(
				'/(\$database\[\'default\'\]\[\'username\'\]\s*=\s*[\'"])[a-z\/\.-_:]*([\'"];)/',
				"\${1}{$dat['db_username']}\${2}",
				$buffer
			);
			$buffer = preg_replace(
				'/(\$database\[\'default\'\]\[\'password\'\]\s*=\s*[\'"])[a-z\/\.-_:]*([\'"];)/',
				"\${1}{$dat['db_password']}\${2}",
				$buffer
			);
			$buffer = preg_replace(
				'/(\$database\[\'default\'\]\[\'dbname\'\]\s*=\s*[\'"])[a-z\/\.-_:]*([\'"];)/',
				"\${1}{$dat['db_name']}\${2}",
				$buffer
			);
			$buffer = preg_replace(
				'/(\$database\[\'default\'\]\[\'table_prefix\'\]\s*=\s*[\'"])[a-z\/\.-_:]*([\'"];)/',
				"\${1}{$dat['db_prefix']}\${2}",
				$buffer
			);
			$buffer = preg_replace(
				'/(\$database\[\'default\'\]\[\'query_debug\'\]\s*=\s*)[a-zA-Z\/\.-_:]*(;)/',
				"\${1}TRUE\${2}",
				$buffer
			);
		}
		else
		{
			$buffer = FALSE;
		}
		return $buffer;
	}
}
