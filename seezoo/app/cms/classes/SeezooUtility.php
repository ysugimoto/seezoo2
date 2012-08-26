<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ==============================================================================
 * 
 * Seezoo Utility Class
 * 
 * Manage seezoo CMS parameters.
 * 
 * @package seezoo Core
 * @author Yoshiaki Sugimoto <neo.yoshiaki.sugimoto@gmail.com>
 * @create 2011/06/02
 * 
 * ==============================================================================
 */

class SeezooUtility
{
	private $CI;
	private $convert_defaults = array(
		'width'          => 300,
		'height'         => 200,
		'quality'        => 80,
		'maintain_ratio' => TRUE
	);
	
	function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * is_edit_mode
	 * Get this page is edit mode now
	 * @access public
	 * @return bool
	 */
	public function is_edit_mode()
	{
		return ( $this->CI->edit_mode === 'EDIT_SELF' ) ? TRUE : FALSE;
	}
	
	/**
	 * is_user_logged_in
	 * @access public
	 * @return bool
	 */
	public function is_user_logged_in()
	{
		return ( (int)$this->CI->session->userdata('user_id') > 0 ) ? TRUE : FALSE;
	}
	
	/**
	 * is_member_logged_in
	 * @access public
	 * @return bool
	 */
	public function is_member_logged_in()
	{
		return ( (int)$this->CI->session->userdata('member_id') > 0 ) ? TRUE : FALSE;
	}
	
	/**
	 * get_template_path
	 * returns current using template path on absolute/relative.
	 * @access public
	 * @param $abs
	 */
	public function get_template_path($abs = TRUE)
	{
		if ( $abs )
		{
			return file_link() . 'templates/' . $this->CI->_rel_template_path;
		}
		else
		{
			return 'templates/' . $this->CI->_rel_template_path;
			//return $this->CI->relative_template_path;
		}
	}
	
	/**
	 * get_page_path
	 * returns current page path from pageID absolute/relative
	 * @access public
	 * @param $abs
	 * @return $path
	 */
	public function get_page_path($abs = TRUE)
	{
		if ( isset($this->page_path ) )
		{
			return $this->page_path;
		}
		$sql =
				'SELECT '
				.	'page_path '
				.'FROM '
				.	'page_paths '
				.'WHERE '
				.	'page_id = ? '
				.'LIMIT 1';
		$query = $this->CI->db->query($sql, array((int)$this->CI->page_id));
		
		if ( $query && $query->row() )
		{
			$result = $query->row();
			$page_path = ( $abs )
			             ? get_base_link() . $result->page_path
			             : $result->page_path;
		}
		else 
		{
			$page_path = ( $abs )
			             ? get_base_link()
			               : '';
		}
		$this->page_path = $page_path;
		return $this->page_path;
	}
	
	/**
	 * get_global_navigation
	 * generate first level navigation under the conditions:
	 *   display_page = 1
	 *   navigation_show = 1
	 *   parent = 1 ( top page )
	 *   publiced page only
	 *   display order descending
	 * @note this method is auto-navigaton blocks short cut.
	 *       but, If you need ascending or page level controled data,
	 *       use auto_navigation block on edit mode.
	 * @access public
	 */
	public function get_global_navigation($ul_class = '', $active_class = '')
	{
		// load the sitemap model
		$this->CI->load->model('sitemap_model');
		// And load the ajax_helper
		$this->CI->load->helper('ajax_helper');
		$navigations = $this->CI->sitemap_model->get_auto_navigation_data(
							array(
								'page_id'			=> 1,
								'show_base_page'	=> 1
							),
							1,
							1,
							TRUE
						);
		$nav = $this->build_navigation($navigations, $active_class);
		$ul = '<ul class="' . $ul_class . '">';
		array_unshift($nav, $ul);
		
		return implode("\n", $nav);
	}
	
