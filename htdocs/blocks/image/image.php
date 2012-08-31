<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ============================================================================
 * Seezoo 画像設置ブロック
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto<neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */

class Image_block extends Block
{
	protected $table            = 'sz_bt_image_block';
	protected $block_name       = '画像ブロック';
	protected $description      = '画像を設置します。';
	protected $interface_width  = '500';
	protected $interface_height = '500';
	
	public $link_path           = FALSE;

	public function db()
	{
		$dbst = array(
			'block_id'        => array(
									'type'       => 'INT',
									'constraint' => 11,
									'key'        => TRUE
								),
			'file_id'         => array(
									'type'       => 'INT',
									'constraint' => 11,
									'default'    => 0
								),
			'hover_file_id'   => array(
									'type'       => 'INT',
									'constraint' => 11,
									'default'    => 0
								),
			'alt'             => array(
									'type'       => 'VARCHAR',
									'constraint' => 255,
									'null'       => TRUE
								),
			'link_type'       => array(
									'type'       => 'INT',
									'constraint' => 1,
									'default'    => 1
								),
			'link_to_page_id' => array(
									'type'       => 'INT',
									'constraint' => 11,
									'null'       => TRUE
								),
			'link_to'         => array(
									'type'       => 'VARCHAR',
									'constraint' => 255,
									'null'       => TRUE
								),
			'action_method'   => array(
									'type'       => 'VARCHAR',
									'constraint' => 255,
									'null'       => TRUE
								),
			'action_file_id'  => array(
									'type'       => 'INT',
									'constraint' => 11,
									'null'       => TRUE
								)
		);

		return array($this->table => $dbst);
	}

	public function get_action_methods()
	{
		return array(
			'0'          => '--',
			'thickbox' => 'Thickbox風に拡大',
			'lightbox' => 'Lightbox風に拡大',
			'milkbox'  => 'Milkbox風に拡大',
			'slbox'    => 'Sexy LightBox風に拡大'
		);
	}
	
	/**
	 * リンクパス生成
	 */
	public function get_link_path()
	{
		if ( $this->link_type < 1 && $this->link_to_page_id > 0 )
		{
			// generate link from page_id
			$this->link_path = get_page_link_by_page_id($this->link_to_page_id);
			return TRUE;
		}
		else if ( $this->link_type > 0 && !empty($this->link_to) )
		{
			// generate link from external link
			$this->link_path = $this->link_to;
			return TRUE;
		}
		return FALSE;
	}
	
	public function set_image_tag()
	{
		$path = $this->set_file_path($this->file_id);
		$img  = '<img src="' . $path . '" ';
		if ( $this->hover_file_id > 0 )
		{
			$img .= 'onmouseover="this.src=\'' . $this->set_file_path($this->hover_file_id) . '\';" '
					. 'onmouseout="this.src=\'' . $path . '\';" ';
		}
		$img .= 'alt="' . ((!empty($this->alt)) ? $this->alt : '') . '" />';
		return $img;
	}
	

	public function set_file_path($fid)
	{
		$sql   = 'SELECT file_name, crypt_name,extension FROM files WHERE file_id = ? LIMIT 1';
		$query = $this->ci->db->query($sql, array((int)$fid));

		if ($query->row())
		{
			$result = $query->row();
			return file_link() . 'files/' . $result->crypt_name . '.' . $result->extension;
		}
		return '';
	}

	public function set_action_file_path()
	{
		$this->ci->load->model('file_model');
		$file = $this->ci->file_model->get_file_data($this->action_file_id);

		return make_file_path($file, '', TRUE);
	}

	public function load_javascript()
	{
		if (!array_key_exists($this->action_method, $this->ci->additional_footer_javascript))
		{
			$this->ci->additional_footer_javascript[$this->action_method] = '<script type="text/javascript" src="' . base_url() . 'js/query_loader.js?lib=image_lib&amp;method=' . $this->action_method . '"></script>';
		}
	}
}
