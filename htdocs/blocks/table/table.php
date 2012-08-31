<?php

/**
 * ===========================================================================
 * 
 * Seezoo Table Block Plugin
 * 
 * @package Seezoo Plugins
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * 
 * ===========================================================================
 */

class Table_block extends Block
{
	protected $block_name = '表ブロック';
	protected $description = '表を作成します。';
	protected $table = 'sz_bt_table';
	protected $interface_width = 760;
	protected $interface_height = 500;
	
	protected $ignore_escape = array('table_data');
	
	public function db()
	{
		$dbst = array(
			'block_id'		=> array(
													'type'		=> 'INT',
													'constraint'	=> 11,
													'key' => TRUE
												),
			'table_data'	=> array(
													'type'		=> 'TEXT',
													'null'		=> TRUE
												)
		);
		
		return array($this->table => $dbst);
	}
	
	public function set_tag_names()
	{
		foreach (array('td', 'th') as $tag )
		{
			$data[$tag] = $tag;
		}
		return $data;
	}
	
	public function save($args)
	{
		$data = array(
			'block_id'		=> $args['block_id'],
			'table_data'	=> $args['table_data']
		);
		
		parent::save($data);
	}
	
	public function get_table_string()
	{
		return $this->_format_table_tag($this->table_data);
	}
	
	private function _format_table_tag($table)
	{
		// First, tag and attribute,style property to lowercase
		$table = preg_replace_callback(
								'#<([^>]+)>#us',
								create_function('$match', ' return strtolower($match[0]);'),
								$table
								);
		// Second, remove "default" in td classname
		$table = preg_replace('#<(td|tr|tbody)\s?([^>+]*)>#us', '<$1>', $table);
		// Third, remove JaaScript marking properties
		$table = preg_replace('#__[exc]{1,2}id="([0-9]+)"#us', '', $table);
		
		return $table;
	}
	
	protected function _generate_table()
	{
	}
}