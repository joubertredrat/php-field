<?php
/**
 * This class generates a input text based on data in attributes.
 * Original idea and concepts by Rubens21 (@rubens21) and Marcus.
 * Want to see how the class? read documentation.
 *
 * @author		Joubert Guimarães de Assis "RedRat" <joubert@redrat.com.br>
 * @copyright	Copyright (c) 2013, RedRat Consultoria
 * @license		GPL version 2
 * @see 		Github, animes and mangás, cute girls and PHP, much PHP
 * @link 		https://github.com/joubertredrat/ci_field
 * @todo 		In future implements a html5 attributes.
 */

class Field {
	/**
	 * Field type.
	 */
	private $type;

	/**
	 * Input, textarea, select attributes.
	 */
	private $name;
	private $id;
	private $class;
	private $value;
	private $style;
	private $title;
	private $required;
	private $multiple;

	/**
	 * Select values.
	 */
	private $option;
	private $multiple_option;

	/**
	 * Input events.
	 */
	private $onclick;
	private $onchange;
	private $onkeyup;

	/**
	 * Radio and Checkbox attribute.
	 */
	private $checked;

	/**
	 * Label attributes and data.
	 */
	private $label_class;
	private $label_id;
	private $label_text;

	/**
	 * Type of form fields.
	 */
	const TYPE_TEXT = 'text';
	const TYPE_EMAIL = 'email';
	const TYPE_HIDDEN = 'hidden';
	const TYPE_TEXTAREA = 'textarea';
	const TYPE_RADIO = 'radio';
	const TYPE_CHECKBOX = 'checkbox';
	const TYPE_SELECT = 'select';
	const TYPE_FILE = 'file';
	const TYPE_SUBMIT = 'submit';

	/**
	 * Constants for class works.
	 */
	const BREAK_LINE = true;
	const PREFIX_CALL = 'set';

	/**
	 * Creates a new instance of input field with a name.
	 * @param string $name Name of field.
	 * @param string $type Field type.
	 * @return Object returns the object.
	 */
	private function __construct($name, $type) {
		$this->type = $type;
		$this->name = strtolower($name);
		$this->required = false;
		foreach(self::get_multiple_attr() as $attr_name)
			$this->$attr_name = array();
		return $this;
	}

	public static function get_types() {
		$data[] = self::TYPE_TEXT;
		$data[] = self::TYPE_EMAIL;
		$data[] = self::TYPE_TEXTAREA;
		$data[] = self::TYPE_HIDDEN;
		$data[] = self::TYPE_CHECKBOX;
		$data[] = self::TYPE_RADIO;
		$data[] = self::TYPE_SELECT;
		$data[] = self::TYPE_FILE;
		$data[] = self::TYPE_SUBMIT;
		return $data;
	}

	/**
	 * Call a new instance of field.
	 * @param string $name Name of field.
	 * @param string $type Field type.
	 * @return Object returns a created object.
	 */
	public static function newer($name = null, $type = self::TYPE_TEXT) {
		if(!in_array($type, self::get_types()))
			exit(__CLASS__ . ' said: Haha, this type "' . $type . '" is wrong.');
		if(is_null($name) || empty($name))
			exit(__CLASS__ . ' said: No no no, field needs a name.');
		return new self($name, $type);
	}

	/**
	 * Return a attributes with multiple data.
	 * @return array Return array with attributes.
	 */
	public static function get_multiple_attr() {
		return array('class', 'style', 'label_class', 'onclick', 'onchange', 'onkeyup', 'option', 'multiple_option');
	}

