<?php if ( ! defined('SZ_EXEC') ) exit('access_denied');

class SeezooOptions
{
	private static $current_handle = 'common';
	private static $options        = array();
	private static $stack_options  = array();
	
	private $instance;
	
	public static function init($handle = '')
	{
		if ( empty($handle) )
		{
			$handle = 'common';
		}
		if ( $handle === self::$current_handle )
		{
			return;
		}
		
		// temporary save to stack
		self::$stack_options[self::$current_handle] = self::$options;
		self::$current_handle = $handle;
		
		if ( isset(self::$stack_options[$handle]) )
		{
			self::$options = self::$stack_options[$handle];
			return;
		}
		
		$db = Seezoo::$Importer->database();
		
		$sql =
				'SELECT '
				.	'OP.option_id, '
				.	'OP.name, '
				.	'OP.value '
				.'FROM '
				.	'sz_options as OP '
				.'WHERE '
				.	'handle_key = ?'
				;
		$query = $db->query($sql, array($handle));
		$opt   = array();
		if ( $query && $query->numRows() > 0 )
		{
			foreach ( $query->result() as $row )
			{
				$opt[$row->name] = $row;
			}
		}
		self::$options = $opt;
		self::$stack_options[$handle] = $opt;
	}
	
	public static function get($type = '')
	{
		return ( isset(self::$options[$type]) ) ? self::$options[$type]->value : FALSE;
	}
	
	public static function getAll()
	{
		$ret = array();
		foreach ( self::$options as $key => $value )
		{
			$ret[$key] = $value->value;
		}
		return $ret;
	}
	
	public static function set($type, $value = '')
	{
		if ( is_array($type) )
		{
			foreach ( $type as $key => $v )
			{
				self::set($key, $v);
			}
		}
		else
		{
			if ( ! isset(self::$options[$type]) )
			{
				self::$options[$type] = new stdClass;
			}
			self::$options[$type]->value = $value;
		}
	}
	
	public static function save($data = null)
	{
		if ( is_null($data) )
		{
			$data = self::$options;
		}
		else if ( is_object($data) )
		{
			$data = array_merge(self::$options, object_to_array($data));
		}
		else
		{
			$data = array_merge(self::$options, $data);
		}
		
		if ( count($data) == 0 )
		{
			return;
		}
		
		$db     =& DB();
		$handle = self::$current_handle;
		
		foreach ( $data as $key => $value )
		{
			if ( isset(self::$options[$key]) )
			{
				$db->where('option_id', self::$options[$key]->option_id);
				$db->where('handle_key', self::$current_handle);
				$db->update('sz_options', array('name' => $key, 'value' => $value));
			}
			else 
			{
				$db->insert('sz_options', array('name' => $key, 'value' => $value, 'handle_key' => self::$current_handle));
				$newdata = new stdClass;
				$newdata->value = $value;
				$newdata->option_id = $db->insert_id();
				self::$options[$key] = $newdata;
			}
		} 
		
//		// first delete record by handle id handle is plugins
//		$sql = 'DELETE FROM sz_options WHERE handle_key = ?';
//		$db->query($sql, array($handle));
//		
//		$insert  = 'INSERT INTO sz_options VALUES ';
//		$prepare = array();
//		$bind    = array();
//		foreach ( $data as $key => $value )
//		{
//			$prepare[] = '(?, ?, ?)';
//			$bind[] = $key;
//			$bind[] = $value;
//			$bind[] = $handle;
//		}
//		
//		$sql   = $insert . implode(', ', $prepare);
//		return $db->query($sql, $bind);
	}
}