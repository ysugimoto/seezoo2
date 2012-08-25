<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ====================================================
 * Seezoo Area management Class
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * ====================================================
 */
class Area
{
	protected $ci;
	protected $editable = TRUE;
	protected $moveable = TRUE;
	protected $reverse_view;
	
	// property from constructor
	public $page_id;
	public $area_id;
	public $area_name;
	public $created_date;

	function __construct($params, $reverse_view = FALSE)
	{
		//$this->editable = $editable;
		//$this->moveable = $moveable;
		$this->reverse_view = $reverse_view;
		
		if (is_array($params))
		{
			foreach ($params as $key => $val)
			{
				$this->{$key} = $val;
			}
			$this->ci =& get_instance();
		}
	}

	/**
	 * load_blocks
	 * Generate blocks in this area
	 */
	public function load_blocks()
	{
		$id         = $this->area_id;
		$is_editing = ($this->ci->edit_mode == 'EDIT_SELF') ? TRUE : FALSE;
		$mode       = ( defined('SZ_OUTPUT_MODE') )
		              ? SZ_OUTPUT_MODE
		              : $this->ci->config->item('final_output_mode');
		              
		// default, get blocks from version table
		$from_table = 'block_versions';
		
		// permission for PC or mobile
		$mobile     = Mobile::get_instance();
		
		$data  = array(
			'aid'      => $id,
			'can_edit' => $this->editable,
			'can_move' => $this->moveable,
			'reverse'  => $this->reverse_view
		);
		
		if ($is_editing)
		{
			// if page is edit mode, get blocks from pending table
			$from_table = 'pending_blocks';
		}
		
			
		$sql = 'SELECT DISTINCT '
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
				.'FROM ' . $from_table . ' as B '
				.'LEFT OUTER JOIN ('
				.		'SELECT '
				.			'block_id, '
				.			'allow_view_id, '
				.			'allow_edit_id, '
				.			'allow_mobile_carrier '
				.		'FROM '
				.			'block_permissions '
				.	') as BP ON ('
				.		'BP.block_id = IF ( B.slave_block_id > 0, B.slave_block_id, B.block_id ) '
				.') '
				.'LEFT JOIN collections as C ON ('
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
		$query = $this->ci->db->query($sql, array($id, 1, $this->ci->version_number));
		
		if ( $is_editing && $this->editable && $this->reverse_view === TRUE )
		{
			$this->ci->load->view('parts/add_block', array('area_name' => $this->area_name, 'page_id' => $this->ci->page_id));
		}
		
		// blocks exists?
		if ($query->num_rows() > 0)
		{
			$blocks = $query->result_array();
			$query->free_result();
			
		
			// If page id editting, output arrange master <div>.
			if ( $is_editing )
			{
				$this->ci->load->view('parts/arrange_master_wrapper', $data);
			}
			
			if ( $this->reverse_view === TRUE )
			{
				$blocks = array_reverse($blocks);
			}
			
			foreach ($blocks as $value)
			{
				if ( $mobile->is_mobile() )
				{
					$can_edit = FALSE;
					if ( $mobile->is_docomo() )
					{
						$can_view = is_permission_allowed($value['allow_mobile_carrier'], 'D');
					}
					else if ( $mobile->is_au() )
					{
						$can_view = is_permission_allowed($value['allow_mobile_carrier'], 'A');
					}
					else if ( $mobile->is_softbank() )
					{
						$can_view = is_permission_allowed($value['allow_mobile_carrier'], 'S');
					}
					else if ( $mobile->is_willcom() )
					{
						$can_view = is_permission_allowed($value['allow_mobile_carrier'], 'W');
					}
					else
					{
						$can_view = FALSE;
					}
				}
				else
				{
					if ( ! $this->ci->is_admin )
					{
						$can_edit = is_permission_allowed($value['allow_edit_id'], $this->ci->user_id);
						$can_view = is_permission_allowed($value['allow_view_id'], $this->ci->user_id);
						
						if ( $can_edit === FALSE && $this->ci->member_id > 0)
						{
							$can_edit = is_permission_allowed($value['allow_edit_id'], 'm');
						}
						if ( $can_view === FALSE && $this->ci->member_id > 0)
						{
							$can_view = is_permission_allowed($value['allow_view_id'], 'm');
						}
					}
					else 
					{
						$can_edit = $can_view = TRUE;
					}
				}
				$can_delete = $can_edit; // same edit permission
				
				if ( ! $can_view )
				{
					continue;
				}
				
				if ( (int)$value['is_enabled'] === 0 )
				{
					$can_edit = FALSE;
				}
				
				$block = $this->ci->load->block(
											$value['collection_name'],
											((int)$value['slave_block_id'] > 0) ? $value['slave_block_id'] : $value['block_id'],
											TRUE,
											TRUE /* force view mode */
										);
				
				// mark slave block original id
				$block->orig_block_id = (int)$value['block_id'];
				
				$enable = ( ! isset($value[$mode . '_enabled']) || $value[$mode . '_enabled'] > 0 ) ? TRUE : FALSE;
	
				// Is block enable to output? 
				if ( $enable )
				{
					// add header item if file exists
					$block->add_header_item($value['collection_name']);
				}
				
				// call view method
				$block->view();
	
				if (!empty($value['ct_handle']))
				{
					$view_path = 'templates/' . $value['ct_handle'] . '/view';
				}
				else
				{
					$view_path = 'view';
				}
				$data['block']      = $block;
				$data['value']      = $value;
				$data['bid']        = $value['block_id'];
				$data['can_edit']   = $can_edit;
				$data['can_delete'] = $can_delete;
	
				if ( $is_editing )
				{
					$this->ci->load->view('parts/edit_wrapper', $data);
					if ( $enable )
					{
						// load the block-view file with block instance parameter.
						$dir    = $value['collection_name'] . '/';
						$this->ci->load->block_view($dir . $view_path, array('controller' => $block), FALSE, $block->plugin_handle);
						//$block->load_view($view_path);
					}
					else
					{
						$this->ci->load->view('elements/block_view_disabled', $value);
						if ( $block->is_multi_column() )
						{
							$this->ci->load->view('parts/edit_wrapper_end', $data);
						}
					}
					// Does block show multi-column?
					if ( ! $block->is_multi_column() )
					{
						$this->ci->load->view('parts/edit_wrapper_end', $data);
					}
				}
				else
				{
					if ( $enable )
					{
						//$block->load_view($view_path);
						$dir    = $value['collection_name'] . '/';
						$this->ci->load->block_view($dir . $view_path, array('controller' => $block), FALSE, $block->plugin_handle);
					}
				}
			}
			
//			if ( $this->reverse_view === TRUE )
//			{
//				$this->show_blocks_reverse($blocks, $data);
//			}
//			else
//			{
//				$this->show_blocks($blocks, $data);
//			}
		}
		else
		{
			if ($is_editing)
			{
				// if block is empty, set arrange master block for moveable blocks.
				$this->ci->load->view('parts/arrange_master_wrapper', $data);
			}
		}

		if ( $is_editing && $this->editable )
		{
			if ( $this->reverse_view === FALSE )
			{
				$this->ci->load->view('parts/add_block', array('area_name' => $this->area_name, 'page_id' => $this->ci->page_id));
			}
			$this->ci->load->view('parts/arrange_master_wrapper_end');
		}
		
	}
	
