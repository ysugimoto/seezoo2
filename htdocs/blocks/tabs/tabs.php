<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ============================================================================
 * Seezoo タブコンテンツブロックコントローラ
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */
class Tabs_block extends Block
{
	protected $table            = 'sz_bt_tab_contents';
	protected $sub_table        = 'sz_bt_tab_relations';
	protected $block_name       = 'タブコンテンツブロック';
	protected $description      = 'タブ切り替え可能なブロックを生成します。';
	protected $interface_width  = 500;
	protected $interface_height = 400;
	protected $enable_mb        = FALSE;
	
	protected $multi_column     = TRUE;
	
	// public tabs (use view)
	public $tabs = array();

	public function db()
	{
		$dbst = array(
			'block_id'         => array(
										'type'       => 'INT',
										'constraint' => 11
									),
			'tab_relation_key' => array(
										'type'       => 'VARCHAR',
										'constraint' => 255,
										'default'    => 0
									),
			'single_contents'  => array(
										'type'       => 'INT',
										'constraint' => 1,
										'default'    => 1
									),
			'link_inner'       => array(
										'type'       => 'INT',
										'constraint' => 1,
										'default'    => 0
									)
		);

		$dbst_sub = array(
			'tab_relation_id'  => array(
										'type'           => 'INT',
										'constraint'     => 1,
										'key'            => TRUE,
										'auto_increment' => TRUE
									),
			'tab_relation_key' => array(
										'type'           => 'VARCHAR',
										'constraint'     => 255,
										'default'        => 0
									),
			'contents_name'    => array(
										'type'           => 'VARCHAR',
										'constraint'     => 255,
										'default'        => 0
									)
		);

		return array($this->table => $dbst, $this->sub_table => $dbst_sub);
	}

	public function save($data)
	{
		$key = $this->generate_key();
		$CI  =& get_instance();

		foreach ($CI->input->post('tab_contents_name') as $value)
		{
			$CI->db->insert($this->sub_table, array('tab_relation_key' => $key, 'contents_name' => $value));
		}

		// create master data
		$args = array(
			'block_id'         => $data['block_id'],
			'tab_relation_key' => $key,
			'single_contents'  => $data['single_contents'],
			'link_inner'       => (int)$data['link_inner']
		);

		parent::save($args);
	}

	/*
	 * this block has db relations. so that, manual duplicate in override super class
	 */
	public function duplicate()
	{
		$new_key = $this->generate_key();
		// update key
		$this->_block_record['tab_relation_key'] = $new_key;

		// duplicate base DB
		$new_bid = parent::duplicate();

		// duplicate [relations duplicate]
		$sql   = 'SELECT * FROM ' . $this->sub_table . ' WHERE tab_relation_key = ?';
		$query = $this->ci->db->query($sql, array($this->tab_relation_key));

		foreach ($query->result_array() as $v)
		{
			$v['tab_relation_key'] = $new_key;
			unset($v['tab_relation_id']);
			$this->ci->db->insert($this->sub_table, $v);
		}

		return $new_bid;
	}

	public function setup_tab()
	{
		$sql   = 'SELECT * FROM ' . $this->sub_table . ' WHERE tab_relation_key = ?';
		$query = $this->ci->db->query($sql, array($this->tab_relation_key));

		foreach ($query->result() as $v)
		{
			$ret[] = $v->contents_name;
		}

		$this->tabs = $ret;
	}

	public function is_edit_mode()
	{
		return (isset($this->ci->edit_mode) && $this->ci->edit_mode === 'EDIT_SELF') ? TRUE : FALSE;
	}

	public function generate_key()
	{
		return sha1(microtime());
	}
	
}