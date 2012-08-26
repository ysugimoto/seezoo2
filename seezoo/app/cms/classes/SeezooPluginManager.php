<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  =========================================================
 * seezoo plugin management class
 *
 * @package seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *  =========================================================
 */

class SeezooPluginManager
{
	private static $instance;
	private static $current_plugin = '';
	
	private $db;
	private $plugin_path;
	private $table             = 'sz_plugins';
	private $plugin_list       = array();
	private $plugin_list_all   = array();
	private $plugin_list_names = array();
	
	protected $_messages = array(
		'PLUGIN_INSTALL_SUCCESS'     => 'プラグインをインストールしました。',
		'PLUGIN_UNINSTALL_SUCCESS'   => 'プラグインをアンインストールしました。',
		'PLUGIN_NOT_FOUND'           => 'プラグインが見つかりませんでした。',
		'PLUGIN_ALREADY_INSTALLED'   => '選択したプラグインはすでにインストールされています。',
		'PLUGIN_INSTALL_FAILED'      => 'プラグインのインストールに失敗しました。',
		'PAGE_INSTALL_FAILED'        => 'プラグインページのインストールに失敗しました。',
		'BLOCK_INSTALL_FAILED'       => 'プラグインブロックのインストールに失敗しました。',
		'PLUGIN_UNINSTALL_FAILED'    => 'プラグインの案インストールに失敗しました。',
		'PAGE_UNINSTALL_FAILED'      => 'プラグインページのアンインストールに失敗しました。',
		'BLOCK_UNINSTALL_FAILED'     => 'プラグインブロックのアンインストールに失敗しました。',
		'UPDATE_PAGE_DB_MISSED'      => 'プラグインページのDB更新に失敗しました。',
		'NO_BLOCK_CLASS'             => 'ブロッククラスが見つかりませんでした。',
		'NO_BLOCK_TABLE_NAME'        => 'ブロックのDBテーブル名が定義されていません。',
		'NO_DB_STRUCTURE_METHOD'     => 'ブロックのDB構造メソッドが定義されていません。',
		'INVALID_BLOCK_DB_STRUCTURE' => 'ブロックのDB構造に問題があります。',
		'NOT_ENOUGH_BLOCK_COLUMN'    => 'ブロックのDB構造に問題があります。',
		'BLOCK_FILESET_NOT_ENOUGH'   => 'ブロックのファイル構造がインストール要件を満たしていないものがあります。'
	);
	
	
	/**
	 * Singleton pattern get_instance()
	 */
	public static function get_instance()
	{
		if ( ! self::$instance instanceof SeezooPluginManager )
		{
			self::$instance = new SeezooPluginManager();
		}
		return self::$instance;
	}
	
	public static function set_current($handle)
	{
		self::$current_plugin = trim($handle, '/');
	}
	
	public static function get_current()
	{
		return self::$current_plugin;
	}
	
	public function get_current_plugin()
	{
		return self::$current_plugin;
	}
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		require_once BASEPATH.'database/DB.php';
		$this->db =& DB();
		$this->plugin_path = SZ_PLG_PATH;
		
