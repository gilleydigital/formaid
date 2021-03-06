<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Formaid_Form {
	// Which form field is being modified
	protected $active_field = 0;
	
	// The form fields
	protected $fields = array();
		
	// Valid field types
	protected $field_types = array('text', 'hidden', 'password', 'file', 'textarea', 'select', 'submit', 'html', 'checkbox', 'honeypot'); //  radio, image, button
	
	// Parameters
	protected $params = array('action', 'name', 'label', 'value', 'attributes', 'options', 'double_encode', 'checked');
	
	public function __construct($action = NULL, $attributes = NULL)
	{
		$this->fields[] = array(
			'type' => 'open',
			'attributes' => array(
				'class' => 'formaid',
			)
		);
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
	
	public function __toString()
	{
		$buffer = '';
		
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
			
			$buffer .= $view;
		}
		
		// Close
		$buffer .= View::factory('formaid'.DIRECTORY_SEPARATOR.'close');
		
		return $buffer;
	}
	
	/* Special Cases */
	public function submit($value = 'Submit')
	{
		return $this->new_field('submit', NULL)->value($value)->class('formfield-submit');	
	}

	public function honeypot()
	{
		return $this->new_field('text', 'honeypot')->label('Leave this blank')->class('honeypot');
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
			if ($key = 'class' and isset($this->fields[$active]['attributes']) and isset($this->fields[$active]['attributes']['class']))
			{
				$this->fields[$active]['attributes'][$key] .= ' '.$value;
			}
			else
			{
				$this->fields[$active]['attributes'][$key] = $value;
			}
		}
		
		return $this;
	}
}