	/**
	 * Duplicate area
	 * duplicate same area, blocks in this area to target page
	 * @param int $target_page_id
	 * @return void
	 */
	public function duplicate($target_page_id = 0)
	{
		// guard empty page
		// or duplicate to same page
		// or same areaname already_exists
		if ( ! $target_page_id
		     || $target_page_id == $this->page_id
		     || $this->_area_exists($target_page_id) )
		{
			return;
		}
		
		$this->ci->load->model('sitemap_model');
		
		// get current version
		$current_version        = $this->ci->sitemap_model->get_current_version($this->page_id);
		$current_target_version = $this->ci->sitemap_model->get_current_version($target_page_id);
		$now_date               = db_datetime();
		
		// insert area target
		$area_data = array(
			'area_name'    => $this->area_name,
			'page_id'      => $target_page_id,
			'created_date' => $now_date
		);
		$this->ci->db->insert('areas', $area_data);
		
		// and get inserted id
		$new_area_id = $this->ci->db->insert_id();
		
		// get block data on copy-from area
		$sql =
				'SELECT DISTINCT '
				.	'* '
				.'FROM '
				.	'block_versions '
				.'WHERE '
				.	'area_id = ? '
				.'AND '
				.	'version_number = ?'
				;
		$query = $this->ci->db->query($sql, array($this->area_id, $current_version));
		
		// block not exists
		if ( ! $query || $query->num_rows() === 0 )
		{
			return;
		}
		// duplicate block
		foreach ( $query->result() as $block_data )
		{
			$block = $this->ci->load->block(
											$value['collection_name'],
											( (int)$value['slave_block_id'] > 0 ) ? $value['slave_block_id'] : $value['block_id'],
											TRUE
										);
										
			$new_block_id = $block->duplicate();
			$bv_data      = array(
				'block_id'        => $new_block_id,
				'collection_name' => $block_data->collection_name,
				'area_id'         => $new_area_id,
				'display_order'   => $block_data->display_order,
				'is_active'       => $block_data->is_active,
				'version_date'    => $now_date,
				'version_number'  => $current_target_version,
				'ct_handle'       => $block_data->ct_handle,
				'slave_block_id'  => $block_data->slave_block_id
			);
			
			$this->ci->db->insert('block_versions', $bv_data);
		}
	}
	
	/**
	 * Check the area_name already exsits at target page
	 * @param int $target_page_id
	 * @return bool
	 */
	protected function _area_exists($target_page_id)
	{
		$sql =
				'SELECT '
				.	'area_id '
				.'FROM '
				.	'areas '
				.'WHERE '
				.	'area_name = ? '
				.'AND '
				.	'page_id = ?'
				;
		$query = $this->ci->db->query($sql, array($this->area_name, $target_page_id));
		if ( $query && $query->num_rows() > 0 )
		{
			// already exists!
			return TRUE;
		}
		return FALSE;
	}
}