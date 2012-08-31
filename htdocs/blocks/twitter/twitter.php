<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ============================================================================
 * Seezoo twitter連携ブロックコントローラ
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */

class Twitter_block extends Block
{
	protected $table            = 'sz_bt_twitter_block';
	protected $block_name       = 'twitterブロック';
	protected $description      = 'twitterのつぶやきを表示します。';
	protected $interface_width  = 500;
	protected $interface_height = 500;

	public function db()
	{
		$dbst = array(
				'block_id'   => array(
									'type'       => 'INT',
									'constraint' => 11,
									'key'        => TRUE
								),
				'user_name'  => array(
									'type'       => 'VARCHAR',
									'constraint' => 255,
								),
				'view_type'  => array(
									'type'       => 'INT',
									'constraint' => 1,
									'default'    => 1
								),
				'view_limit' => array(
									'type'       => 'INT',
									'constraint' => 2,
									'default'    => 10
								)
		);

		return array($this->table => $dbst);
	}

	public function get_view_type_array()
	{
		$res = array(
			1 => '指定ユーザーのつぶやき',
			2 => '全タイムラインを表示'
		);

		return $res;
	}

	public function build_twitter_path()
	{
		$url = '#';
		if ((int)$this->view_type === 1)
		{
			$url = 'http://twitter.com/statuses/user_timeline/' . $this->user_name;
		}
		else if ((int)$this->view_type === 2)
		{
			$url = 'http://twitter.com/statuses/friends_timeline/' . $this->user_name;
		}

		return $url;
	}
	
	public function get_tweet_by_serverside()
	{
		$uri  = $this->build_twitter_path();
		$resp = http_request($uri . '.json');
		
		if ( ! $resp )
		{
			return FALSE;
		}
		$tweet = json_decode($resp);
		return ( count($tweet) > $this->view_limit )
		        ? array_slice($tweet, 0, $this->view_limit)
		        : $tweet;
	}
}