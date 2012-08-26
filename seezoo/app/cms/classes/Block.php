<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ====================================================
 * Seezoo Block exntensible base Class
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * ====================================================
 */
class Block
{
	public $ci;
	public $bid;
	public $collection_name;
	public $plugin_handle;
	public $orig_block_id;
	public $_block_record = array();

	/**
	 * init
	 * calls instead of constructor
	 * @access public
	 * @param $bid
	 * @param $cname
	 * @param $plugin_handle
	 * @return void
	 */
	function init($bid = FALSE, $cname = FALSE, $plugin_handle = '')
	{
		$CI = $this->ci =& get_instance();
		if (!$bid)
		{
			$this->block_id = $this->_generate_block_id();
		}
		else
		{
			$this->block_id = $bid;
		}
		$this->collection_name = $cname;
		$this->plugin_handle   = $plugin_handle;

		// get tableInfo
		if ($bid)
		{
			$this->block_id = $bid;

			$sql = 'SELECT * FROM ' . $this->table . ' WHERE block_id = ? LIMIT 1';
			$query = $CI->db->query($sql, array($this->block_id));

			if ($query->num_rows() > 0)
			{
				foreach ($query->row_array() as $key => $val)
				{
					$this->{$key} = $val;
				}
				$this->_block_record = $query->row_array();
			}
		}
	}

	/**
	 * duplicate same block
	 * @access public
	 * @return int $new_bid
	 */
	function duplicate()
	{
		// stack current block_id
		$bid = $this->_block_record['block_id'];
		
		// generate new block_id
		$new_bid = $this->_generate_block_id();
		$this->_block_record['block_id'] = $new_bid;

		// insert new block record
		$this->ci->db->insert($this->table, $this->_block_record);
		
		// and insert block permissions too.
		$query = $this->ci->db->query('SELECT * FROM block_permissions WHERE block_id = ? LIMIT 1', array($bid));
		if ( $query && $query->row() )
		{
			$result = $query->row_array();
			unset($result['block_permissions_id']);
			$result['block_id'] = $new_bid;
			$this->ci->db->insert('block_permissions', $result);
		}
		
		return $new_bid;
	}
	
	/**
	 * hook view method
	 * please logic define sub class
	 */
	function view()
	{
		
	}

	/**
	 * gettter functions of protected proterties that extended SubClass
	 */
	function get_table_name()
	{
		return ((isset($this->table))) ? $this->table : FALSE;
	}

	function get_if_width()
	{
		return ((isset($this->interface_width))) ? $this->interface_width : FALSE;
	}

	function get_if_height()
	{
		return ((isset($this->interface_height))) ? $this->interface_height : FALSE;
	}

	function get_block_name()
	{
		return ((isset($this->block_name))) ? $this->block_name : FALSE;
	}

	function get_description()
	{
		return ((isset($this->description))) ? $this->description : FALSE;
	}

	function get_enables($mode)
	{
		return ( ( isset($this->{'enable_' . $mode})) ) ? (int)$this->{'enable_' . $mode} : 1;
	}

	function set_action_path($segment = array())
	{
		$seg = '';
		if (count($segment) > 0)
		{
			$seg = '/' . implode('/', $segment);
		}

		$token = md5(uniqid(mt_rand(), TRUE));
		$this->ci->session->set_userdata('action_' .  $this->collection_name . '_' . $this->block_id, $token);
		$prefix = ($this->ci->is_ssl_page) ? ssl_page_link() : page_link();
		return $prefix . 'action/' . $this->collection_name . '/' . $this->block_id . '/' . $token . $seg;
	}

	function set_ajax_request_path($method_name)
	{
		$token = md5(uniqid(mt_rand(), TRUE));
		$this->ci->session->set_userdata('action_' .  $this->collection_name . '_' . $this->block_id, $token);
		$prefix = ($this->ci->is_ssl_page) ? ssl_page_link() : page_link();
		return $prefix . 'ajax/' . $method_name . '/' . $this->collection_name . '/' . $this->block_id . '/' . $token;
	}

	function get_skip_sanityzed_columns()
	{
		return (isset($this->ignore_escape) && is_array($this->ignore_escape)) ? $this->ignore_escape : array();
	}

	function is_multi_column()
	{
		return (isset($this->multi_column)) ? $this->multi_column : FALSE;
	}

	function save($args)
	{
		$this->ci =& get_instance();

		if (!$this->bid)
		{
			// insert
			$r = $this->ci->db->insert($this->table, $args);
		}
		else
		{
			// update (versioning insert)
			$args['block_id'] = $this->_generate_block_id();
			$r = $this->ci->db->insert($this->table, $args);
			$this->bid = $args['block_id'];
		}
		return $r;
	}

