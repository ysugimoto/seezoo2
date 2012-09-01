<?php  if ( ! defined('SZ_EXEC')) exit('No direct script access allowed');
/**
 * ====================================================
 * seezoo Block exntensible base Class
 *
 * @package seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * ====================================================
 */
abstract class Block
{
	// Abstract declaration properties
	abstract public    $blockName;
	abstract public    $description;
	abstract protected $table;
	
	// Abstract declaration method
	abstract public static function db();
	
	// Block information properties
	public $interfaceWidth  = 500;
	public $interfaceHeight = 500;
	public $enableMB        = TRUE;
	public $enableSP        = TRUE;
	public $enablePC        = TRUE;
	public $ignoreEscapes   = array();
	public $multiColumn     = FALSE;
	public $viewEngine      = 'default';
	
	// Requires parent class properties
	protected $block_id;
	protected $blockRecord;
	protected $collectionName;
	protected $seezoo;
	
	
	// --------------------------------------------------
	
	
	/**
	 * init
	 * calls instead of constructor
	 * 
	 * @access public
	 * @param  $blockID
	 * @param  $collectionName
	 */
	public function init($blockID = FALSE, $collectionName = FALSE)
	{
		$this->block_id       = ( ! $blockID ) ? $this->_generateBlockID() : $blockID;
		$this->collectionName = $collectionName;
		$this->db             = Seezoo::$Importer->database();
		$this->seezoo         = SeezooCMS::getInstance();
		
		// get tableInfo
		if ( $blockID )
		{
			$this->_initBlockRecord();
		}
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Set block record
	 * 
	 * @access protected
	 */
	protected function _initBlockRecord()
	{
		$sql = 
			'SELECT '
			.	'* '
			.'FROM '
			. $this->db->prefix().$this->table . ' '
			.'WHERE '
			.	'block_id = ? '
			.'LIMIT 1'
			;
		$query = $this->db->query($sql, array($this->block_id));
		
		if ( $query )
		{
			$record = $query->rowArray();
			foreach ( $record as $key => $val )
			{
				$this->{$key} = $val;
			}
			$this->_blockRecord = $record;
		}
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Duplicate same block
	 * 
	 * @access public
	 * @return int $newBlockID
	 */
	public function duplicate()
	{
		// stack current block_id
		$blockID = $this->_blockRecord['block_id'];
		
		// generate new block_id
		$newblockID = $this->_generateBlockID();
		$this->_blockRecord['block_id'] = $newblockID;
		
		// insert new block record
		$this->db->insert($this->table, $this->_blockRecord);
		
		// and insert block permissions too.
		$BlockPermission = ActiveRecord::finder('block_permissions')
		                    ->findByBlockId($blockID);
		
		if ( $BlockPermission )
		{
			unset($BlockPermission->block_permissions_id);
			$BlockPermission->block_id = $newblockID;
			$BlockPermission->insert();
		}
		
		return $newblockID;
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Hook view method
	 * please logic declarate in sub class
	 */
	public function view()
	{
		
	}
	
	
	// --------------------------------------------------
	
	/**
	 * Getter function of protected proterties that extended SubClass
	 * 
	 * @access public
	 * @return string
	 */
	public function getTableName()
	{
		return $this->table;
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Set block action's URI
	 * 
	 * @access public
	 * @param  array $segment
	 * @return string
	 */
	public function setActionPath($segment = array())
	{
		$seg = '';
		if ( count($segment) > 0 )
		{
			$seg = '/' . implode('/', $segment);
		}
		
		$sess  = Seezoo::$Importer->library('Session');
		$token = sha1(uniqid(mt_rand(), TRUE));
		$sess->set('action_' .  $this->collectionName . '_' . $this->block_id, $token);
		$prefix = get_base_link();
		return $prefix . 'action/' . $this->collectionName . '/' . $this->block_id . '/' . $token . $seg;
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Insert / Update block records
	 * 
	 * @access public
	 * @param  array $args
	 * @return int $newBlockID
	 */
	public function save($args)
	{
		if ( ! $this->block_id )
		{
			// insert
			$newBlockID = $this->db->insert($this->table, $args);
		}
		else
		{
			// update (versioning insert)
			$args['block_id'] = $this->_generateBlockID();
			$newBlockID       = $this->db->insert($this->table, $args);
			$this->block_id   = $args['block_id'];
		}
		return $newBlockID;
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Add asset to output header section
	 * 
	 * @access public
	 * @param  string $str
	 */
	public function addHeader($str)
	{
		if ( strpos($str, '<link') !== FALSE )
		{
			$this->seezoo->additionalHeaderCss[] = $str;
		}
		else if ( strpos($str, '<script') !== FALSE )
		{
			$this->seezoo->additionalHeaderJavaScript[] = $str;
		}
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Add asset to output footer section
	 * 
	 * @access public
	 * @param  string $str
	 */
	public function addFooter($str)
	{
		if ( strpos($str, '<script') !== FALSE )
		{
			$this->seezoo->additionalFooterJavaScript[] = $str;
		}
		else 
		{
			$this->seezoo->additionalFooterElement[] = $str;
		}
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Add block default assets
	 * 
	 * @access public
	 */
	public function addBlockAssets()
	{
		if ( SZ_OUTPUT_MODE === 'mb' )
		{
			return;
		
		$collectionName = $this->collectionName;}
		$subDir         = ( SZ_OUTPUT_MODE === 'sp' ) ? 'smartphone/' : '';
		$jsPath         = 'blocks/' . $collectionName . '/' . $subDir . 'view.js';
		$cssPath        = 'blocks/' . $collectionName . '/' . $subDir . 'view.css';
		
		// blocks javascript or CSS file required?
		if ( ! isset($this->seezoo->additionalHeaderJavaScript[$collectionName]) )
		{
			$js = $this->_detectAsset($jsPath);
			if ( SZ_OUTPUT_MODE === 'sp' && $js === '' )
			{
				$jsPath = 'blocks/' . $block_name . '/view.js';
				$js     = $this->_detectAsset($jsPath);
			}
			
			if ( $js !== '' )
			{
				$this->seezoo->additionalHeaderJavaScript[$collectionName] = $js;
			}
		}
		
		if ( ! isset($this->seezoo->additionalHeaderCss[$collectionName]) )
		{
			$css = $this->_detectAsset($cssPath);
			if ( SZ_OUTPUT_MODE === 'sp' && $css === '' )
			{
				$cssPath = 'blocks/' . $block_name . '/view.css';
				$css     = $this->_detectAsset($cssPath);
			}
			
			if ( $css !== '' )
			{
				$this->seezoo->additionalHeaderCss[$collectionName] = $css;
			}
		}
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Asset detection
	 * 
	 * @access protected
	 * @param  string $file
	 * @return string
	 */
	protected function _detectAsset($file)
	{
		$fileString = '';
		$pakcages   = Seezoo::getPackage();
		
		foreach ( $packages as $pkg )
		{
			if ( file_exists(PKGPATH . $pkg . '/' . $file) )
			{
				$fileString = build_javascript(file_link() . 'packages/' . $pkg . '/' . $file);
				break;
			}
		}
		
		if ( empty($fileString) )
		{
			// Is there extension file?
			if ( file_exists(EXTPATH . $file) )
			{
				$fileString = build_javascript(file_link() . 'extensions/' . $file);
			}
			else if ( file_exists(ROOTPATH . $file) )
			{
				$fileString = build_javascript(file_link() . $file);
			}
		}
		
		return $fileString;
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Render the block view
	 * 
	 * @access public
	 * @param  string $path
	 * @param  array $param
	 * @param  bool $return
	 */
	public function renderView($path, $param = array(), $return = FALSE)
	{
		// Switch engine
		$SZ       = Seezoo::getInstance();
		$engine   = $SZ->view->getEngine();
		$SZ->view->engine($this->viewEngine);
		
		$file     = SZ_BLOCK_DIR . $this->collectionName . '/' . $path . $SZ->view->getExtension();
		$pakcages = Seezoo::getPackage();
		$viewFile = '';
		
		foreach ( $packages as $pkg )
		{
			if ( file_exists(PKGPATH . $pkg . '/' . $file) )
			{
				$viewFile = PKGPATH . $pkg . '/' . $file;
				break;
			}
		}
		
		if ( empty($viewFile) )
		{
			// Is there extension file?
			if ( file_exists(EXTPATH . $file) )
			{
				$viewFile = EXTPATH . $file;
			}
			else if ( file_exists(ROOTPATH . $file) )
			{
				$viewFile = ROOTPATH . $file;
			}
		}
		
		if ( empty($viewFile) )
		{
			throw new RuntimeException('Unable to load block view file: ' . $this->collectionName . ' block.');
		}
		
		$buffer = $SZ->view->renderBlockView($viewFile, $param);
		$SZ->view->engine($engine);
		return $buffer;
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Genetrate new block record
	 * 
	 * @access protected
	 * @return int
	 */
	protected function _generateBlockID()
	{
		$Block = ActiveRecord::create('blocks');
		$Block->created_time = db_datetime();
		return $Block->insert();
	}
	
	
	// --------------------------------------------------
	
	
	/**
	 * Blocak postdata validation
	 * default no works. please override on subclass.
	 * @param $args
	 */
	protected function validation($args = array())
	{
		return TRUE;
	}
}