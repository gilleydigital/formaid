<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Formaid {
	protected static $messages = array();
	
	/* New form */
	public static function form($action = NULL, $attributes = NULL)
	{
		return new Formaid_Form($action, $attributes);
	}
	
	/* Messaging */
	public static function messages($module, $message_type, $message)
	{	
		if ($message and ($message_type === 'success' or $message_type === 'error'))
		{
			Formaid::$message_type($module, $message);
		}
	}

	public static function success($file, $path = NULL, $default = NULL)
	{
		return Formaid::add_message($file, $path, $default, 'success');
	}

	public static function error($file, $path = NULL, $default = NULL)
	{
		return Formaid::add_message($file, $path, $default, 'error');
	}

	public static function errors(array $errors)
	{
		$type = 'error';
		foreach($errors AS $message)
		{
			Formaid::$messages[$type][] = $message;
		}
	
		if ( ! View::get_global($type))
		{
			View::bind_global('form_result', Formaid::$messages);
		}
	}

	/* Helpers */
	protected static function add_message($file, $path, $default, $type)
	{
		if ( $message = Kohana::message($file, $path, $default))
		{
			Formaid::$messages[$type][] = $message;

			if ( ! View::get_global($type))
			{
				View::bind_global('form_result', Formaid::$messages);
			}
		
			return true;
		}
		else{
			return false;
		}
	}

	/* Flow control */
	public static function post()
	{
		$post = Request::current()->post();
		if (isset($post))
		{
			if (isset($post['is_posted']))
			{
				if ($post['is_posted'] === 'true')
				{
					unset($post['is_posted']);
					return Validation::factory($post);
				}
				else{
					return false;
				}
			}
		}
		else{
			return false;
		}
	}
}
