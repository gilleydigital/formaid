<?php defined('SYSPATH') or die('No direct script access.');

class View extends Kohana_View {
	
	public static function get_global($key)
	{
		return isset(View::$_global_data[$key]) ? View::$_global_data[$key] : FALSE;
	}
}