	/**
	 * build_navigation
	 * Build navigation list on ul>li format
	 * @param  $nav
	 * @param  $ac_class
	 * @param  $recursive
	 * @access private
	 * @return mixed string or array
	 */
	private function build_navigation($nav, $ac_class, $recursive = FALSE)
	{
		if ($nav === FALSE)
		{
			return '';
		}
		$out = array();

		if ( $recursive )
		{
			$out = array('<ul>');
		}
		
		$user_id   = (int)$this->CI->session->userdata('user_id');
		$member_id = (int)$this->CI->session->userdata('member_id');
		$page_id   = (int)$this->CI->page_id;

		foreach ($nav as $key => $v)
		{
			if (is_permission_allowed($v['page']['allow_access_user'], $user_id)
					|| ($member_id > 0
					&& is_permission_allowed($v['page']['allow_access_user'], 'm')))
			{
				$out[] = '<li>';

				$active_class = ($v['page']['page_id'] == $page_id)
				                 ? TRUE
				                 : FALSE;
				$exp = explode('/', trim($v['page']['page_path'], '/'));
				$page_class = array(end($exp));
				
				if ( $active_class === TRUE && ! empty($ac_class) )
				{
					$page_class[] = $ac_class;
				}
				$link_class = ' class="' . implode(' ', $page_class) . '"';
				
				if ( $v['page']['is_ssl_page'] > 0 )
				{
					$out[] = '<a href="' .ssl_page_link() . $v['page']['page_path']
								. '"' . $link_class . '>' . $v['page']['page_title'] . '</a>';
				}
				else 
				{
					$out[] = '<a href="' .page_link() . $v['page']['page_path']
								. '"' . $link_class . '>' . $v['page']['page_title'] . '</a>';
				}
				if ($v['child'] !== FALSE)
				{
					// child pages format on recursive call this function.
					$out[] = $this->build_navigation($v['child'], $active_class, TRUE);
				}
				$out[] = '</li>';
			}
		}

		$out[] = '</ul>';

		return ($recursive) ? implode("\n", $out) : $out;
	}
	