	/**
	 * Call the attributes and set the values.
	 * @param string $name Attribute name.
	 * @param array $arguments Values for attribute.
	 * @return Object returns the object.
	 */
	public function __call($name, $arguments) {
		$name = explode('_', $name);
		$method = $name[0];
		if($method != self::PREFIX_CALL)
			exit(__CLASS__ . ' said: Erm, this method needs a "' . self::PREFIX_CALL . '" before attribute, "' . self::PREFIX_CALL . '_id", "' . self::PREFIX_CALL . '_name", etc... okay? :)');
		unset($name[0]);
		switch($this->type) {
			case self::TYPE_TEXT:
			case self::TYPE_TEXTAREA:
			case self::TYPE_EMAIL:
			case self::TYPE_RADIO:
			case self::TYPE_CHECKBOX:
			case self::TYPE_FILE:
			case self::TYPE_SUBMIT:
				$name = implode('_', $name);
				if(!property_exists(__CLASS__, $name))
					exit(__CLASS__ . ' said: No way! wrong attribute: "' . $name . '".');	
				if(count($arguments) > 1 && !in_array($name, self::get_multiple_attr()))
					exit(__CLASS__ . ' said: Hey! "' . $name . '" receives only one param.');
				$this->$name = in_array($name, self::get_multiple_attr()) ? array_merge($this->$name, $arguments) : $arguments[0];
			break;
			case self::TYPE_HIDDEN:
				$name = implode('_', $name);
				if(!property_exists(__CLASS__, $name))
					exit(__CLASS__ . ' said: No way! wrong attribute: "' . $name . '".');
				if(count($arguments) > 1)
					exit(__CLASS__ . ' said: Hey! "' . $name . '" receives only one param.');
				$this->$name =  $arguments[0];
			break;
			case self::TYPE_SELECT:
				$name = implode('_', $name);
				if(!property_exists(__CLASS__, $name))
					exit(__CLASS__ . ' said: No way! wrong attribute: "' . $name . '".');
				$group = null;
				switch($name) {
					case 'multiple_option':
						$group = $arguments[0];
						unset($arguments[0]);
						$arguments = array_values($arguments);
					case 'option':
						switch(count($arguments)) {
							case 1:
								$option['name'] = $arguments[0];
								$option['value'] = $arguments[0];
								$option['selected'] = false;
							break;
							case 2:
								$option['name'] = $arguments[0];
								if(is_bool($arguments[1]))
								{
									$option['value'] = $arguments[0];
									$option['selected'] = $arguments[1];
								}
								else
								{
									$option['value'] = $arguments[1];
									$option['selected'] = false;
								}
							break;
							case 3:
								$option['name'] = $arguments[0];
								$option['value'] = $arguments[1];
								$option['selected'] = $arguments[2];
							break;
							default:
								exit(__CLASS__ . ' said: something is wrong here :(');
							break;
						}
						if(!is_null($group))
							$this->multiple_option[$group][] = $option;
						else
							$this->option[] = $option;
					break;
					default:
						if(count($arguments) > 1 && !in_array($name, self::get_multiple_attr()))
							exit(__CLASS__ . ' said: Hey! "' . $name . '" receives only one param.');
						$this->$name = in_array($name, self::get_multiple_attr()) ? array_merge($this->$name, $arguments) : $arguments[0];
					break;
				}
			break;
			default:
				exit(__CLASS__ . ' said: Houston, Rasmus, Mom!!! I have a problem, I don\'t know what it\'s "' . $this->type . '" :(');
			break;
		}
		return $this;
	}

	/**
	 * Generates a html from data on object.
	 * @return string Return a html generated.
	 */
	public function get() {
		if($this->required)
			$this->class[] = 'required';
		return $this->{'get_' . $this->type}();
	}

	/**
	 * Get a object.
	 * @return Object returns the object.
	 */
	public function get_object() {
		return $this;
	}

	/**
	 * Gets a HTML generated and print on screen.
	 * @return void
	 */
	public function render() {
		echo $this->get();
	}

	/**
	 * Dump the object and print on screen.
	 * @return void
	 */
	public function dump() {
		ob_start(); 
		var_dump($this); 
		echo highlight_string("<?php\n " . ob_get_clean() . "?>", true);
	}

	/**
	 * Generates a general html for most inputs.
	 * @return string Return a html generated.
	 */
	private function get_standard() {
		$html = '';
		$label = array();
		if($this->label_text && $this->type != self::TYPE_SUBMIT) {
			if($this->id)
				$label[] = 'for="' . $this->id . '"';
			if($this->label_id)
				$label[] = 'id="' . $this->label_id . '"';
			if(count($this->label_class) > 0)
				$label[] = 'class="' . implode(' ', $this->label_class) . '"';
			if($this->title)
				$label[] = 'title="' . $this->title . '"';
			$html .= '<label' . (count($label) > 0 ? ' ' . implode(' ', $label) : '') . '>' . $this->label_text . ':' . ($this->required ? '*' : '') . '</label>' . (self::BREAK_LINE ? "\n" : '');
		}

		$input[] = 'type="' . $this->type . '"';
		$input[] = 'name="' . $this->name . '"';
		if($this->id)
			$input[] = 'id="' . $this->id . '"';
		if(count($this->class) > 0)
			$input[] = 'class="' . implode(' ', $this->class) . '"';
		if($this->value)
			$input[] = 'value="' . $this->value . '"';
		if($this->title)
			$input[] = 'title="' . $this->title . '"';
		if($this->multiple && $this->type === self::TYPE_FILE)
			$input[] = 'multiple="multiple"';
		if($this->required)
			$input[] = 'required';
		$input = array_merge($input, $this->get_html_multiple_attr());
		$html .= '<input ' . implode(' ', $input) . ' />';
		return $html;
	}

