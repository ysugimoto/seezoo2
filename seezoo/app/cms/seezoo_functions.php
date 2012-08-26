<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

/**
 * ===============================================================================
 *
 * CMS過去互換用ヘルパ関数
 *
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 *
 * ===============================================================================
 */
if ( ! function_exists('file_link') )
{
	function file_link($path = '')
	{
		return get_config('base_url') . $path;
	}
}
