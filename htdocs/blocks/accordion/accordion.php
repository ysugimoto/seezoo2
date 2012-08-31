<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ============================================================================
 * Seezoo 開閉コンテンツブロックコントローラ
 * @package Seezoo Plugin
 * @author Yoshiaki Sugimoto<neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */
class Accordion_block extends Block
{
	protected $table            = 'sz_bt_accordion';
	protected $block_name       = 'アコーディオンブロック';
	protected $description      = '見出し+開閉可能なエリアを挿入します。';
	protected $interface_width  = 500;
	protected $interface_height = 500;
	protected $enable_mb        = FALSE;
	
	protected $multi_column     = TRUE;

	public function db()
	{
		$dbst = array(
			'block_id'		=> array(
								'type'       => 'INT',
								'constraint' => 11,
								'key'        => TRUE
							),
			'head_level'	=> array(
								'constraint' => 2,
								'type'	      => 'VARCHAR'
							),
			'head_text'	=> array(
								'constraint' => 255,
								'type'       => 'VARCHAR'
							),
			'description_area_name' => array(
								'type'       => 'VARCHAR',
								'constraint' => 255
							),
			'open_animate'	=> array(
								'type'       => 'tinyint',
								'constraint' => 1,
								'default'    => 0
							)
		);

		return array($this->table => $dbst);
	}

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
			$out[] = 'class="ac_head ' . $this->class_name . '"';
		}
		else 
		{
			$out[] = 'class="ac_head"';
		}

		$out[] = '>' . $this->head_text . '</' . $head[$this->head_level] . '>';

		return implode(' ', $out);
	}
	
	public function build_description()
	{
		$out[] = '<div class="sz_accordion_body">';
		$out[] = nl2br(prep_str($this->description_text));
		$out[] = '</div>';
		
		return implode("\n", $out);
	}
}