<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ============================================================================
 * Seezoo HTMLコンテンツ挿入ブロックコントローラ
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto<neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */
class Html_block extends Block
{
	protected $table            = 'sz_bt_htmlcontent';
	protected $block_name       = 'HTMLブロック';
	protected $description      = 'HTMLコード挿入します。';
	protected $interface_width  = 500;
	protected $interface_height = 500;
	
	protected $ignore_escape = array('body');
	
	public function db()
	{
		$dbst = array(
			'block_id' => array(
								'type'       => 'INT',
								'constraint' => 11,
								'key'        => TRUE
							),
			'body'     => array(
								'type'       => 'TEXT'
							)
		);
		
		return array($this->table => $dbst);
	}
}