	/**
	 * Generates a html with input type text.
	 * @return string Return a html generated.
	 */
	private function get_text() {
		return $this->get_standard();
	}

	/**
	 * Generates a html with input type email.
	 * @return string Return a html generated.
	 */
	private function get_email() {
		return $this->get_standard();
	}

	/**
	 * Generates a html with input type file.
	 * @return string Return a html generated.
	 */
	private function get_file() {
		return $this->get_standard();
	}

	/**
	 * Generates a html with input type submit.
	 * @return string Return a html generated.
	 */
	private function get_submit() {
		return $this->get_standard();
	}

	/**
	 * Generates a html with textarea.
	 * @return string Return a html generated.
	 */
	private function get_textarea() {
		$html = '';
		if($this->label_text) {
			if($this->id)
				$label[] = 'for="' . $this->id . '"';
			if($this->label_id)
				$label[] = 'id="' . $this->label_id . '"';
			if(count($this->label_class) > 0)
				$label[] = 'class="' . implode(' ', $this->label_class) . '"';
			if($this->title)
				$label[] = 'title="' . $this->title . '"';
			$html .= '<label ' . implode(' ', $label) . '>' . $this->label_text . ':' . ($this->required ? '*' : '') . '</label>' . (self::BREAK_LINE ? "\n" : '');
		}

		$input[] = 'name="' . $this->name . '"';
		if($this->id)
			$input[] = 'id="' . $this->id . '"';
		if(count($this->class) > 0)
			$input[] = 'class="' . implode(' ', $this->class) . '"';
		if($this->title)
			$input[] = 'title="' . $this->title . '"';
		$input = array_merge($input, $this->get_html_multiple_attr());
		$html .= '<textarea ' . implode(' ', $input) . ' >' . ($this->value ? $this->value : '') . '</textarea>';
		return $html;
	}

	/**
	 * Generates a html for field with multiple value attributes.
	 * @return string Return a html generated.
	 */
	private function get_html_multiple_attr() {
		$attr = array();
		if($this->style) {
			if(strpos(implode('', $this->style), '"') !== false)
				exit(__CLASS__ . ' said: Look here! for "style" attribute use only quotation.');
			$attr[] = 'style="' . implode('; ', $this->style) . ';"';
		}
		if($this->onclick) {
			if(strpos(implode('', $this->onclick), '"') !== false)
				exit(__CLASS__ . ' said: Look here! for "onclick" attribute use only quotation.');
			$attr[] = 'onclick="' . implode('; ', $this->onclick) . ';"';
		}
		if($this->onchange) {
			if(strpos(implode('', $this->onchange), '"') !== false)
				exit(__CLASS__ . ' said: Look here! for "onchange" attribute use only quotation.');
			$attr[] = 'onchange="' . implode('; ', $this->onchange) . ';"';
		}
		if($this->onkeyup) {
			if(strpos(implode('', $this->onkeyup), '"') !== false)
				exit(__CLASS__ . ' said: Look here! for "onkeyup" attribute use only quotation.');
			$attr[] = 'onkeyup="' . implode('; ', $this->onkeyup) . ';"';
		}
		return $attr;
	}

	/**
	 * Generates a html with input type hidden.
	 * @return string Return a html generated.
	 */
	private function get_hidden() {
		$html = '';
		$input[] = 'type="hidden"';
		$input[] = 'name="' . $this->name . '"';
		if($this->id)
			$input[] = 'id="' . $this->id . '"';
		if($this->value)
			$input[] = 'value="' . $this->value . '"';
		$input = array_merge($input, $this->get_html_multiple_attr());
		$html .= '<input ' . implode(' ', $input) . ' />';
		return $html;
	}