	function add_header($str)
	{
		if (strpos($str, '<link') !== FALSE)
		{
			$this->ci->additional_header_css[] = $str;
		}
		else if (strpos($str, '<script') !== FALSE)
		{
			$this->ci->additional_header_javascript[] = $str;
		}
	}

	function add_footer($str)
	{
		if (strpos($str, '<script') !== FALSE)
		{
			$this->ci->additional_footer_javascript[] = $str;
		}
		else 
		{
			$this->ci->additional_footer_element[] = $str;
		}
	}

	function add_header_item($block_name)
	{
		if ( SZ_OUTPUT_MODE === 'mb' )
		{
			return;
		}
		$CI       =& get_instance();
		$subdir   = ( SZ_OUTPUT_MODE === 'sp' ) ? 'smartphone/' : '';
		$js_path  = 'blocks/' . $block_name . '/' . $subdir . 'view.js';
		$css_path = 'blocks/' . $block_name . '/' . $subdir . 'view.css';

		// blocks javascript or CSS file required?
		if ( ! isset($CI->additional_header_javascript[$block_name]) )
		{
			// Is there package file?
			if ( file_exists(SZ_EXT_PATH . $js_path) )
			{
				$CI->additional_header_javascript[$block_name] = build_javascript(package_link() . $js_path);
			}
			else if ( file_exists(FCPATH . $js_path) )
			{
				$CI->additional_header_javascript[$block_name] = build_javascript(file_link() . $js_path);
			}
			else if ( SZ_OUTPUT_MODE === 'sp' )
			{
				$js_path = 'blocks/' . $block_name . '/view.js';
				if ( file_exists(SZ_EXT_PATH . $js_path) )
				{
					$CI->additional_header_javascript[$block_name] = build_javascript(package_link() . $js_path);
				}
				else if ( file_exists(FCPATH . $js_path) )
				{
					$CI->additional_header_javascript[$block_name] = build_javascript(file_link() . $js_path);
				}
			}
		}

		if ( ! isset($CI->additional_header_css[$block_name]) )
		{
			// Is there package file?
			if ( file_exists(SZ_EXT_PATH . $css_path) )
			{
				$CI->additional_header_css[$block_name] = build_css(package_link() . $css_path);
			}
			else if ( file_exists(FCPATH . $css_path) )
			{
				$CI->additional_header_css[$block_name] = build_css(file_link() . $css_path);
			}
			else if ( SZ_OUTPUT_MODE === 'sp' )
			{
				$css_path = 'blocks/' . $block_name . '/view.css';
				if ( file_exists(SZ_EXT_PATH . $css_path) )
				{
					$CI->additional_header_css[$block_name] = build_css(package_link() . $css_path);
				}
				else if ( file_exists(FCPATH . $css_path) )
				{
					$CI->additional_header_css[$block_name] = build_css(file_link() . $css_path);
				}
			}
		}
	}

	function add_footer_item($block_name)
	{
		$CI =& get_instance();
		$path = 'blocks/' . $block_name . '/';
		// blocks javascript or CSS file required?
		if (file_exists($path . 'view.js') && !array_key_exists($block_name, $CI->additional_header_javascript))
		{
			$CI->additional_footer_javascript[$block_name] = build_javascript($path . 'view.js');
		}
		if (file_exists($path . 'view.css') && !array_key_exists($block_name, $CI->additional_header_css))
		{
			$CI->additional_footer_css[$block_name] = build_css($path . 'view.css');
		}
	}
	
	function load_view($path, $param = array(), $return = FALSE)
	{
		$dir    = $this->collection_name . '/';
		//$params['controller'] = $this;
		return $this->ci->load->block_view($dir . $path, $params, $return, $this->plugin_handle);
	}

	function _generate_block_id()
	{
		$this->ci->db->insert('blocks', array('created_time' => date('Y-m-d H:i:s', time()), 'collection_name' => $this->collection_name));
		$id = $this->ci->db->insert_id();
		return $id;
	}
	
	/**
	 * is_edit_mode
	 * @access public
	 * @return bool
	 */
	function is_edit_mode()
	{
		return (isset($this->ci->edit_mode)
				  && $this->ci->edit_mode === 'EDIT_SELF') ? TRUE : FALSE;
	}
	
	function is_mobile()
	{
		return ( $this->ci->config->item('is_mobile') || $this->ci->view_mode === 'mb' ) ? TRUE : FALSE;
	}
	
	function is_smartphone()
	{
		return ( $this->ci->config->item('is_smartphone') || $this->ci->view_mode === 'sp' ) ? TRUE : FALSE;
	}
	
	/**
	 * Blocak postdata validation
	 * default no works. please override on subclass.
	 * @param $args
	 */
	function validation($args = array())
	{
		return TRUE;
	}

}