	/**
	 * 画像をコンバートして、コンバート後の画像ファイルを返す
	 * @param $path
	 * @param $out_ext
	 * @param $width
	 * @param $height
	 * @param $ratio
	 * @param $quality
	 */
	public function convert_image(
	                             $path,               /* source image path         */
	                             $out_ext = FALSE,    /* output extension          */
	                             $width = 300,        /* dst_image width           */
	                             $height = 200,       /* dst image height          */
	                             $ratio = TRUE,       /* dst_image w/h ratio       */
	                             $quality = 80,       /* dst image quality         */
	                             $path_prefix = FALSE /* source file from template */
	)
	{
		if ( ! $path_prefix )
		{
			$source_path = $this->get_template_path(FALSE) . kill_traversal($path);
		}
		else
		{
			$source_path = kill_traversal($path);
		}
		
		$http         = file_link() . 'files/converted/';
		$real         = FCPATH      . 'files/converted/';
		// Does original file exists and image file?
		if ( ! file_exists(FCPATH . $source_path) )
		{
			return $this->get_template_path() . kill_traversal($path);
			//return $http . $source_path;
		}
		
		$data = @getimagesize(FCPATH . $source_path);
		if ( ! $data )
		{
			return $this->get_template_path() . kill_traversal($path);
			//return $http . $source_path;
		}
		
		// split filebody and extension
		list($filebody, $extension) = $this->_split_file_ext(FCPATH . $source_path);
		if ( $extension == '' )
		{
			return $http . $source_path;
		}
		
		$out_extension = ( $out_ext ) ? $out_ext : $extension;
		
		// cache target crypted file
		$cachefile = sha1($filebody)
		             . implode('_', array($width, $height, $quality, (int)$ratio))
		             . '.' . $out_extension;
		
		// If cachefile exists( already converted ), return that filepath
		if ( file_exists($real . $cachefile) )
		{
			return $http . $cachefile;
		}
		
		// if ratio is TRUE, recalculate new width/height
		if ( $ratio === TRUE )
		{
			if ( $data[0] > $data[1] && $width != $data[0])
			{
				$height = round($width * ($data[1] / $data[0]));
			}
			else if ( $data[1] < $data[0] && $height != $data[1] )
			{
				$width  = round($height * ($data[0] / $data[1]));
			}
		}
		
		// GD function definition
		$imagefunction = 'imagecreatefrom' . (( $extension === 'jpg' ) ? 'jpeg' : $extension);
		$savefunction  = 'image' . (( $out_extension === 'jpg' ) ? 'jpeg' : $out_extension); 
		$source_image  = $imagefunction(FCPATH . $source_path);
		$dst_image     = imagecreatetruecolor($width, $height);
		$is_truecolor  = imageistruecolor($source_image);
		
		// fill background if transparent
		if ( ! $is_truecolor )
		{
			$total_colors = imagecolorstotal($source_image);
			$trans_id     = imagecolortransparent($source_image);
			if ( $trans_id > 0 )
			{
				$trans_colors = ( $trans_id < $total_colors )
				                ? imagecolorsforindex($source_image, $trans_id)
				                : array('red' => '255', 'green' => '255', 'blue' => '255');
				imagefill(
						$dst_image,
						0,
						0,
						$trans_colors['red'],
						$trans_colors['green'],
						$trans_colors['blue']
					);
			}
		}
		
		// resample copy
		imagecopyresampled(
							$dst_image,
							$source_image,
							0,
							0,
							0,
							0,
							$width,
							$height,
							$data[0],
							$data[1]
						);
		
		// If source image if jpg, quality copy
		if ( $data[2] == IMAGETYPE_JPEG )
		{
			if ( ! $quality )
			{
				if ( ! $is_truecolor )
				{
					if ( $width == $data[0] && $height == $data[1] )
					{
						imagetruecolortopalette($dst_image, FALSE, $total_colors);
					}
					else if ( $total_colors < 4 )
					{
						imagetruecolortopalette($dst_image, ! isset($trans_colors), 8);
					}
					else
					{
						imagetruecolortopalette($dst_image, ! isset($trans_colors), 256);
						imagepalettecopy($dst_image, $source_image);
					}
				}
				else 
				{
					imagetruecolortopalette($dst_image, TRUE, pow(2, 6));
				}
				if ( isset($trans_colors) )
				{
					imagecolortransparent(
										$dst_image,
										imagecolorclosest(
														$dst_image,
														$trans_colors['red'],
														$trans_colors['green'],
														$trans_colors['blue']
													)
										);
				}
			}
			else 
			{
				if ( ! $is_truecolor )
				{
					$total = imagecolorstotal($source_image);
					$index = 1;
					while ( $total > 2 )
					{
						$total /= 2;
						$index++;
					}
				}
				else 
				{
					$index = 6;
				}
				
				if ( $quality == 50 )
				{
					$bit = $index;
				}
				else if ( $quality < 50 )
				{
					$bit = $quality * (($index - 1) / 50) + 1; 
				}
				else if ( $quality > 50 )
				{
					$bit = ( $quality - 50 ) * ((8 - $index) / 50) + $index;
				}
				$tmp_quality = pow(2, round($bit));
				
				if ( $tmp_quality < 1 )
				{
					$tmp_quality = 1;
				}
				if ( $tmp_quality > 256 )
				{
					$tmp_quality = 256;
				}
				
				imagetruecolortopalette($dst_image, TRUE, $tmp_quality);
			}
		}
		
		// output converted file
		if ( ! function_exists($savefunction) )
		{
			// GD is not support or some problem
			imagedestroy($dst_image);
			imagedestroy($source_image);
			return $http . $source_path;
		}
		
		if ( $savefunction === 'imagejpeg' )
		{
			$savefunction($dst_image, $real . $cachefile, $quality);
		}
		else 
		{
			$savefunction($dst_image, $real . $cachefile);
		}
		
		// destroy resource
		imagedestroy($dst_image);
		imagedestroy($source_image);
		
		// return converted image
		return $http . $cachefile;
	}
	
	private function _split_file_ext($path)
	{
		$pos = strrpos($path, '.');
		if ( $pos !== FALSE )
		{
			return array(
				substr($path, 0, $pos),
				substr($path, $pos + 1)
			);
		}
		return array($path, '');
	}
}
