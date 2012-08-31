<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ============================================================================
 * 
 * Seezoo Flash設置ブロックコントローラ
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ============================================================================
 */
class Flash_block extends Block
{
	public $ci;
	protected $description = 'Flashムービーを設置します。';
	protected $block_name = 'Flash設置ブロック';
	protected $block_path = 'blocks/flash/';
	protected $table = 'sz_bt_flash';
	protected $interface_width = 500;
	protected $interface_height = 500;
	protected $enable_sp = FALSE;

	public function db()
	{
		$dbst = array(
				'block_id' => array(
									'key'        => TRUE,
									'constraint' => 11,
									'type'       => 'INT'
									),
				'file_id' => array(
									'type'       => 'INT',
									'constraint' => 11,
									)
		);

		return array($this->table => $dbst);
	}

	protected function _get_file()
	{
		$this->ci->load->model('file_model');
		return $this->ci->file_model->get_file_data($this->file_id);
	}
	
	public function generate_object_element()
	{
		$file = $this->_get_file();
		if (!$file)
		{
			return '<span>ファイルが見つかりませんでした。</span>';
		}
		list($w, $h) = @getimagesize(FCPATH . 'files/' . $file->crypt_name . '.' . $file->extension);
		$param_array = array(
			'wmode'   => 'transparent',
			'quality' => 'high',
			'menu'    => 'false',
			'scale'   => 'noScale',
			'salign'  => 'lt',
			'width'   => $w,
			'height'  => $h
		);
		return load_flash(file_link() . 'files/' . $file->crypt_name . '.' . $file->extension, $param_array);
	}
	
	protected function _check_browser_IE()
	{
		return (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) ? TRUE : FALSE;
	}
}