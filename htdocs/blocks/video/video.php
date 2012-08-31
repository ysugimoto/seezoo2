<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ============================================================================
 * Seezoo Video Player Block Controller
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */

class Video_block extends Block
{
	protected $table            = 'sz_bt_video';
	protected $block_name       = 'ビデオプレイヤーブロック';
	protected $description      = '外部ビデオを再生します。';
	protected $interface_width  = 500;
	protected $interface_height = 500;
	protected $enable_mb        = FALSE;
	
	public function db()
	{
		$dbst = array(
				'block_id'       => array(
										'type'       => 'INT',
										'constraint' => 11,
										'key'        => TRUE
									),
				'file_id'        => array(
										'type'       => 'INT',
										'constraint' => 11
									),
				'display_width'  => array(
										'type'       => 'INT',
										'constraint' => 5
									),
				'display_height' => array(
										'type'       => 'INT',
										'constraint' => 5
									)
		);
		
		return array($this->table => $dbst);
	}
	
	public function is_edit_mode()
	{
		return (isset($this->ci->edit_mode) && $this->ci->edit_mode === 'EDIT_SELF') ? TRUE : FALSE;
	}
	
	public function set_up()
	{
		$this->ci->load->model('file_model');
		$this->file = $this->ci->file_model->get_file_data($this->file_id);
	}
	
	public function set_video()
	{
		$path = 'blocks/video/seezoo_video_player.swf';
		if (file_exists(SZ_EXT_PATH . $path))
		{
			$path = file_link() . SZ_EXT_DIR . $path;
		}
		else 
		{
			$path = file_link() . $path;
		}
		$object = load_flash(
							$path . '?key=' . sha1(microtime()),
							array(
								'wmode'             => 'transparent',
								'FlashVars'         => 'moviePath=' . make_file_path($this->file, '', TRUE),
								'allowScriptAccess' => 'always',
								'allowFullScreen'   => 'true',
								'salign'            => '',
								'align'             => 'middle',
								'scale'             => 'showall',
								'loop'              => 'false',
								'width'             => $this->display_width,
								'height'            => $this->display_height,
								'id'                =>'sz_video_block_' . $this->block_id
							)
						);
		return $object;
	}
}