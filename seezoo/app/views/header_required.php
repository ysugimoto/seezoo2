<?php  if ( ! defined('SZ_EXEC')) exit('No direct script access allowed');

$output         = array();
$output_mode    = get_config('final_output_mode');
$output_carrier = get_config('final_output_carrier');



if ( ($output_carrier === 'au' || $output_carrier === 'softbank')
     || ($output_mode === 'pc' || $output_mode === 'sp')
     || ( $output_mode === 'mb' && $this->is_login ) )
{
	$output[] = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
	$output[] = '<meta http-equiv="Content-Style-Type" content="text/css; charset=UTF-8" />';
	$output[] = '<meta http-equiv="Content-Script-Type" content="text/javascript; charset=UTF-8" />';

	if ($output_carrier === 'au' || $output_carrier === 'softbank' )
	{
		$output[] = '<meta http-equiv="pragma" content="no-cache" />';
		$output[] = '<meta http-equiv="cache-control" content="no-cache" />';
		$output[] = '<meta http-equiv="expires" content="0" />';
	}

	if ( $output_mode === 'sp' )
	{
		$output[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
		$output[] = '<meta name="format-detection" content="telephone=no" />';
	}
}
else if ( $output_mode === 'mb' && $output_carrier === 'docomo' )
{
	$output[] = '<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=Shift_JIS" />';
	$output[] = '<meta http-equiv="Cache-Control" content="no-cache" />';
	$output[] = '<meta http-equiv="Pragma" content="no-cache" />';
	$output[] = '<meta http-equiv="Expires" content="-1" />';
}

$output[] = write_favicon();

if ( ! $seezoo->is_preview )
{
	// create title
	// If Meta-Title is exists, use this as Page-Title.
	$page_title = ( ! empty($seezoo->page->meta_title) )
	                ? $seezoo->page->meta_title
	                : $seezoo->page->page_title;

	// set default title
	$detail_title = '';

	// $page_type assigned?
	if ( isset($page_type) )
	{
		switch( $page_type )
		{
			case 'search' :
				$detail_title = sprintf('「%s」の検索結果', $display_query);
				break;
			case 'author' :
				$detail_title = sprintf('%sのアーカイブ', $user_name);
				break;
			case 'category' :
				$detail_title = sprintf('%sのアーカイブ', $category_list[$category_id]);
				break;
			case 'postdate' :
				if($month == '00' AND $date == '00')
				{
					$detail_title = sprintf('%s年のアーカイブ', $year, $month, $date);
				}
				elseif($month != '00' AND $date == '00')
				{
					$detail_title = sprintf('%s年%s月のアーカイブ', $year, $month, $date);
				}
				else
				{
					$detail_title = sprintf('%s年%s月%s日のアーカイブ', $year, $month, $date);
				}
				break;
			case 'article' :
			case 'comment' :
				$detail_title = $detail->title;
				break;
			default :
				$detail_title = '';
		}
	}
	$detail_title .= ( ! empty( $detail_title) ) ? ' | ' : '';
	$output[] = '<title>' . prep_str(sprintf(SEEZOO_TITLE_FORMAT, $detail_title . $page_title, SITE_TITLE)) . '</title>';

	if ( $seezoo->page->meta_description !== '')
	{
		$output[] = '<meta name="description" content="' . prep_str($seezoo->page->meta_description) . '" />';
	}
	if ( $seezoo->page->meta_keyword !== '')
	{
		$output[] = '<meta name="keywords" content="' . prep_str($seezoo->page->meta_keyword) . '" />';
	}

	if ( $seezoo->isLoggedIn() === TRUE )
	{
		$output[] = build_css('css/edit_base.css');
		$output[] = build_css('css/ajax_styles.css');
		if ( ADVANCE_UA === 'ie6' )
		{
			$output[] = build_css('css/edit_base_advance_ie6.css');
		}
		else if ( ADVANCE_UA === 'ie7' )
		{
			$output[] = build_css('css/edit_base_advance_ie7.css');
		}

		if ( $seezoo->isCMSMode === FALSE )
		{
			$output[] = flint_execute('segment');
			// Do you want to use jQuery?
			if ( $seezoo->site_data->use_jquery > 0 )
			{
				$output[] = '<script type="text/javascript" src="' . file_link() . 'js/jquery.min.js" charset="UTF-8"></script>';
			}

		}
		else
		{
			$output[] = flint_execute($seezoo->page->page_id);

			// Do you want to use jQuery?
			//if ( $seezoo->site->use_jquery > 0 )
			//{
			//	$output[] = '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>';
			//}
		}

		// advance parameters
		$output[] = '<script type="text/javascript">';
		$output[] = 'var SZ_PAGE_ID = ' . $seezoo->page->page_id . ';';
		$output[] = 'var IS_EDIT = ' . (($seezoo->edit_mode === 'EDIT_SELF') ? 'true' : 'false') . ';';
		if ( $seezoo->edit_mode != 'EDIT_SELF')
		{
			$output[] = "DOM('a').event('click', function(ev) { ev.preventDefault();});";
		}
		$output[] = '</script>';
	}
	else
	{
		// if normal views, flint.js works normal mode
		$output[] = flint_execute('view');

		// Do you want to use jQuery?
		//if ( $seezoo->site->use_jquery > 0 )
		//{
		//	$output[] = '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>';
		//}
	}
}
else
{
	if ( $seezoo->is_preview === TRUE )
	{
		$output[] = '<script type="text/javascript" src="' . get_base_link() . 'flint.php?m=preview" charset="UTF-8"></script>';
	}
	else
	{
		$output[] = '<script type="text/javascript" src="' . get_base_link() . 'flint.php?m=view" charset="UTF-8"></script>';
	}
	$output[] = '<script type="text/javascript" src="' . file_link() . 'js/flint.dev.min.js" charset="UTF-8"></script>';
	$output[] = '<script type="text/javascript">';
	$output[] = 'window.onload = function() { var al = document.getElementsByTagName("a");';
	$output[] = 'for (var i = 0; i < al.length; i++) { al[i].onclick = function() { return false;}}';
	$output[] = '};';
	$output[] = '</script>';
}
	// tmp set output headers
	$output[] = '<!--{$OUTPUT_HEADERS}-->';

if ( ! empty($seezoo->page->advance_css) )
{
	$output[] = '<link rel="stylesheet" type="text/css" href="' . get_base_link() . 'page/advance_css/' . $seezoo->page->template_id . '" id="sz_advance_css_val_link" />';
}
//}
echo implode("\n", $output) . "\n";
