<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ============================================================================
 * Seezoo 記事エディタコントローラ
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 *
 */
class Textcontent_block extends Block
{
	public $property;
	public $ci;
	protected $table = 'sz_bt_textcontent';
	protected $block_name = '記事ブロック';
	protected $description = 'リッチエディタでコンテンツを作成します。';
	protected $block_path = 'blocks/textcontent/';
	protected $interface_width = 760;
	protected $interface_height = 500;

	protected $ignore_escape = array('body');

	function db()
	{
		$dbst = array(
			'block_id'		=> array(
								'type'			=> 'INT',
								'constraint'	=> 11,
								'key'			=> TRUE
								),
			'body'			=> array(
								'type'			=> 'TEXT'
								)
		);

		return array($this->table => $dbst);
	}

	function show_body()
	{
		$this->ci->load->model('sitemap_model');
		$grep = '/href=[\'"]?([^"]*)?[\'"]/';
		$out = str_replace('[base_url]', base_url(), $this->body); //ベースＵＲＬ追加 Y.Paku 2011.10.07
		$out = preg_replace_callback($grep, array($this, 'ignore_http'), $out);
	//	$out = html_entity_decode($out);
		$out = preg_replace_callback('/\[m:([0-9]+)\]/u', array($this, '_parse_emoji'), $out);
		return preg_replace(array('/<br>|<br\/>|<br\s\/>/', '/&amp;/'), array('<br />' . "\n", '&'), $out);
	}

	function ignore_http($match)
	{
		if ( preg_match('/^mailto|^javascript/u', $match[1]) )
		{
			return $match[0];
		}
		else if (preg_match('/^http/', $match[1]))
		{
			if (strpos($match[1], file_link()) !== FALSE)
			{
				return $match[0];
			}
			else
			{
				// if link target URL have not your site, open with new tab or window.
				return 'target="_blank" ' . $match[0];
			}

		} else if (preg_match('/^tel/', $match[1])) {
			return $match[0];
		} else {
			if ( $this->ci->sitemap_model->is_ssl_page_path($match[1]) )
			{
				return 'href="' . ssl_page_link($match[1]) . '"';
			}
			else
			{
				return 'href="' . page_link($match[1]) . '"';
			}
		}
	}

	function save($data)
	{
		return parent::save($data);
	}

	function _parse_emoji($match)
	{
		$num = (int)$match[1];
		$mobile = Mobile::get_instance();
		return $mobile->convert_emoji($num);
	}
}
