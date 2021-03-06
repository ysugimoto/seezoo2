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
			$lib = ( ADVANCE_UA === 'ie6' ) ? 'flint.dev.min.js' : 'flint.dev2.js';
		}
		else if ( $mode === 'sp' )
		{
			$lib = 'flint.mobile.min.js';
		}
		
		if ( $lib )
		{
			return '<script type="text/javascript" src="' . file_link() . 'flint.php?mode=' . $workMode . '" charset="UTF-8"></script>' . "\n"
					.'<script type="text/javascript" src="' . file_link() . 'js/' . $lib . '" charset="UTF-8"></script>';
		}
		return '';
	}
}

// set_image : 画像パス生成
// @note IE6の場合、png->gifのコンバートが走る
if ( ! function_exists('set_image'))
{
	function set_image($filePath)
	{
		// replace file png to gif
		if ( ADVANCE_UA === 'ie6' && preg_match('/\.png$/u', $filePath) )
		{
			$ie6      = preg_replace('/\.png$/', '.gif', $filePath);
			$fileName = basename($ie6);
			if ( ! file_exists(FCPATH . 'files/ie6/' . $fileName) )
			{
				// convert image
				$source = imagecreatefrompng($ie6);
				imagepng($source, FCPATH . 'files/ie6/' . $fileName);
				imagedestroy($source);
			}
			$path = file_link() . 'files/ie6/' . $fileName;
		}
		else
		{
			$path = file_link() . 'images/' . $filePath;
		}
		return '<img src="' . $path . '" alt="" />';
	}
}

if ( ! function_exists('parse_path_segment'))
{
	function parse_path_segment($path, $before = FALSE)
	{
		$pos = strrpos($path, '/');

		if ( $pos === FALSE )
		{
			return ( $before === FALSE ) ? $path : '';
		}
		else
		{
			return ( $before === FALSE )
			         ? substr($path, $pos + 1)
			         : substr($path, 0, $pos);
		}
	}
}

// hour_list : 時間の配列を生成
if ( ! function_exists('hour_list'))
{
	function hour_list()
	{
		for ($i = 0; $i < 24; $i++)
		{
			$key = ($i < 10) ? '0' . $i : (string)$i;
			$list[$key] = $key;
		}
		return $list;
	}
}

// minute_list : 分の配列を生成
if ( ! function_exists('minute_list'))
{
	function minute_list()
	{
		for ($i = 0; $i < 60; $i++)
		{
			$key = ($i < 10) ? '0' . $i : (string)$i;
			$list[$key] = $key;
		}
		return $list;
	}
}

// set_public_datetime : DATETIME形式からフォーマットで出力
if ( ! function_exists('set_public_datetime'))
{
	function set_public_datetime($format, $datetime)
	{
		// PHP5.1.0以前はstrtotimeは-1を返却するのでここで吸収
		if ((int)strtotime($datetime) < 1)
		{
			return '';
		}
		return date($format, strtotime($datetime));
	}
}

