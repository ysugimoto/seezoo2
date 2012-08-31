<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ============================================================================
 * Seezoo ブログ情報表示ブロックコントローラ
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 *
 */
class Blogupdates_block extends Block
{
	protected $table            = 'sz_bt_bloginfo';
	protected $block_name       = 'ブログRSS表示ブロック';
	protected $description      = 'ブログの更新情報を表示します。';
	protected $block_path       = 'blocks/bloginfo/';
	protected $interface_width  = 500;
	protected $interface_height = 500;
	
	protected $enabled;

	function db()
	{
		$dbst = array(
			'block_id'              => array(
											'type'       => 'INT',
											'constraint' => 11,
											'key'        => TRUE
											),
			'category'              => array(
											'type'       => 'INT',
											'constraint' => 11,
											'default'    => 0
											),
			'view_count'            => array(
											'type'       => 'INT',
											'constraint' => 3,
											'default'    => 10
											),
			'accept_comment_only'   => array(
											'type'       => 'TINYINT',
											'constraint' => 1,
											'default'    => 0
											),
			'accept_trackback_only' => array(
											'type'       => 'TINYINT',
											'constraint' => 1,
											'default'    => 0
											),
			'posted_user'           => array(
											'type'       => 'INT',
											'constraint' => 11,
											'default'    => 0
											)
		);

		return array($this->table => $dbst);
	}
	
	/**
	 * ブログデータ取得
	 */
	public function get_blog_data()
	{
		$this->ci->load->helper('blog_helper');
		$sql[] = 'SELECT sz_blog_id, title, body, entry_date, permalink FROM sz_blog WHERE 1';
		$bind  = array();
		
		// filter condition add
		if ( $this->category > 0 )
		{
			$sql[]  = 'AND sz_blog_category_id = ?';
			$bind[] = (int)$this->category;
		}
		if ( $this->accept_comment_only > 0 )
		{
			$sql[] = 'AND is_accept_comment = 1';
		}
		if ( $this->accept_trackback_only > 0 )
		{
			$sql[] = 'AND is_accept_trackback = 1';
		}
		if ( $this->posted_user > 0 )
		{
			$sql[]  = 'AND user_id = ?';
			$bind[] = (int)$this->posted_user;
		}
		// filter end
		
		$sql[]  = 'AND is_public = 1';
		$sql[]  = 'AND show_datetime <= NOW()';
		$sql[]  = 'ORDER BY entry_date DESC';
		$sql[]  = 'LIMIT ?';
		$bind[] = (int)$this->view_count;
		
		$query  = $this->ci->db->query(implode(' ', $sql), $bind);
		
		if ( $query && $query->num_rows() > 0 )
		{
			return $query->result();
		}
		return array();
	}
	
	/**
	 * ブログが有効になっているかチェック
	 */
	public function is_blog_enabled()
	{
		if ( ! is_null($this->enabled) )
		{
			return $this->enabled;
		}
		$sql =
			'SELECT '
			.	'1 '
			.'FROM '
			.	'blog_info '
			.'WHERE '
			.	'is_enable = 1 '
			.'LIMIT 1';
		$query = $this->ci->db->query($sql);
		
		$this->enabled = ( $query && $query->row() ) ? TRUE : FALSE; 
		return $this->enabled;
	}
	
	/**
	 * 登録されているカテゴリ一覧を取得
	 */
	public function get_blog_categories()
	{
		$ret = array(0 => 'すべて');
		$sql =
			'SELECT '
			.	'sz_blog_category_id, '
			.	'category_name '
			.'FROM '
			.	'sz_blog_category '
			.'WHERE '
			.	'is_use = 1';
		$query = $this->ci->db->query($sql);
		
		if ( $query && $query->num_rows() > 0 )
		{
			foreach ( $query->result() as $v)
			{
				$ret[$v->sz_blog_category_id] = $v->category_name;
			}
		}
		return $ret;
	}
	
	/**
	 * ユーザー一覧を取得
	 * Enter description here ...
	 */
	public function get_blog_posted_users()
	{
		$ret = array(0 => 'すべて');
		$sql =
			'SELECT '
			.	'user_id, '
			.	'user_name '
			.'FROM '
			.	'users as U '
			.'WHERE '
			.	'EXISTS ( '
			.		'SELECT '
			.			'1 '
			.		'FROM '
			.			'sz_blog '
			.		'WHERE '
			.			'user_id = U.user_id '
			.	')';
		$query = $this->ci->db->query($sql);
		
		if ( $query && $query->num_rows() > 0 )
		{
			foreach ( $query->result() as $v)
			{
				$ret[$v->user_id] = $v->user_name;
			}
		}
		return $ret;
	}
	
}