<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ============================================================================
 * Seezoo ファイルダウンロードブロックコントローラ
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */
class File_download_block extends Block
{
	protected $description      = 'ファイルをダウンロードさせるブロックを設置します。';
	protected $block_name       = 'ファイルダウンロードブロック';
	protected $block_path       = 'blocks/file_download/';
	protected $table            = 'sz_bt_file_download';
	protected $intergace_width  = 500;
	protected $interface_height = 500;

	public function db()
	{
		$dbst = array(
				'block_id' => array(
									'key'        => TRUE,
									'constraint' => 11,
									'type'       => 'INT'
									),
				'file_id'  => array(
									'type'       => 'INT',
									'constraint' => 11,
									),
				'dl_text'  => array(
									'type'       => 'VARCHAR',
									'constraint' => 255,
									'null'       => TRUE
									)
		);

		return array($this->table => $dbst);
	}
	
	function setup_download()
	{
		$file = $this->_get_file();
		if (!$file)
		{
			return '<p>ファイルが見つかりませんでした。</p>';
		}
		$txt = (empty($this->dl_text))
				  ? $file->file_name . '.' . $file->extension
				  : $this->dl_text;
		return anchor($this->set_action_path(), $txt);
	}

	function action($token)
	{
		$this->ci->load->helper('download_helper');
		$file =$this->_get_file();
		$this->_update_dl_count($file->file_id, $file->download_count);
		
		// configure filesize
		// If download file size is larger than memory_limit on php.ini,
		// try split
		$name = $file->file_name . '.' . $file->extension;
		force_download_reg($name, make_file_path($file));
		exit;
	}

	protected function _get_file()
	{
		$this->ci->load->model('file_model');
		return $this->ci->file_model->get_file_data($this->file_id);
	}

	function save($data)
	{
		parent::save($data);
	}
	
	protected function _update_dl_count($fid, $cnt)
	{
		$this->ci->db->where('file_id', $fid);
		$this->ci->db->update('files', array('download_count' => (int)$cnt + 1));
	}
}