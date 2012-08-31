<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ============================================================================
 * Seezoo Slideshow Block
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */

class Slideshow_block extends Block
{
	protected $block_name       = 'スライドショー（ギャラリー）ブロック';
	protected $description      = '画像をスライドショーで切り替えます。';
	protected $table            = 'sz_bt_slideshow';
	protected $sub_table        = 'sz_bt_slideshow_relation';
	protected $interface_width  = 600;
	protected $interface_height = 500;
	protected $enable_mb        = FALSE;

	// protected class properties
	// splitted file_ids from slide files string
	protected $img_array        = array();
	protected $link_array       = array();
	// slide files last
	protected $last_image       = 0;
	protected $first_image      = 0;
	protected $max_height       = 0;
	protected $total_width      = 0;
	protected $total_height     = 0;
	
	public $orig_block_id;

	// define db structure
	public function db()
	{
		$dbst = array(
			'block_id'   => array(
								'type'       => 'INT',
								'constraint' => 11,
								'key'        => TRUE
							),
			'slide_type' => array(
								'type'       => 'VARCHAR',
								'constraint' => 60,
								'null'       => TRUE
							),
			'delay_time' => array(
								'type'       => 'INT',
								'constraint' => 11,
								'default'    => 3000
							),
			'play_type'  => array(
								'type'       => 'INT',
								'constraint' => 11
							),
			'file_ids'   => array(
								'type'       => 'VARCHAR',
								'constraint' => 255
							),
			'page_ids'   => array(
								'type'       => 'VARCHAR',
								'constraint' => 255
							),
			'is_caption' => array(
								'type'       => 'TINYINT',
								'constraint' => 1,
								'default'    => 0
							)
		);

		return array($this->table => $dbst);
	}

	public function get_play_types()
	{
		return array(
			1 => '指定順に再生',
			2 => 'ランダム再生',
			3 => 'ギャラリーモードで再生'
		);
	}

	public function get_slide_types()
	{
		return array(
			1 => 'フェードアウト',
			2 => '左方向スライド'
		//	3	=> '上方向スライド'
		);
	}

	// overrride file_ids to implode string, and format delay_time
	public function save($args)
	{
		$args['file_ids'] = implode(':', $args['file_ids']);
		if (isset($args['delay_time']))
		{
			$args['delay_time'] = $args['delay_time'] * 1000;
		}
		$args['page_ids'] = implode(':', $args['page_ids']);
		parent::save($args);
	}

	/* ================= view methods ===================*/

	// calculate max height - set slide files has max image height
	// @need prepare call this method
	public function calculate_max_height()
	{
		$files = explode(':', $this->file_ids);
		$links = explode(':', $this->page_ids);

		if ( $this->slide_type == 1 && $this->play_type != 3 )
		{
			$files = array_reverse($files);
			$links = array_reverse($links);
		}
		
		$max_height = 0;
		$this->ci->load->model('file_model');
		foreach ($files as  $key => $fid)
		{
			$file        = $this->ci->file_model->get_file_data($fid);
			$path        = make_file_path($file);
			list($w, $h) = @getimagesize($path);
			if ($h > $max_height)
			{
				$max_height = $h;
			}
			$this->img_array[$fid] = $file;
			
			// link page is not empty?
			if ( isset($links[$key]) && ctype_digit($links[$key]) )
			{
				$this->link_array[$fid] = get_page_link_by_page_id($links[$key]);
			}
			else 
			{
				$this->link_array[$fid] = FALSE;
			}

			if ($key == count($files) - 1)
			{
				$this->last_image = $fid;
			}
			else if ($key == 0)
			{
				$this->first_image = $fid;
			}
			$this->total_width  += $w;
			$this->total_height += $h;
		}
		$this->max_height = $max_height;
		
		return $max_height;
	}

	public function set_slide_file_list()
	{
		// splited stacks already exists?
		if (count($this->img_array) > 0)
		{
			if ($this->play_type == 2)
			{
				shuffle($this->img_array);
			}
			return $this->img_array;
		}
		// else, recalculate and set image stacks
		else
		{
			$this->calculate_max_height();
			if ($this->play_type == 2)
			{
				shuffle($this->img_array);
			}
			return $this->img_array;
		}
	}

	public function set_first_image()
	{
		if (count($this->img_array) === 0)
		{
			$this->calculate_max_height();
		}
		$file = reset($this->img_array);
		return make_file_path($file, '', TRUE);
	}

