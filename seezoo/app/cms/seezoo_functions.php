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

if ( ! function_exists('db_datetime') )
{
	function db_datetime()
	{
		return date('Y-m-d H:i:s');
	}
}

if ( ! function_exists('xml_define') )
{
	function xml_define()
	{
		$request = Seezoo::getRequest();
		$ua      = $request->server('HTTP_USER_AGENT');
		
		// Except, IE7 IE6 outputs the XML declaration
		return ( $ua && ! preg_match('/msie\s[6|7]\.0/', strtolower($ua)) )
		         ? '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
		         : '';
	}
}

if ( ! function_exists('area_create') )
{
	function area_create($name, $param = array())
	{
		$area = new Area($name);
		return $area->show($param);
	}
}

if ( ! function_exists('write_favicon') )
{
	function write_favicon($status = FALSE)
	{
		if ( file_exists(ROOTPATH . 'files/favicon/favicon.ico') )
		{
			if ( ! $status )
			{
				return '<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="' . file_link() . 'files/favicon/favicon.ico" />' . "\n"
						. '<link rel="icon" type="image/vnd.microsoft.icon" href="' . file_link() . 'files/favicon/favicon.ico" />';
			}
			else {
				return '<span>faviconが設定されています</span>';
			}
		}
		else
		{
			return ( ! $status ) ? '' : 'なし';
		}
	}
}

if ( ! function_exists('flint_exeute') )
{
	function flint_execute($workMode = 0)
	{
		$mode = get_config('final_output_mode');
		$lib  = FALSE;
		
		if ( $mode === 'pc' || $CI->is_login === TRUE )
		{
			$lib = ( ADVANCE_UA === 'ie6' ) ? 'flint.dev.min.js' : 'flint.dev2.min.js';
		}
		else if ( $mode === 'sp' )
		{
			$lib = 'flint.mobile.min.js';
		}
		
		if ( $lib )
		{
			return '<script type="text/javascript" src="' . file_link() . 'flint.php?m=' . $workMode . '" charset="UTF-8"></script>' . "\n"
					.'<script type="text/javascript" src="' . file_link() . 'js/' . $lib . '" charset="UTF-8"></script>';
		}
		return '';
	}
}

