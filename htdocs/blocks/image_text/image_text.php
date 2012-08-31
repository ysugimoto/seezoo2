<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ============================================================================
 * Seezoo Text_image Controller
 * image + text on block
 * selectable image-right or image-left
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *============================================================================
 */
class Image_text_block extends Block
{
	protected $table            = 'sz_bt_image_text';
	protected $block_name       = 'テキスト＋画像ブロック';
	protected $description      = 'テキスト＋画像を並べて表示します。';
	protected $interface_width  = 500;
	protected $interface_height = 500;

	public function db()
	{
		$dbst = array(
			'block_id'   => array(
									'type'       => 'INT',
									'constraint' => 11,
									'key'        => TRUE
								),
			'file_id'    => array(
									'type'       => 'INT',
									'constraint' => 11,
								),
			'text'       => array(
									'type'       => 'TEXT'
								),
			'float_mode' => array(
									'type'       => 'VARCHAR',
									'constraint' => 10,
									'default'    => 'left'
								)
		);

		return array($this->table => $dbst);
	}

	public function save($args)
	{
		parent::save($args);
	}

	public function get_float_mode_list()
	{
		return array(
			'left'  => '左画像＋右テキスト',
			'right' => '右画像＋左テキスト'
		);
	}

	public function set_float($reverse = FALSE)
	{
		if ($reverse)
		{
			return $this->float_mode;
		}
		else
		{
			return ($this->float_mode == 'left') ? 'right' : 'left';
		}
	}

	public function show_file()
	{
		$this->ci->load->model('file_model');
		$file = $this->ci->file_model->get_file_data($this->file_id);
		return make_file_path($file, '', TRUE);
	}

}