	/**
	 * Generates a html with input type checkbox.
	 * @return string Return a html generated.
	 */
	private function get_checkbox() {
		$html = '';

		$input[] = 'type="' . $this->type . '"';
		$input[] = 'name="' . $this->name . '"';
		if($this->id)
			$input[] = 'id="' . $this->id . '"';
		if(count($this->class) > 0)
			$input[] = 'class="' . implode(' ', $this->class) . '"';
		if($this->value)
			$input[] = 'value="' . $this->value . '"';
		if($this->title)
			$input[] = 'title="' . $this->title . '"';
		if($this->checked)
			$input[] = 'checked="checked"';
		$input = array_merge($input, $this->get_html_multiple_attr());
		$html .= '<input ' . implode(' ', $input) . ' />' . (self::BREAK_LINE ? "\n" : '');

		if($this->label_text) {
			if($this->id)
				$label[] = 'for="' . $this->id . '"';
			if($this->label_id)
				$label[] = 'id="' . $this->label_id . '"';
			if(count($this->label_class) > 0)
				$label[] = 'class="' . implode(' ', $this->label_class) . '"';
			if($this->title)
				$label[] = 'title="' . $this->title . '"';
			$html .= '<label ' . implode(' ', $label) . '>' . $this->label_text . ($this->required ? '*' : '') . '</label>';
		}
		return $html;
	}

	/**
	 * Generates a html with input type radio.
	 * @return string Return a html generated.
	 */
	private function get_radio() {
		return $this->get_checkbox();
	}

	/**
	 * Generates a html with select field.
	 * @return string Return a html generated.
	 */
	private function get_select() {
		$html = '';
		if($this->label_text) {
			if($this->id)
				$label[] = 'for="' . $this->id . '"';
			if($this->label_id)
				$label[] = 'id="' . $this->label_id . '"';
			if(count($this->label_class) > 0)
				$label[] = 'class="' . implode(' ', $this->label_class) . '"';
			if($this->title)
				$label[] = 'title="' . $this->title . '"';
			$html .= '<label ' . implode(' ', $label) . '>' . $this->label_text . ':' . ($this->required ? '*' : '') . '</label>' . (self::BREAK_LINE ? "\n" : '');
		}

		$select[] = 'name="' . $this->name . '"';
		if($this->id)
			$select[] = 'id="' . $this->id . '"';
		if(count($this->class) > 0)
			$select[] = 'class="' . implode(' ', $this->class) . '"';
		if($this->title)
			$select[] = 'title="' . $this->title . '"';
		if($this->multiple)
			$select[] = 'multiple="multiple"';
		$select = array_merge($select, $this->get_html_multiple_attr());

		$options = array();
		$multiple_options = array();

		if(count($this->multiple_option) > 0)
		{
			$groups = array_keys($this->multiple_option);
			foreach($groups as $group) {
				$list_options = array();
				foreach ($this->multiple_option[$group] as $data) {
					$list_options[] = '<option value="' . $data['value'] . '"' . (is_bool($data['selected']) && $data['selected'] == true ? ' selected="selected"' : '' ) . '>' . $data['name'] . '</option>';
				}
				$multiple_options[] = '<optgroup label="' . $group . '">' . (self::BREAK_LINE ? "\n" : '') . implode((self::BREAK_LINE ? "\n" : ''), $list_options) . (self::BREAK_LINE ? "\n" : '') . '</optgroup>';
			}

		}
		if(count($this->option) > 0)
		{
			foreach ($this->option as $data) {
				$options[] = '<option value="' . $data['value'] . '"' . (is_bool($data['selected']) && $data['selected'] == true ? ' selected="selected"' : '' ) . '>' . $data['name'] . '</option>';
			}
		}	
		$html .= '<select ' . implode(' ', $select) . '>' . (self::BREAK_LINE ? "\n" : '');
		if(count($multiple_options) > 0)
			$html .= implode((self::BREAK_LINE ? "\n" : ''), $multiple_options) . (self::BREAK_LINE ? "\n" : '');
		if(count($options) > 0)
			$html .= implode((self::BREAK_LINE ? "\n" : ''), $options) . (self::BREAK_LINE ? "\n" : '');
		$html .= '</select>';
		return $html;
	}
}