		$this->_detect_plugin();
	}
	
	/**
	 * Get status message
	 * @param string $msg
	 */
	public function get_message($msg)
	{
		return ( isset($this->_messages[$msg]) ) ? $this->_messages[$msg] : '';
	}
	
	/**
	 * startup
	 * @access private
	 */
	public static function start_up()
	{
		$instance = self::get_instance();
		$basepath = $instance->get_plugin_path();
		
		foreach ( $instance->get_plugin_list() as $plugin )
		{
			$path = "{$basepath}{$plugin->plugin_handle}/tools/init.php";
			if ( file_exists($path) )
			{
				$instance->_load_init($path);
			}
		}
	}
	
	/**
	 * installed plugin-block exists
	 * @param string $cname
	 * @param bool $force_view
	 * @return mixed $plugin_dir or FALSE
	 */
	public function block_exists($cname, $force_view = FALSE)
	{
		$sql = 
				'SELECT '
				.	'PLG.plugin_handle '
				.'FROM '
				.	$this->table . ' as PLG '
				.'JOIN collections as C '
				.	'USING(plugin_id)'
				.'WHERE '
				.	'C.collection_name = ? '
				;
		if ( ! $force_view )
		{
			$sql .= 'AND PLG.is_enabled = 1 ';
		}
		$sql .= 'LIMIT 1';
		
		$query = $this->db->query($sql, array($cname));
		if ( ! $query || ! $query->row() )
		{
			// not installed.
			return FALSE;
		}
		
		$result     = $query->row();
		$block_path = 'blocks/' . $cname . '/' . $cname . '.php';
		return ( file_exists($this->plugin_path . $result->plugin_handle . '/' . $block_path) )
		         ? /*$this->plugin_path . */$result->plugin_handle . '/'
		         : FALSE;
	}
	
	/**
	 * Get plugin list
	 * @param bool $all
	 * @return array
	 * @access public
	 */
	public function get_plugin_list($all = FALSE)
	{
		return $this->plugin_list;
	}
	
	/**
	 * Get installed plugin name list
	 * @return array
	 * @access public
	 */
	public function get_installed_plugin_names()
	{
		return $this->plugin_list_names;
	}
	
	/**
	 * Get plugin path
	 */
	public function get_plugin_path()
	{
		return $this->plugin_path;
	}
	
	/**
	 * Get plugins package list..
	 * @param string $handle
	 */
	public function get_plugin_detail($handle)
	{
		$xml_path = $this->plugin_path . rtrim($handle, '/') . '/package.xml';
		$pages    = array();
		$blocks   = array();
		$data     = new stdClass();
		$data->handle = trim($handle, '/');
		
		if ( ! file_exists($xml_path) )
		{
			return FALSE;
		}
		if ( FALSE === ($XML = @simplexml_load_file($xml_path)) )
		{
			return FALSE;
		}
		if ( isset($XML->plugin_name) )
		{
			$data->name = trim((string)$XML->plugin_name, "\n");
		}
		if ( isset($XML->description) )
		{
			$data->description = trim((string)$XML->description, "\n");
		}
		
		$data->is_installed = (bool)in_array($handle, $this->plugin_list_names);
		
		foreach ( $XML->page as $page )
		{
			$o = new stdClass();
			$o->controller  = (isset($page->path))        ? (string)$page->path : '';
			$o->page_title  = (isset($page->page_title))  ? (string)$page->page_title : '';
			$o->description = (isset($page->description)) ? (string)$page->description : '';
			$pages[] = $o;
		}
		foreach ( $XML->block as $block )
		{
			$o = new stdClass();
			$o->block_name   = (isset($block->name))        ? (string)$block->name : '';
			$o->block_handle = (isset($block->handle))      ? (string)$block->handle : '';
			$o->description  = (isset($block->description)) ? (string)$block->description : '';
			$blocks[] = $o;
		}
		
		$data->pages  = $pages;
		$data->blocks = $blocks;

		return $data;
	}
