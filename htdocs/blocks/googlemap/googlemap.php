<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ============================================================================
 * Seezoo Google Maps出力ブロックコントローラ
 * @package Seezoo Core
 * @author Yoshiaki Sugimoto<neo.yoshiaki.sugimoto@gmail.com>
 * ============================================================================
 */
class Googlemap_block extends Block
{
	protected $table            = 'sz_bt_googlemap_block';
	protected $block_name       = 'GoogleMapブロック';
	protected $description      = 'GoogleMapを表示します。';
	protected $interface_width  = 500;
	protected $interface_height = 500;
	
	// Google maps API base url constans
	const GOOGLE_STATIC_MAP_BASE = 'http://maps.google.com/maps/api/staticmap';
	const GOOGLE_GEO_BASE        = 'http://maps.google.com/maps/geo';
	
	/**
	 * Database structure definition
	 */
	public function db()
	{
		$dbst = array(
			'block_id'  => array(
								'type'       => 'INT',
								'constraint' => 11,
								'key'        => TRUE
							),
			'api_key'   => array(
								'type'       => 'VARCHAR',
								'constraint' => 255,
								'null'       => TRUE
							),
			'zoom'      => array(
								'type'       => 'INT',
								'constraint' => 2,
								'default'    => 13
							),
			'address'   => array(
								'type'       => 'VARCHAR',
								'constraint' => 255
							),
			'lat'       => array(
								'type'       => 'VARCHAR',
								'constraint' => 60
							),
			'lng'       => array(
								'type'       => 'VARCHAR',
								'constraint' => 60
							),
			'width'     => array(
								'type'       => 'INT',
								'constraint' => 4,
							),
			'height'    => array(
								'type'       => 'INT',
								'constraint' => 4
							),
			'version'   => array(
								'type'       => 'TINYINT',
								'constraint' => 1
							)
		);
		
		return array($this->table => $dbst);
	}
	
	/**
	 * Set up google map ( javascript build )
	 */
	public function set_up()
	{
		if ((int)$this->version === 2
		    && ! isset($this->ci->additional_footer_javascript['googlemap2']) )
		{
			// use Google Maps JavaScript API version 2
			$this->ci->additional_footer_javascript['googlemap2'] = '<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='
			                                                         . $this->api_key
			                                                         . '&amp;sensor=false" charset="UTF-8"></script>';
		}
		else if ((int)$this->version === 3
		          && ! isset($this->ci->additional_footer_javascript['googlemap3']) )
		{
			// use Google Maps JavaScript API version 3 (no need API key)
			$this->ci->additional_footer_javascript['googlemap3'] = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false" charset="UTF-8"></script>';
		}

		$this->ci->additional_footer_javascript[] = '<script type="text/javascript" src="'
		                                             . file_link() . 'js/googlemap_init.js?'
		                                             . '&z=' . $this->zoom
		                                             . '&id=' . $this->block_id
		                                             . '&lat=' . $this->lat
		                                             . '&lng=' . $this->lng
		                                             . '&v=' . $this->version
		                                             . '"></script>';
	}
	
	/**
	 * Build Google static maps request ( for mobile feature phone )
	 */
	public function show_static_map()
	{
		$params = array(
			'center='  . $this->lat . ',' . $this->lng,
			'zoom='    . $this->zoom,
			'size='    . '300x300',
			'format='  . 'jpg',
			'markers=' . 'size:mid|color:red|' . $this->lat . ',' . $this->lng,
			'maptype=' . 'roadmap',
			'mobile='  . 'true',
			'sensor='  . 'false'
		);
		
		$request_uri = self::GOOGLE_STATIC_MAP_BASE
						. '?' . implode('&', $params);
		
		return '<img src="' . $request_uri . '" alt="' . prep_str($this->address) . '" />';
	}
	
	/**
	 * Get API key from site info
	 */
	public function get_api_key_from_site_info()
	{
		$sql    = 'SELECT gmap_api_key FROM site_info LIMIT 1';
		$query  = $this->ci->db->query($sql);
		$result = $query->row();
		
		return $result->gmap_api_key;
	}
	
	public function save($args)
	{
		$CI =& get_instance();
		if ($CI->input->post('eternal'))
		{
			$data = array('gmap_api_key' => $args['api_key']);
			
			$CI->db->update('site_info', $data);
		}
		if ($args['address'])
		{
			$geo    = 'http://maps.google.com/maps/geo?';
			$p      = array('q' => $args['address'], 'key' => $args['api_key'], 'sensor' => 'false', 'output' => 'xml');
			$latlng = http_request($geo . http_build_query($p));
			if ( $latlng )
			{
				try
				{
					$xml = @simplexml_load_string($latlng);
					list($args['lng'], $args['lat'], $zo) = explode(',', (string)$xml->Response->Placemark->Point->coordinates);
				}
				catch ( Exception $e )
				{
					list($args['lng'], $args['lat'], $zo) = array(0, 0, 14);
				}
			}
			else 
			{
				list($args['lng'], $args['lat'], $zo) = array(0, 0, 14);
			}
		}
		
		parent::save($args);
	}
}