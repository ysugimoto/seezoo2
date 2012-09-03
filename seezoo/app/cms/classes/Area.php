<?php  if ( ! defined('SZ_EXEC')) exit('No direct script access allowed');

define('BASEPATH', 1);

/**
 * ====================================================
 * 
 * Area management Class
 *
 * @package seezoo-CMS Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ====================================================
 */
class Area
{
	/**
	 * This area ID
	 * @var int
	 */
	protected $areaID;
	
	/**
	 * This page ID
	 * @var int
	 */
	protected $pageID;
	
	/**
	 * This area name
	 * @var string
	 */
	protected $areaName;
	
	/**
	 * Database instance
	 * @var SZ_Database
	 */
	protected $db;
	
	
	/**
	 * seezoo CMS instance
	 * @var SeezooCMS
	 */
	protected $seezoo;


	function __construct($name = FALSE, $pageID = 0)
	{
		if ( ! $name )
		{
			throw new InvalidArgumentException('Area name must not be empty!');
		}
		
		$this->areaName = $name;
		$this->db       = Seezoo::$Importer->database();
		$this->seezoo   = SeezooCMS::getInstance();
		$this->pageID   = ( $pageID === 0 )
		                    ? $this->seezoo->page->page_id
		                    : $pageID;
		
		$this->_detectArea($pageID);
	}
	
	
	// ---------------------------------------------------------------
	
	
	/**
	 * Area detection
	 * 
	 * @access protected
	 */
	protected function _detectArea()
	{
		$Area = ActiveRecord::finder('areas')
		        ->setOrderBy('area_id', 'desc')
		        ->findByAreaNameAndPageId($this->areaName, $this->pageID);
		
		if ( ! $Area )
		{
			// Create new area record when record not exists
			$Area = ActiveRecord::create('areas');
			$Area->area_name    = $this->areaName;
			$Area->page_id      = $this->pageID;
			$Area->created_date = db_datetime();
			$this->areaID = $Area->insert();
		}
		else
		{
			$this->areaID = $Area->area_id;
		}
	}
	
	
	// ---------------------------------------------------------------
	
	
	/**
	 * Display area with contains blocks
	 * 
	 * @access public
	 * @param  array $params
	 */
	public function show($params = array())
	{
		$SZ        = Seezoo::getInstance();
		$editting  = ( $this->seezoo->edit_mode === 'EDIT_SELF' ) ? TRUE : FALSE;
		$reverse   = ( isset($params['reverse']) ) ? (bool)$param['reverse'] : FALSE;
		
		$blocks = $this->_getContainedBlocks($editting, $this->seezoo->page->version_number);
		
		if ( $editting && $reverse === TRUE )
		{
			$SZ->view->assign(array('areaName' => $this->areaName, 'pageID' => $this->pageID));
			$SZ->view->render('parts/add_block');
		}
		
		if ( $blocks )
		{
			if ( $editting )
			{
				$SZ->view->render('parts/arrange_master_wrapper', $data);
			}
			if ( $reverse === TRUE )
			{
				$blocks = array_reverse($blocks);
			}
			$this->_showBlocks($blocks, $editting, $reverse);
		}
		else if ( $editting )
		{
			$SZ->view->render('parts/arrange_master_wrappper', $data);
		}
		
		if ( $editting )
		{
			if ( $reverse === FALSE )
			{
				$SZ->view->render('parts/add_block', array('areaName' => $this->areaName, 'pageID' => $this->pageID));
			}
			$SZ->view->render('parts/arrange_master_wrapper_end');
		}
	}
	
	
	// ---------------------------------------------------------------
	
	
	/**
	 * Get contained blocks
	 * 
	 * @access protected
	 * @param  bool $editting
	 * @param  int $versionNumber
	 * @return mixed
	 */
	protected function _getContainedBlocks($editting = FALSE, $versionNumber = 0)
	{
		$fromTable = ( $editting ) ? 'pending_blocks' : 'block_versions';
		$fromTable = $this->db->prefix() . $fromTable;
		
		$sql = 
				'SELECT DISTINCT '
				.	'B.block_id, '
				.	'B.slave_block_id, '
				.	'B.collection_name, '
				.	'B.area_id, '
				.	'B.ct_handle, '
				.	'BP.allow_view_id, '
				.	'BP.allow_edit_id, '
				.	'BP.allow_mobile_carrier, '
				.	'C.is_enabled, '
				.	'C.block_name, '
				.	'C.pc_enabled, '
				.	'C.sp_enabled, '
				.	'C.mb_enabled '
				.'FROM ' . $fromTable . ' as B '
				.'LEFT OUTER JOIN ('
				.		'SELECT '
				.			'block_id, '
				.			'allow_view_id, '
				.			'allow_edit_id, '
				.			'allow_mobile_carrier '
				.		'FROM '
				.			$this->db->prefix().'block_permissions '
				.	') as BP ON ('
				.		'BP.block_id = IF ( B.slave_block_id > 0, B.slave_block_id, B.block_id ) '
				.') '
				.'LEFT JOIN ' . $this->db->prefix().'collections as C ON ('
				.	'C.collection_name = B.collection_name '
				.') '
				.'WHERE '
				.	'B.area_id = ? '
				.'AND '
				.	'B.is_active = ? '
				.'AND '
				.	'B.version_number = ? '
				.'ORDER BY B.display_order ASC';
				;
		$query = $this->db->query($sql, array($this->areaID, 1, $versionNumber));
		
		return ( $query && $query->numRows() > 0 )
		         ? $query->result()
		         : FALSE; 
	}
	
	
	// ---------------------------------------------------------------
	
	
	/**
	 * Display block view
	 * 
	 * @access protected
	 * @param  array $blocks
	 * @param  bool $editting
	 * @param  bool $reverse
	 * 
	 */
	protected function _showBlocks($blocks, $editting = FALSE, $reverse = FALSE)
	{
		$SZ             = Seezoo::getInstance();
		$UserModel      = Seezoo::$Importer->model('UserModel');
		$data           = new stdClass;
		$data->aid      = $this->areaID;
		$data->can_edit = TRUE;
		$data->can_move = TRUE;
		$data->reverse  = $reverse;
		
		$isAdmin  = $UserModel->isAdmin($this->seezoo->getUserID());
		
		foreach ( $blocks as $block )
		{
			list($canEdit, $canView, $canDelete) = $this->_checkBlockPermission($block);
			
			if ( ! $canView )
			{
				continue;
			}
			
			if ( (int)$block->is_enabled === 0 )
			{
				$canEdit = FALSE;
			}
			
			$blockID    = ( (int)$block->slave_block_id > 0 ) ? $block->slave_block_id : $block->block_id;
			$BlockClass = $this->seezoo->loadBlock($block->collection_name, $blockID);
			$BlockClass->originalBlockID = (int)$block->block_id;
			
			$blockEnable = ( ! isset($block->{SZ_OUTPUT_MODE . '_enabled'})
			                 || $block->{SZ_OUTPUT_MODE . '_enabled'} ) ? TRUE : FALSE;
			
			if ( $blockEnable )
			{
				$BlockClass->addBlockAssets();
			}
			
			$BlockClass->view();
			
			$viewPath = ( ! empty($block->ct_handle) )
			              ? 'templates/' . $block->ct_handle . '/view'
			              : 'view';
			$data->block      = $BlockClass;
			$data->value      = $block;
			$data->bid        = $block->block_id;
			$data->can_edit   = $canEdit;
			$data->can_delete = $canDelete;
			
			if ( $editting )
			{
				$SZ->view->render('parts/edit_wrapper', $data);
				if ( $blockEnable )
				{
					$param = array('controller' => $BlockClass);
					$BlockClass->renderView($viewPath, $param);
				}
				else
				{
					$SZ->view->render('elements/block_view_disabled', $block);
					if ( ! $block->multiColumn )
					{
						$SZ->view->render('parts/edit_wrapper_end', $data);
					}
				}
				
				if ( ! $block->multiColumn )
				{	
					$SZ->view->render('parts/edit_wrapper_end', $data);
				}
			}
			else if ( $blockEnable )
			{
				$param = array('controller' => $BlockClass);
				$BlockClass->renderView($viewPath, $param);
			}
		}
	}
	
	
	// ---------------------------------------------------------------
	
	
	/**
	 * Check block permissions
	 * 
	 * @access protected
	 * @param  Block $block
	 * @return array (booleans)
	 */
	protected function _checkBlockPermission($block)
	{
		$Mobile    = Seezoo::$Importer->library('Mobile');
		$UserModel = Seezoo::$Importer->model('UserModel');
		$isAdmin   = $UserModel->isAdmin($this->seezoo->getUserID());
		
		// Use mobile permission
		if ( $Mobile->is_mobile() )
		{
			$canEdit = FALSE;
			switch ( TRUE )
			{
				case $Mobile->is_docomo():
					$mark = 'D';
					break;
				case $Mobile->is_au():
					$mark = 'A';
					break;
				case $Mobile->is_softbank():
					$mark = 'S';
					break;
				case $Mobile->is_willcom():
					$mark = 'W';
					break;
				default:
					$mark = FALSE;
					break;
			}
			$canView = ( $mark )
			             ? $this->seezoo->hasBlockPermission($block->allow_mobile_carrier, $mark)
			             : FALSE;
		}
		else
		{
			if ( $isAdmin )
			{
				$canEdit = $canView = TRUE;
			}
			else
			{
				$canEdit = $this->seezoo->hasBlockPermission($block->allow_edit_id, $this->seezoo->userID);
				$canView = $this->seezoo->hasBlockPermission($block->allow_edit_id, $this->seezoo->userID);
				
				$memberID = $this->seezoo->getMemberID();
				if ( $canEdit === FALSE && $memberID > 0 )
				{
					$canEdit = $this->seezoo->hasBlockPermission($block->allow_edit_id, 'm');
				}
				if ( $canView === FALSE && $memberID > 0 )
				{
					$canView = $this->seezoo->hasBlockPermission($block->allow_edit_id, 'm');
				}
			}
		}
		
		return array($canEdit, $canView, $canEdit);
	}
	
	
	// ---------------------------------------------------------------
	
	
	/**
	 * Duplicate area
	 * duplicate same area, blocks in this area to target page
	 * @param int $target_page_id
	 * @return void
	 */
	public function duplicate($targetPageID = 0)
	{
		// guard empty page
		// or duplicate to same page
		// or same areaname already_exists
		if ( ! $targetPageID
		     || $targetPageID == $this->pageID
		     || $this->_areaExists($targetPageID) )
		{
			return;
		}
		
		$Sitemap = Seezoo::$Importer->model('SitemapModel');
		
		// get current version
		$currentVersion        = $Sitemap->getCurrentVersion($this->pageID);
		$currentTargetVersion  = $Sitemap->getCurrentVersion($targetPageID);
		$nowDate               = db_datetime();
		
		$Area = ActiveRecord::create('areas');
		$Area->area_name    = $this->areaName;
		$Area->page_id      = $targetPageID;
		$Area->created_date = $nowDate;
		
		$newAreaID = $Area->insert();
		
		// get block data on copy-from area
		$blocks = ActiveRecord::finder('block_versions')
		          ->distinct()
		          ->findAllByAreaIdAndVersionNumber($this->pageID, $currentVersion);
		
		// block not exists
		if ( ! $blocks )
		{
			return;
		}
		// duplicate block
		foreach ( $blocks as $blockRecord )
		{
			$block = $this->seezoo->loadBlock(
			                               $value['collection_name'],
			                               ( (int)$value['slave_block_id'] > 0 ) ? $value['slave_block_id'] : $value['block_id']
			                             );
			
			$newBlockID  = $block->duplicate();
			$versionData = array(
				'block_id'        => $newBlockID,
				'collection_name' => $blockRecord->collection_name,
				'area_id'         => $newAreaID,
				'display_order'   => $blockRecord->display_order,
				'is_active'       => $blockRecord->is_active,
				'version_date'    => $nowDate,
				'version_number'  => $currentTargetVersion,
				'ct_handle'       => $blockRecord->ct_handle,
				'slave_block_id'  => $blockRecord->slave_block_id
			);
			
			$this->db->insert('block_versions', $versionData);
		}
	}
	
	
	// ---------------------------------------------------------------
	
	
	/**
	 * Check the area_name already exsits at target page
	 * @param int $target_page_id
	 * @return bool
	 */
	protected function _areaExists($targetPageID)
	{
		$Area = ActiveRecord::finder('areas')
		         ->findByAreaNameAndPageId($this->areaName, $targetPageID);
		
		return ( $Area ) ? TRUE : FALSE;
	}
}