/*	
	public function get_plugin_detail($handle)
	{
		$base_path  = $this->plugin_path . rtrim($handle, '/') . '/'; 
		$pages      = $this->_detect_plugin_controllers($base_path . 'controllers/');
		$blocks     = $this->_detect_plugin_blocks($base_path . 'blocks/');
		$page_data  = array();
		$block_data = array();
		$data       = new stdClass();
		
		foreach ( $pages as $key => $page )
		{
			$exp = explode('.', $page);
			$page_data[] = $page_link() . $exp[0];
		}
		foreach ( $blocks as $block )
		{
			$block_class = ucfirst($block) . '_block';
			require_once($base_path . 'blocks/' . $block . '/' . $block.EXT);
			$b = $block_class();
			$block_data[] = $b->get_block_name();
		}
		
		$data->pages  = $page_data;
		$data->blocks = $block_data;
		
		return $data;
	}
*/	
	
	/**
	 * _detect_plugin_controllers
	 * plugin controllers detection
	 * @param string $path
	 * @param string $prefix
	 * @acess private
	 */
	private function _detect_plugin_controllers($path, $prefix = '')
	{
		$controllers = array();
		
		if ($fp = @opendir($path))
		{
			$path = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			
			while (FALSE !== ($file = readdir($fp)))
			{
				// skip dotted secret file or directory
				if (strncmp($file, '.', 1) == 0
							|| ($file == '.' || $file == '..')
				)
				{
					continue;
				}
				
				if ( is_dir($path . $file) )
				{
					$controllers = array_merge(
												$controllers,
												$this->_detect_plugin_controllers($path . $file, $file . '/')
											);
				}
				else
				{
					$controllers[] = $prefix . $file;
				}
			}
			
			closedir($fp);
		}
		return $controllers;
	}
	
	/**
	 * _detect_plugin_blocks
	 * plugin bloks detection
	 * @param string $path
	 */
	private function _detect_plugin_blocks($path)
	{
		$blocks = array();
		
		if ($fp = @opendir($path))
		{
			$path = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			
			while (FALSE !== ($file = readdir($fp)))
			{
				// skip dotted secret file or directory
				if (strncmp($file, '.', 1) == 0
							|| ($file == '.' || $file == '..')
							|| ! is_dir($path . $file)
				)
				{
					continue;
				}
				
				$base = $path . $file . '/';

				// block has need structure?
				if ( file_exists($base . 'add'.EXT)
						&& file_exists($base . 'edit'.EXT)
						&& file_exists($base . $file.EXT)
						&& file_exists($base . 'view'.EXT)
				)
				{
					$blocks[] = $file;
				}
			}
		}
		return $blocks;
	}
	
	/**
	 * get all plugin directory indexes
	 * @access public
	 * @return array
	 */
	public function plugin_index()
	{
		$dir = $this->plugin_path;
		$filedata = array();
		// create package indexes
		if ($fp = @opendir($dir))
		{
			$dir = rtrim($dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			
			while (FALSE !== ($file = readdir($fp)))
			{
				// skip dotted secret file or directory
				if (strncmp($file, '.', 1) == 0
						|| ($file == '.'
						|| $file == '..')
						|| ! is_dir($dir . $file)
				)
				{
					continue;
				}
				
				$filedata[] = $file;
			}
			
			closedir($fp);
		}
		
		return $filedata;
	}
	
	/**
	 * initialize plugin
	 * divide method require only for create local scopes. 
	 * @param string $path
	 * @access private
	 */
	private function _load_init($path)
	{
		require($path);
	}
	
	/**
	 * plugin list detection from DB
	 * @access private
	 */
	private function _detect_plugin()
	{
		$sql =
				'SELECT '
				.	'plugin_id, '
				.	'plugin_name, '
				.	'plugin_handle, '
				.	'description, '
				.	'added_datetime '
				.'FROM '
				.	$this->table . ' '
				.'WHERE '
				.	'is_enabled = 1'
				;
		$query = $this->db->query($sql);
		if ( $query )
		{
			foreach ( $query->result() as $plugin )
			{
				$this->plugin_list[$plugin->plugin_id] = $plugin;
				$this->plugin_list_names[] = $plugin->plugin_handle;
			}
		}
	}
	
	/**
	 * Install a pljugin
	 * @param string $handle
	 * @access public
	 * @return mixed
	 */
	public function install($handle)
	{
		$detail = $this->get_plugin_detail($handle);
		
		// install history check
		$sql   = 'SELECT plugin_id FROM ' . $this->table . ' WHERE plugin_handle = ?';
		$query = $this->db->query($sql, array($handle));
		
		if ( $query->num_rows() > 0 )
		{
			// plugin update to enable
			$result    = $query->row();
			$plugin_id = (int)$result->plugin_id;
			$sql       = 'UPDATE ' . $this->table . ' SET is_enabled = 1 WHERE plugin_id = ? LIMIT 1';
			$this->db->query($sql,array($plugin_id));
			
			// fake transuction query (delete)
			$trans_sql = 'UPDATE ' . $this->table . ' SET is_enabled = 0 WHERE plugin_id = ? LIMIT 1';
		}
		else
		{
			$insert_data = array(
				'plugin_name'    => $detail->name,
				'plugin_handle'  => $handle,
				'description'    => $detail->description,
				'added_datetime' => db_datetime()
			);
			// insert plugin table
			$insert = $this->db->insert($this->table, $insert_data);
			
			if ( ! $insert )
			{
				return 'PLUGIN_INSTALL_FAILED';
			}
			// Get the installed plugin ID
			$plugin_id = (int)$this->db->insert_id();
			
			// fake transuction query (delete)
			$trans_sql = 'DELETE FROM ' . $this->table . ' WHERE plugin_id = ? LIMIT 1';
		}
		
		
		
		// loop and install page
		foreach ( $detail->pages as $page )
		{
			if ( $this->_install_page($page, $handle, $plugin_id) !== TRUE )
			{
				$this->db->query($trans_sql, array($plugin_id));
				return 'PAGE_INSTALL_FAILED';
			}
		}
		// loop and install block
		foreach ( $detail->blocks as $block )
		{
			if ( $this->_install_block($block, $handle, $plugin_id) !== TRUE )
			{
				$this->db->query($trans_sql, array($plugin_id));
				return 'BLOCK_INSTALL_FAILED';
			}
		}
		
		return TRUE;
	}
	
	public function uninstall($plugin_handle)
	{
		if ( empty($plugin_handle) )
		{
			return 'PLUGIN_NOT_FOUND';
		}
		
		// get plugin_id
		$sql =
				'SELECT '
				.	'plugin_id '
				.'FROM '
				.	'sz_plugins '
				.'WHERE '
				.	'plugin_handle = ? '
				.'LIMIT 1';
		$query = $this->db->query($sql, array($plugin_handle));
		if ( ! $query || ! $query->row() )
		{
			return 'PLUGIN_NOT_FOUND';
		}
		
		$result    = $query->row();
		$plugin_id = $result->plugin_id;
		
		// query binding
		$sql =
				'UPDATE '
				.	'%s '
				.'SET '
				.	'is_enabled = 0 '
				.'WHERE '
				.	'plugin_id = ?';
		
		// target tables and error messages
		$target_tables = array(
			'sz_plugins'       => 'PLUGIN_UNINSTALL_FAILED',
//			'page_paths'       => 'PAGE_UNINSTALL_FAILED',
			'collections'      => 'BLOCK_UNINSTALL_FAILED'
		);
		// update plugin record
		foreach ( $target_tables as $table => $error )
		{
			if ( ! $this->db->query(sprintf($sql, $table), (int)$plugin_id) )
			{
				return $error;
			}
		}
		
		// delete page_data
		$sql = 'SELECT page_path_id, page_id FROM page_paths WHERE plugin_id = ?';
		$query = $this->db->query($sql, array($plugin_id));
		
		if ( $query && $query->num_rows() > 0 )
		{
			foreach ( $query->result() as $page )
			{
				//delete page_permission
				$this->db->where('page_id', $page->page_id);
				$this->db->delete('page_permissions');
				
				// delete page_data
				$this->db->where('page_id', $page->page_id);
				$this->db->delete('page_versions');
				
				// delete page_path
				$this->db->where('page_path_id', $page->page_path_id);
				$this->db->delete('page_paths');
			}
		}
		return 'PLUGIN_UNINSTALL_SUCCESS';
	}
	
	/**
	 * install pages
	 * @param object $page
	 * @param string $plugin_handle 
	 * @param int $plugin_id
	 * @access private
	 * @return mixed
	 */
	protected function _install_page($page, $plugin_handle, $plugin_id)
	{
		$page_path = kill_traversal($page->controller);
		$dir       = $this->plugin_path . $plugin_handle . '/';
		$filepath  = $dir . 'controllers/' . $page_path . EXT;
		
		// file exists?
		if ( ! file_exists($filepath) )
		{
			return FALSE;
		}
		// TODO : 既にインストール済みの場合、レコードが重複してしまう、つまり、同じページが2つできてしまう
		// 回避するために重複チェックを行うべきだが、そもそもプラグインをアンインストールした場合のプラグイン
		// レコードの扱いについて検討が必要。
		// 暫定的に、インストール済みの場合は処理を中断させる。
		$sql   = 'SELECT page_id FROM page_paths WHERE page_path = ? AND plugin_id = ?';
		$query = $this->db->query($sql, array($page_path, $plugin_id));
		
		// page path exists?
		if ( $query && $query->num_rows() > 0 )
		{
			// update
			//$sql = 'UPDATE page_paths SET is_enabled = 1 WHERE page_path = ? AND plugin_id = ?';
			//return $this->db->query($sql, array($page_path, $plugin_id));
			return TRUE;
		}
		
		// rquire file
		require($filepath);
		
		// get descriptions
		$page_data   = $this->_get_page_descriptions($page_path);
		
		// description exists? ( class exists )
		if ( $page_data === FALSE )
		{
			return FALSE;
		}
		
		// インストール開始 -----------------------------------------------------------------------------
		// 前提として、プラグインとしてインストールされるページは全てCIコントローラページである。
		// そのため、インストールされているかどうかの判定はページパスのレコードがあるかどうかに限定される。
		// また、アンインストール→インストールを再度行う場合、ページIDは変更（インクリメントされたもの）される。
		
		// page install
		$ret = $this->db->insert('pages', array('version_number' => 1));
		if ( ! $ret )
		{
			return FALSE;
		}
		
		$page_id     = $this->db->insert_id();
		$CI          =& get_instance();
		$user_id     = (int)$CI->session->userdata('user_id');
		$schema_dir  = $dir . 'schemas/';
		
		// First, create page version
		$ret = $this->db->insert(
								'page_versions',
								array(
									'page_id'            => $page_id,
									'page_title'         => $page_data['page_title'],
									'page_description'   => $page_data['page_description'],
									'created_user_id'    => $user_id,
									'is_public'          => 1,
									'approved_user_id'   => $user_id,
									'is_system_page'     => 1,
									'parent'             => $page_data['parent'],
									'display_page_level' => $page_data['display_page_level'],
									'display_order'      => $page_data['display_order'],
									'navigation_show'    => 1
								)
							);
		if ( ! $ret )
		{
			$this->db->where('page_id', $page_id);
			$this->db->delete('pages');
			return FALSE;
		}
		
		// Second, create page path
		$ret = $this->db->insert(
								'page_paths',
								array(
									'page_id'   => $page_id,
									'page_path' => $page_path,
									'plugin_id' => $plugin_id
								)
							);
		if ( ! $ret )
		{
			$this->db->where('page_id', $page_id);
			$this->db->delete('pages');
			$this->db->where('page_id', $page_id);
			$this->db->delete('page_versions');
			return FALSE;
		}
		
		// Thrid, create page permission record
		$ret = $this->db->insert(
								'page_permissions',
								array(
									'page_id' => $page_id
								)
							);
							
		if ( ! $ret )
		{
			$this->db->where('page_id', $page_id);
			$this->db->delete('pages');
			$this->db->where('page_id', $page_id);
			$this->db->delete('page_versions');
			$this->db->where('page_id', $page_id);
			$this->db->delete('page_paths');
		}
		
		// Finally, create and insert page schema and default record id exists
		if ( file_exists($schema_dir . 'db/' . $page_path . 'xml') )
		{
			if ( ! $this->_create_page_db_schema($page_path, $schema_dir . 'db/') )
			{
				$this->db->where('page_id', $page_id);
				$this->db->delete('pages');
				$this->db->where('page_id', $page_id);
				$this->db->delete('page_versions');
				$this->db->where('page_id', $page_id);
				$this->db->delete('page_paths');
				$this->db->where('page_id', $page_id);
				$this->db->delete('page_permissions');
				
				return FALSE;
			} 
		}
		if ( file_exists($schema_dir . 'data/' . $page_path . '.xml') )
		{
			if ( ! $this->_create_initial_db_data($page_path, $schema_dir . 'data/') )
			{
				$this->db->where('page_id', $page_id);
				$this->db->delete('pages');
				$this->db->where('page_id', $page_id);
				$this->db->delete('page_versions');
				$this->db->where('page_id', $page_id);
				$this->db->delete('page_paths');
				$this->db->where('page_id', $page_id);
				$this->db->delete('page_permissions');
				
				return FALSE;
			}
		}
		
		return ( $ret ) ? TRUE : FALSE;
	}
	
	/**
	 * Make controller description
	 * @param string $page_path
	 * @access private
	 * @return mixed
	 */
	protected function _get_page_descriptions($page_path)
	{
		$CI =& get_instance();
		$CI->load->model('sitemap_model');
		
		// Is directoried page?
		$position = strrpos($page_path, '/');
		if ( $position !== FALSE )
		{
			$exp         = explode('/', $page_path);
			$classname   = end($exp);
			$parent_path = implode('/', array_pop($exp));
			$page_level  = ( $exp[0] === 'dashboard' ) ? count($exp) - 2 : count($exp) - 1;
		}
		else
		{
			$classname   = $page_path;
			$parent_path = FALSE;
			$page_level  = 1;
		}
		
		// get class name
		$class = ucfirst($classname);
		if ( ! class_exists($class) )
		{
			return FALSE;
		}
		
		$props = get_class_vars($class);
		
		// set array parameters
		$ret   = array(
			'page_title'         => ( isset($props['page_title']) && ! is_null($props['page_title']) ) ? $props['page_title'] : $classname,
			'page_description'   => ( isset($props['description']) ) ? $props['description'] : '',
			'parent'             => ( $parent_path ) ? $CI->sitemap_model->_get_parent_page_id($parent_path) : 1,
			'display_page_level' => $page_level,
			'template_id'        => $CI->sitemap_model->_get_default_template_id($page_path)
		);
		$ret['display_order']   = $CI->sitemap_model->_get_max_display_order($ret['parent']);
		
		return $ret;
	}
	
	/**
	 * page database structure create
	 * @param string $path
	 * @param string $dir
	 * @return bool
	 */
	protected function _create_page_db_schema($path, $dir)
	{
		$CI =& get_instance();
		$CI->load->dbforge();
		
		$structure = parse_db_schema($path, $dir);
		
		foreach ( $structure as $table => $field )
		{
			if ( $CI->db->table_exists($table) )
			{
				$this->_update_page_db_schema($table, $field);
				continue;
			}
			
			$indexes = array();
			
			foreach ( $field as $name => $column )
			{
				if ( isset($column['key']) )
				{
					$key_field = $name;
					unset($column['key']);
				}
				if ( isset($column['index']) )
				{
					$indexes[] = $key;
					unset($column['index']);
				}
			}
			
			$CI->dbforge->add_field($field);
			
			if ( isset($key_field) )
			{
				$CI->dbforge->add_key($key_field, TRUE);
			}
			
			if ( ! $CI->dbforge->create_table($table, TRUE) )
			{
				return FALSE;
			}
			
			$this->_merge_db_index($table, $indexes);
		}
		
		return TRUE;
	}
	
	/**
	 * update page database structure
	 * @param string $table
	 * @param array $field
	 * @access private
	 * @return mixed
	 */
	protected function _update_page_db_schema($table, $field)
	{
		// load database forge class forcely
		$CI =& get_instance();
		if ( ! isset($CI->dbforge) )
		{
			$CI->load->dbforge();
		}
		
		$indexes = array();
		
		foreach ( $field as $name => $column )
		{
			if ( $name === 'key' || isset($column['key']) )
			{
				continue;
			}
			
			if ( isset($column['index']) )
			{
				$indexes[] = $column['index'];
				unset($column['index']);
			}
			
			if ( $this->db->field_exists($name, $table) )
			{
				// modify
				$column['name'] = $name;
				$ret            = $CI->dbforge->modify_column($table, array($name => $column));
			}
			else 
			{
				// create
				$ret = $CI->dbforge->add_column($table, array($name => $column));
			}
			
			if ( ! $ret )
			{
				return 'UPDATE_PAGE_DB_MISSED';
			}
		}
		
		$this->_merge_db_index($table, $indexes);
		
		return TRUE;
	}
	
	/**
	 * merge the table index
	 * @param string $table
	 * @param array $index
	 * @access private
	 * @return mixed
	 */
	protected 	function _merge_db_index($table, $index)
	{
		$ind  = $this->_get_table_indexes($table);
		$bind = array();
		
		if ( $ind === FALSE )
		{
			return;
		}
		
		foreach ( $index as $column )
		{
			if ( in_array($column, $ind) )
			{
				continue;
			}
			$bind[] = $column;
		}
		
		if ( count($bind) > 0 )
		{
			$sql = sprintf('ALTER TABLE `%s` ADD INDEX ( `%s` )', $table, implode('`,`', $bind));
			$this->db->simple_query($sql);
		}
		
		// drop index
		foreach ( $ind as $column )
		{
			if ( in_array($column, $index) )
			{
				continue;
			}
			$this->db->simple_query(sprintf('ALTER TABLE `%s` DROP INDEX `%s`;', $table, $column));
		}
	}
	
	/**
	 * Get table indexes
	 * @param string $table
	 * @access private 
	 * @return mixed
	 */
	protected function get_table_indexes($table)
	{
		$query = $this->db->query('SHOW INDEX FROM ' . $table);
		
		if ( !$query )
		{
			return FALSE;
		}
		
		$ret = array();
		foreach ( $query->result() as $value )
		{
			$name = $value->Column_name;
			if ( ! in_array($name, $ret) && $value->Key_name !== 'PRIMARY')
			{
				$ret[] = $name;
			}
		}
		return $ret;
	}
	
	/**
	 * Block installation
	 * @param object $block
	 * @param string $plugin_handle
	 * @param int $plugin_id
	 * @access private
	 * @return mixed
	 */
	protected function _install_block($block, $plugin_handle, $plugin_id)
	{
		$collection = kill_traversal($block->block_handle);
		$classname  = ucfirst($collection) . '_block';
		$block_path = $this->plugin_path . $plugin_handle . '/blocks/' . $collection . '/';
		
		// install history exists?
		$sql   = 'SELECT collection_id FROM collections WHERE plugin_id = ? AND collection_name = ?';
		$query = $this->db->query($sql, array($plugin_id, $collection));
		
		// If record exsits, update
		if ( $query->row() )
		{
			$sql = 'UPDATE collections SET is_enabled = 1 WHERE plugin_id = ? AND collection_name = ?';
			return $this->db->query($sql, array($plugin_id, $collection));
		}
		
		// new installation --------------------------------------------
		
		// Does fileset exists?
		if ( ! file_exists($block_path . $collection . '.php')
			|| ! file_exists($block_path . 'add.php') 
			|| ! file_exists($block_path . 'edit.php')
			|| ! file_exists($block_path . 'view.php') )
		{
			return 'BLOCK_FILESET_NOT_ENOUGH';
		}
		
		require($block_path . $collection . '.php');
		
		if ( ! class_exists($classname) )
		{
			return 'NO_BLOCK_CLASS';
		}
		
		// instanciate
		$class = new $classname();
		
		// basic data array
		$data = array(
			'collection_name'  => $collection,
			'description'      => ($class->get_description()) ? $class->get_description() : '',
			'interface_width'  => ($class->get_if_width()) ? $class->get_if_width() : 500,
			'interface_height' => ($class->get_if_height()) ? $class->get_if_height() : 500,
			'added_date'       => db_datetime(),
			'block_name'       => ($class->get_block_name()) ? $class->get_block_name() : '',
			'plugin_id'        => $plugin_id
		);
		
		// Does block class have table name?
		if ( ! $class->get_table_name() )
		{
			return 'NO_BLOCK_TABLE_NAME';
		}
		
		// Does blocl class have "db" method?
		if ( ! method_exists($class, 'db') )
		{
			return 'NO_DB_STRUCTURE_METHOD';
		}
		
		$db_structure   = $class->db();
		$table_name     = $class->get_table_name();
		
		// Is structure valid?
		if ( ! $db_structure || ! is_array($db_structure) || ! isset($db_structure[$table_name]) )
		{
			return 'INVALID_BLOCK_DB_STRUCTURE';
		}
		
		// add db table
		$data['db_table'] = $table_name;
		
		$CI =& get_instance();
		$CI->load->model('block_model');
		
		// loop and create/update block database
		foreach ( $db_structure as $table => $structure )
		{
			$is_master_table = ( $table_name === $table ) ? TRUE : FALSE;
			
			if ( $this->db->table_exists($table) )
			{
				$ret = $CI->block_model->_update_collection_db_from_array($table, $structure, $is_master_table);
			}
			else 
			{
				$ret = $CI->block_model->_make_db_from_array($table, $structure, $is_master_table);
			}
			
			if ( $ret === 'ERROR' )
			{
				return 'NOT_ENOUGH_BLOCK_COLUMN';
			}
			else if ( $ret === FALSE )
			{
				return FALSE;
			}
		}
		
		// Insert master collection
		return ( $this->db->insert('collections', $data) ) ? TRUE : FALSE;
	}
	
	/**
	 * plugin update installation
	 * @param int $plugin_id
	 * @access private
	 * @return mixed
	 */
	protected function _update_plugin($plugin_id)
	{
		// query binding
		$sql =
				'UPDATE '
				.	'%s '
				.'SET '
				.	'is_enabled = 1 '
				.'WHERE '
				.	'plugin_id = ?';
		
		// target tables and error messages
		$target_tables = array(
			'sz_plugins'  => 'PLUGIN_INSTALL_FAILED',
			'page_paths'  => 'PAGE_INSTALL_FAILED',
			'collections' => 'BLOCK_INSTALL_FAILED'
		);
		// update plugin record
		foreach ( $target_tables as $table => $error )
		{
			if ( ! $this->db->query(sprintf($sql, $table), (int)$plugin_id) )
			{
				return $error;
			}
		}
		
		return TRUE;
	}
}

if ( ! function_exists('register_action') )
{
	/**
	 * Action register function
	 * set plugin hook function
	 * @param string $point
	 * @param mixed $exec
	 */
	function register_action($point, $exec = '')
	{
		if ( ! $exec )
		{
			return;
		}
		$EXT =& load_class('Hooks');
		$EXT->regist_hook($point, $exec);
	}
}

SeezooPluginManager::start_up();
