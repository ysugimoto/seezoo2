<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ============================================================================
 * Seezoo 見出しタグ出力ブロックコントローラ
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto<neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */
class Head_block extends Block
{
	protected $table            =  'sz_bt_head_block';
	protected $block_name       = ' 見出しブロック';
	protected $description      = '見出しを挿入します。';
	protected $interface_width  = 500;
	protected $interface_height = 500;

	public function db()
	{
		$dbst = array(
			'block_id'        => array(
									'type'       => 'INT',
									'constraint' => 11,
									'key'        => TRUE
								),
			'head_level'      => array(
									'constraint' => 2,
									'type'       => 'VARCHAR'
								),
			'class_name'      => array(
									'constraint' => 255,
									'type'       => 'VARCHAR'
								),
			'text'            => array(
									'constraint' => 255,
									'type'       => 'VARCHAR'
								),
			'content_type'    => array(
									'constraint' => 1,
									'type'       => 'INT',
									'default'    => 0
								),
			'content_file_id' => array(
									'constraint' => 11,
									'type'       => 'INT'
								),
			'alt_text'        => array(
									'constraint' => 255,
									'type'       => 'INT'
								),
			'link_page_id'    => array(
									'constraint' => 11,
									'type'       => 'INT',
									'default'    => 0
								)
		);

		return array($this->table => $dbst);
	}
	
//	public function save($arg)
//	{
////		var_dump($arg);
////		var_dump($_POST);
////		exit;
//	}

	public function get_head_list()
	{
		return array(
			1 => 'h1',
			2 => 'h2',
			3 => 'h3',
			4 => 'h4',
			5 => 'h5',
			6 => 'h6'
		);
	}
	
	public function get_head_list_display()
	{
		return array(
			1 => '大見出し(h1)',
			2 => '中見出し(h2)',
			3 => '小見出し(h3)',
			4 => '小々見出し(h4)',
			5 => '小々々見出し(h5)',
			6 => '小々々々見出し(h6)'
		);
	}

	public function build_head()
	{
		$head  = $this->get_head_list();
		$out[] = '<' . $head[$this->head_level];

		if (!empty($this->class_name))
		{
			$out[] = 'class="' . $this->class_name . '"';
		}
		
		// output must be an link?
		$prefix = ( $this->link_page_id > 0 ) ? '<a href="' . get_page_link_by_page_id($this->link_page_id) . '">' : '';
		$suffix = ( $this->link_page_id > 0 ) ? '</a>' : '';
		
		$out[]  = '>' . $prefix . $this->_get_text() . $suffix . '</' . $head[$this->head_level] . '>';

		return implode(' ', $out);
	}
	
	private function _get_text()
	{
		// text content
		if ( $this->content_type < 1 )
		{
			return $this->text;
		}
		// image file
		return '<img src="' . file_link() . get_file($this->content_file_id, TRUE)
				. '" alt="' . ((empty($this->alt_text)) ? '' : $this->alt_text) . '" />';
	}
}
