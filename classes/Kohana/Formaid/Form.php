<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Formaid_Form {
	// Which form field is being modified
	protected $active_field = -1;
	
	// The form fields
	protected $fields = array();
	
	// Arguments to the Form::open call
	protected $open_args;
	
	// Valid field types
	protected $field_types = array('text', 'hidden', 'password', 'file', 'textarea', 'select', 'submit', 'html'); //  radio, image, button, checkbox
	
	// Parameters
	protected $params = array('name', 'label', 'value', 'attributes', 'options', 'double_encode');
	
	public function __construct($action = NULL, $attributes = NULL)
	{
		$this->open_args = array($action, $attributes);
	}
	
	// Every function is either a field, a parameter, or an HTML attribute
	public function __call($name, $args)
	{
		$value = $args[0];
		
		// Is it a field?
		if ( in_array($name, $this->field_types) )
		{
			return $this->new_field($name, $value);
		}
		else
		{
			return $this->set_param($name, $value);
		}
	}
	
	public function render()
	{
		// Open the form
		echo View::factory('formaid'.DIRECTORY_SEPARATOR.'open')
			->set('action', $this->open_args[0])
			->set('attributes', $this->open_args[1]);		
		
		// Traverse the fields
		foreach ( $this->fields as $field )
		{
			// Set defaults
			foreach ($this->params as $val)
			{
				if ( ! isset($field[$val]))
			    {
			        $field[$val] = NULL;
			    }
			}
			
			// Grab the view of that type
			$view = View::factory('formaid'.DIRECTORY_SEPARATOR.$field['type']);
			
			// Set variables
			foreach( $field as $key => $value )
			{
				$view->set($key, $value);
			}
			
			echo $view;
		}
		
		// Close
		echo View::factory('formaid'.DIRECTORY_SEPARATOR.'close');
	}
	
	/* Special Cases */
	public function submit($value = 'Submit')
	{
		return $this->new_field('submit', NULL)->value($value)->class('submit');	
	}
	
	/* Helper Functions */
	protected function new_field($type, $name)
	{		
		$this->fields[] = array('type' => $type, 'name' => $name);
		$this->active_field++;
		
		return $this;
	}
	
	// If it's not explicitly a parameter (used in the function call), it's an HTML attribute
	protected function set_param($key, $value)
	{
		$active = $this->active_field;
		
		// Param?
		if (in_array($key, $this->params))
		{
			$this->fields[$active][$key] = $value;
		}
		else
		{
			$this->fields[$active]['attributes'][$key] = $value;
		}
		
		return $this;
	}
}
