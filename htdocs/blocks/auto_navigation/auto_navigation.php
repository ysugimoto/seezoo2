<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ============================================================================
 * Seezoo オートナビゲーション設置ブロックコントローラ
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto<neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */
class Auto_navigation_block extends Block
{
	protected $table            = 'sz_bt_auto_navigation';
	protected $block_name       = 'オートナビゲーションブロック';
	protected $description      = 'サイト構造に合わせて自動リンクを生成します。';
	protected $interface_width  = 500;
	protected $interface_height = 500;


	public function db()
	{
		$dbst = array(
			'block_id'              => array(
											'type'       => 'INT',
											'constraint' => 11,
											'key'        => TRUE
										),
			'sort_order'            => array(
											'type'       => 'INT',
											'constraint' => 2,
											'default'    => 1
										),
			'based_page_id'         => array(
											'type'       => 'INT',
											'constraint' => 11,
											'default'    => 1
										),
			'subpage_level'         => array(
											'type'       => 'VARCHAR',
											'constraint' => 60,
											'default'    => 1
										),
			'manual_selected_pages' => array(
											'type'       => 'VARCHAR',
											'constraint' => 255,
											'default'    => 0
										),
			'handle_class'          => array(
											'type'       => 'VARCHAR',
											'constraint' => 255,
											'null'       => TRUE
										),
			'display_mode'          => array(
											'type'       => 'INT',
											'constraint' => 2,
											'default'    => 1
										),
			'current_class'         => array(
											'type'       => 'VARCHAR',
											'constraint' => 255,
											'default'    => ''
										),
			'show_base_page'        => array(
											'type'       => 'INT',
											'constraint' => 1,
											'default'    => 0
										)
		);

		return array($this->table => $dbst);
	}

	public function get_sort_order_list()
	{
		$res = array(
			1 => 'サイトマップ登録順',
			2 => 'サイトマップ登録逆順',
			3 => 'アルファベット昇順',
			4 => 'アルファベット降順'
		);

		return $res;
	}

	public function get_display_page_levels()
	{
		// get max display_page_level
		$sql    = 'SELECT MAX(display_page_level) as dpl FROM page_versions LIMIT 1';
		$query  = $this->ci->db->query($sql);
		$result = $query->row();
		$dpl    = $result->dpl;

		if ($dpl == 0)
		{
			$dpl = 1;
		}
		for ($i = 1; $i <= $dpl; $i++)
		{
			$ret[$i] = $i;
		}
		$ret['9999'] = '全て';

		return $ret;

	}

	public function get_display_mode()
	{
		return array(
			1 => '縦並び（デフォルト）',
			2 => '横並び（メニュー用）',
			3 => 'パンくずナビ'
		);
	}

	public function set_display_mode_text()
	{
		switch ((int)$this->display_mode)
		{
			case 1  : return '通常の縦並びメニューです。';
			case 2  : return 'グローバルメニュー用に横並びに配置します。';
			case 3  : return 'パンくずナビを表示します。';
			default : return '';
		}
	}

	protected function _get_sort_order_list_db($sid)
	{
		$res = array(
			1 => 'ORDER BY display_order ASC',
			2 => 'ORDER BY display_order DESC',
			3 => 'ORDER BY page_title ASC',
			4 => 'ORDER BY page_title DESC'
		);

		return $res[$sid];
	}

	public function generate_navigation()
	{
		// same logic  ajax/generate_navigation.
		$this->ci->load->model('sitemap_model');
		$this->ci->load->helper('ajax_helper');

		$sub_level = $this->subpage_level;

		// strict sub page level
		if ($sub_level == 9999)
		{
			$sub_level = $this->ci->sitemap_model->get_max_display_level_all();
		}

		if ($sub_level == 0)
		{
			$sub_level = 1;
		}
		// set sort_order
		$ob = (int)$this->sort_order;

		if ($ob === 2) {
			$order_by = ', display_order DESC';
		} else if ($ob === 3) {
			$order_by = ', page_title ASC';
		} else if ($ob === 4) {
			$order_by = ', page_title DESC';
		} else {
			$order_by = ', display_order ASC';
		}

		// set display_mode
		$dpm = (int)$this->display_mode;

		if ($dpm === 1) {
			$dpm_class = 'v_nav';
		} else if ($dpm === 2) {
			$dpm_class = 'h_nav';
		} else if ($dpm === 3) {
			$dpm_class = 'sz_breadcrumb';
		}

		if ($dpm < 3)
		{
			$ret = $this->ci->sitemap_model->get_auto_navigation_data(array('page_id' => $this->based_page_id, 'show_base_page' => $this->show_base_page), $sub_level, $ob, TRUE);
		}
		else
		{
			$ret = $this->ci->sitemap_model->get_navigation_breadcrumb($this->ci->page_id, TRUE);
		}

		return $ret;

	}

	public function get_display_mode_class()
	{
		$dpm = (int)$this->display_mode;

		if ($dpm === 1) {
			$dpm_class = 'v_nav';
		} else if ($dpm === 2) {
			$dpm_class = 'h_nav';
		} else if ($dpm === 3) {
			$dpm_class = 'sz_breadcrumb';
		}

		return $dpm_class;
	}
}
