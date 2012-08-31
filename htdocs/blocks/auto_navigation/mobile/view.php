<div class="sz_auto_navigation">
<?php
// get navigation data
$nav = $controller->generate_navigation();

/**
 * ======================================
 * define function in global line
 * because output format can cahnge user custom template
 * where this functions customized.
 * if you customize, change function name and that process.
 * ======================================
 */
// normal navigation generate function
if ( ! function_exists('_build_autonavigation_format'))
{
	function _build_autonavigation_format($c, $data, $recursive = FALSE)
	{
			if ($data === FALSE)
			{
				return '';
			}
			$CI =& get_instance();

			if (!$recursive)
			{
				$out = array('<ul class="' . $c->get_display_mode_class() . ((!empty($c->handle_class) ? ' ' . $c->handle_class : '')) . '">');
			}
			else
			{
				$out = array('<ul>');
			}

			foreach ($data as $key => $v)
			{
				if (is_permission_allowed($v['page']['allow_access_user'], $CI->user_id)
						|| $CI->member_id > 0 && is_permission_allowed($v['page']['allow_access_user'], 'm'))
				{
					$out[] = '<li>';
	
					$active_class = ($v['page']['page_id'] == $CI->page_id) ? ' class="' . $c->current_class . '"' : '';
					$exp = explode('/', trim($v['page']['page_path'], '/'));
					$page_class = array(end($exp));
					if ( !empty($active_class) )
					{
						$page_class[] = $c->current_class;
					}
					$link_class = ' class="' . implode(' ', $page_class) . '"';
					
					if ( $v['page']['is_ssl_page'] > 0 )
					{
						$out[] = '<a href="' .ssl_page_link($v['page']['page_path'])
									. '"' . $link_class . '>' . $v['page']['page_title'] . '</a>';
					}
					else 
					{
						$out[] = '<a href="' .page_link($v['page']['page_path'])
									. '"' . $link_class . '>' . $v['page']['page_title'] . '</a>';
					}
					if ($v['child'] !== FALSE)
					{
						// child pages format on recursive call this function.
						$out[] = _build_autonavigation_format($c, $v['child'], TRUE);
					}
					$out[] = '</li>';
				}
			}

			$out[] = '</ul>';

			return implode("\n", $out);
	}
}

// generate breadcrumb function
if ( ! function_exists('_build_autonavigation_breadcrumb'))
{
	function _build_autonavigation_breadcrumb($c, $data)
	{
		if (count($data) == 1)
		{
			return '';
		}
		$CI =& get_instance();

		$out = array('<ul class="' . $c->get_display_mode_class() . ((empty($c->handle_class) ? '' : ' ' . $c->handle_class)) . '">');

		foreach ($data as $key => $v)
		{
			if (is_permission_allowed($v['allow_access_user'], $CI->user_id))
			{
				$active_class = ($v['page_id'] == $CI->page_id) ? ' class="' . $c->current_class . '"' : '';
				$exp = explode('/', trim($v['page_path'], '/'));
				$page_class = array(end($exp));
				if ( !empty($active_class) )
				{
					$page_class[] = $c->current_class;
				}
				$link_class = ' class="' . implode(' ', $page_class) . '"';
				
				if ($key == count($data) - 1)
				{
					$out[] = '<li' . $active_class . '>' . $v['page_title'] . '</li>';
				}
				else
				{
					if ( $v['is_ssl_page'] > 0 )
					{
						$out[] = '<li><a href="' . ssl_page_link($v['page_path']) . '"' . $link_class . '>' . $v['page_title'] . '</a></li>';
					}
					else 
					{
						$out[] = '<li><a href="' . page_link($v['page_path']) . '"' . $link_class . '>' . $v['page_title'] . '</a></li>';
					}
				}
				$out[] = '<li class="sz_bc_separator">&gt;</li>';
			}
		}
		array_pop($out);
		$out[] = '</ul>';

		return implode("\n", $out);
	}
}

if ((int)$controller->display_mode < 3)
{
	echo _build_autonavigation_format($controller, $nav);
}
else
{
	echo _build_autonavigation_breadcrumb($controller, $nav);
}

?>
</div>