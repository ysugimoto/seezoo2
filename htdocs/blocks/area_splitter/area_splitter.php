<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  ====================================================================================
 * Seezoo Area Splitter Block
 * 
 * @package Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * =====================================================================================
 */

class Area_splitter_block extends Block
{
	protected $block_name       = '編集エリア分割ブロック';
	protected $table            = 'sz_bt_area_splitter';
	protected $sub_table        = 'sz_bt_area_splitter_relation';
	protected $description      = '編集エリアを分割します。';
	protected $interface_width  = 500;
	protected $interface_height = 500;
	
	protected $multi_column     = TRUE;
	
	// for add or edit border-offset
	protected $border_offset    = 2;
	
	/**
	 * 使用DB定義
	 */
	public function db()
	{
		$dbst = array(
			'block_id'        => array(
							'type'       => 'INT',
							'constraint' => 11
						),
			'as_relation_key' => array(
							'type'       => 'VARCHAR',
							'constraint' => 255,
							'default'    => 0
						)
		);
		$dbst_sub = array(
			'as_relation_key' => array(
								'type'       => 'VARCHAR',
								'constraint' => 255,
								'default'    => 0
							),
			'contents_name'   => array(
								'type'       => 'VARCHAR',
								'constraint' => 255,
								'default'    => 0
							),
			'contents_per'   => array(
								'type'       => 'INT',
								'constraint' => 3,
								'default'    => 0
							) 
		);
		
		return array($this->table => $dbst, $this->sub_table => $dbst_sub);
	}
	
	/**
	 * ブロックデータ保存
	 * @override Block::save
	 * @param array $data
	 */
	public function save($data)
	{
		$key  = $this->generate_key();
		$CI   =& get_instance();
		$pers = $CI->input->post('as_contents_pers');
		
		// create sub data
		foreach ($CI->input->post('as_contents_name') as  $i => $value)
		{
			$sub_data = array(
				'as_relation_key' => $key,
				'contents_name'   => $value,
				'contents_per'    => (isset($pers[$i])) ? (int)$pers[$i] : 0
			);
			$CI->db->insert($this->sub_table, $sub_data);
		}

		// create master data
		$args = array(
			'block_id'        => $data['block_id'],
			'as_relation_key' => $key
		);

		parent::save($args);
	}

	/**
	 * this block has db relations. so that, manual duplicate in override super class
	 * @override Block::duplicate
	 */
	public function duplicate()
	{
		$new_key = $this->generate_key();
		// update key
		$this->_block_record['as_relation_key'] = $new_key;

		// duplicate base DB
		$new_bid = parent::duplicate();

		// duplicate [relations duplicate]
		$sql   = 'SELECT * FROM ' . $this->sub_table . ' WHERE as_relation_key = ?';
		$query = $this->ci->db->query($sql, array($this->as_relation_key));

		foreach ($query->result_array() as $v)
		{
			$v['as_relation_key'] = $new_key;
			$this->ci->db->insert($this->sub_table, $v);
		}

		return $new_bid;
	}
	
	/**
	 * fix browser for IE6.
	 * IE6 has bug which total 100% float box is not correctly rendaring.
	 * so that, we make total 99% float box ON IE6. 
	 * @access public
	 */
	public function fix_browser()
	{
		return (defined('ADVANCE_UA') && ADVANCE_UA == 'ie6') ? 99 : 100;
	}
	
	/**
	 * get_areas
	 * @access public
	 */
	public function get_areas()
	{
		$sql   = 'SELECT * FROM ' . $this->sub_table . ' WHERE as_relation_key = ?';
		$query = $this->ci->db->query($sql, array($this->as_relation_key));

		foreach ($query->result() as $v)
		{
			$ret[] = $v;
		}

		return $ret;
	}
	
	/**
	 * set_edit_width
	 */
	public function set_edit_width($v)
	{
		return 450 * ($v / 100) - $this->border_offset;
	}

	/**
	 * generate relation key hash
	 * @access public
	 * @return string
	 */
	public function generate_key()
	{
		return sha1(microtime());
	}
}