	// view image HTML tag which slide types formated
	public function set_image($file, $key)
	{
		if ( $this->page_ids )
		{
			$links = explode(':', $this->page_ids);
		}
		else 
		{
			$links = array();
		}
		
		$active   = ($key == $this->first_image) ? ' class="active"' : '';
		$has_link = (isset($this->link_array[$key]) && $this->link_array[$key] !== FALSE) ? TRUE : FALSE;
		$prefix   = ($has_link) ? '<a href="' . $this->link_array[$key] . '"%s>' : '';
		$suffix   = ($has_link) ? '</a>' : '';
		
		$html = '';
		if ($this->play_type == 3)
		{
			$html .= '<li' . $active . '>'
					. $prefix
					. '<img src="' . make_file_path($file, 'thumbnail', TRUE) . '" alt="' . $file->file_name . '.' . $file->extension . '" />'
					. $suffix
					. '</li>';
			return $html;
		}
		if ($this->slide_type == 1) // simple fadeout
		{
			$active   = ($key == $this->last_image) ? ' class="active"' : '';
			$style = ' style="z-index:1;';//opacity:0;filter:alpha(opacity=0);"';
			if ($key == $this->last_image)
			{
				$style = ' style="z-index:2;opacity:1;filter:alpha(opacity=100);';
			}
			if ( $prefix )
			{
				$style .= 'width:' . $file->width . 'px;height:' . $file->height . 'px"';
				$prefix = sprintf($prefix, $style);
				$style = '';
			}
			else
			{
				$style .= '"';
			}
			$html .= $prefix
					. '<img src="' . make_file_path($file, '', TRUE) . '" alt="' . $file->file_name . '.' . $file->extension . '" width="' . $file->width . '" height="' . $file->height . '"' . $style . $active . ' />'
					. $suffix;
		}
		else if ($this->slide_type == 2) // slide left
		{
			$html .= '<li' . $active . '>'
					. $prefix
					. '<img src="' . make_file_path($file, '', TRUE) . '" alt="' . $file->file_name . '.' . $file->extension . '" width="' . $file->width . '" height="' . $file->height . '" />'
					. $suffix
					. '</li>';
		}
		else if ($this->slide_type == 3) // slide upper
		{
			$html .= '<li' . $active . '>'
					. $prefix
					. '<img src="' . make_file_path($file, '', TRUE) . '" alt="' . $file->file_name . '.' . $file->extension . '" width="' . $file->width . '" height="' . $file->height . '" />'
					. $suffix
					. '</li>';
		}
		return $html;
	}

	// prefix: if slide_type is not fade out, HTML output has <ul> list tags.
	public function set_slide_type_prefix()
	{
		$prefix = '';
		if ($this->slide_type > 1)
		{
			$attribute = 'class="sz_slideshow';
			switch ($this->slide_type)
			{
				case 2 :
					$attribute .= ' sz_slide_left';
					break;
				case 3 :
					$attribute .= ' sz_slide_upper';
					break;
				default : break;
			}
			$attribute .= '"';
			$prefix = '<ul ' . $attribute . '>';
		}
		return $prefix;
	}

	// suffix: if slide_type is not fade out, HTML output has <ul> list tags.
	public function set_slide_type_suffix()
	{
		$suffix = '';
		if ($this->slide_type > 1)
		{
			$suffix = '</ul>';
		}
		return $suffix;
	}

	// original block javascript outputs header with setting parameters
	public function setup_effect_javascript()
	{
		$file = 'blocks/slideshow/effect.js';
		if (file_exists(SZ_EXT_PATH . $file))
		{
			$path = package_link() . $file;
		}
		else
		{
			$path = file_link() . $file;
		}
		$javascript = 
						'<script type="text/javascript" src="'
						. $path
						. '?bid='
						. $this->orig_block_id
						. '&amp;type='
						. $this->slide_type
						. '&amp;delay='
						. $this->delay_time
						. '&amp;play='
						. $this->play_type
						. '" charset="UTF-8">'
						.'</script>';
		$this->add_header($javascript);
	}
	
	public function generate_caption()
	{
		if ( $this->is_caption < 1 )
		{
			return '';
		}
		$out[] = '<ul class="sz_slideshow_caption">';
		$times = 0;
		foreach ( $this->img_array as $key => $img )
		{
			$class = ( $times === 0 ) ? ' class="sz_slideshow_current"' : '';
			$out[] = '<li><a href="#" rel="target_' . $times . '"' . $class . '>■</a></li>';
			++$times;
		}
		$out[] = '</ul>';
		return implode("\n", $out);
	}
}