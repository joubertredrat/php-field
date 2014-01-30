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
	private $maxlength;
	private $disabled;

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
	 * HTML5 data attribute.
	 */
	private $data;

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
	const TYPE_PASSWORD = 'password';
	const TYPE_BUTTON = 'button';

	/**
	 * Constants for class works.
	 */
	const BREAK_LINE = true;
	const PREFIX_CALL = 'set';

	/**
	 * Creates a new instance of input field with a name.
	 *
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
		foreach(self::get_named_attr() as $attr_name)
			$this->$attr_name = array();
		return $this;
	}

	/**
	 * Get a list with possible field types.
	 *
	 * @return array Return a list of types.
	 */
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
		$data[] = self::TYPE_PASSWORD;
		$data[] = self::TYPE_BUTTON;
		return $data;
	}

	/**
	 * Call a new instance of field.
	 *
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
	 *
	 * @return array Return array with attributes.
	 */
	public static function get_multiple_attr() {
		return array('class', 'style', 'label_class', 'onclick', 'onchange', 'onkeyup', 'option', 'multiple_option');
	}

	/**
	 * Return a named attributes.
	 *
	 * @return array Return array with attributes.
	 */
	public static function get_named_attr() {
		return array('data');
	}

	/**
	 * 
	 */
	public function set_attributes(Array $data) {
		if($data) {
			foreach($data as $attr => $args) {
				$set = self::PREFIX_CALL . '_' . $attr;
				if(in_array($attr, self::get_multiple_attr())) {
					if(is_array($args)) {
						if(count($args) > 1) {
							foreach ($args as $value) {
								$this->{$set}($value);
							}
						} else
							$this->{$set}($args[0]);
					}
					else
						$this->{$set}($args);
				}
				else
					$this->{$set}($args);
			}
		}
		return $this;
	}

	/**
	 * Call the attributes and define the action routes.
	 *
	 * @param string $name Attribute name.
	 * @param array $arguments Values for attribute.
	 * @return Object returns the object.
	 */
	public function __call($name, $arguments) {
		$attr = $this->get_attr_called($name);
		$this->validate_attr($attr, $arguments);
		$this->populate_attr($attr, $arguments);
		return $this;
	}

	/**
	 * Get a attribute name from a external method called.
	 *
	 * @param string $method Method called.
	 * @return string Returns a attribute name.
	 */
	private function get_attr_called($method)
	{
		$method = explode('_', $method);
		$prefix = $method[0];
		if($prefix != self::PREFIX_CALL)
			exit(__CLASS__ . ' said: Erm, this method needs a prefix "' . self::PREFIX_CALL . '" before attribute, "' . self::PREFIX_CALL . '_id", "' . self::PREFIX_CALL . '_name", etc... okay? :)');
		unset($method[0]);
		return implode('_', $method);
	}

	/**
	 * Validate a attribute.
	 *
	 * @param string $attr Attribute name.
	 * @param string $data Attribute data to validation.
	 * @return void
	 */
	private function validate_attr($attr, $data)
	{
		if(!property_exists(__CLASS__, $attr))
			exit(__CLASS__ . ' said: No way! wrong attribute: "' . $attr . '".');
		switch($this->type) {
			case self::TYPE_TEXT:
			case self::TYPE_TEXTAREA:
			case self::TYPE_EMAIL:
			case self::TYPE_RADIO:
			case self::TYPE_CHECKBOX:
			case self::TYPE_FILE:
			case self::TYPE_SUBMIT:
			case self::TYPE_PASSWORD:
			case self::TYPE_BUTTON:
				if(count($data) != 2 && in_array($attr, self::get_named_attr()))
					exit(__CLASS__ . ' said: Hey! "' . $attr . '" receives only two params.');
				if(count($data) > 1 && !in_array($attr, self::get_multiple_attr()) && !in_array($attr, self::get_named_attr()))
					exit(__CLASS__ . ' said: Hey! "' . $attr . '" receives only one param.');
			break;
			case self::TYPE_HIDDEN:
				if(count($data) > 1)
					exit(__CLASS__ . ' said: Hey! "' . $attr . '" receives only one param.');
			break;
			case self::TYPE_SELECT:
			break;
			default:
				exit(__CLASS__ . ' said: Houston, Rasmus, Mom!!! I have a problem, I don\'t know what it\'s "' . $this->type . '" :(');
			break;
		}
	}

	/**
	 * Populates the attribute.
	 *
	 * @return void
	 */
	private function populate_attr($attr, $data)
	{
		switch($this->type) {
			case self::TYPE_TEXT:
			case self::TYPE_TEXTAREA:
			case self::TYPE_EMAIL:
			case self::TYPE_RADIO:
			case self::TYPE_CHECKBOX:
			case self::TYPE_FILE:
			case self::TYPE_SUBMIT:
			case self::TYPE_PASSWORD:
			case self::TYPE_BUTTON:
				if(in_array($attr, self::get_named_attr()))
					$this->{$attr}[$data[0]] = $data[1];
				else
					$this->$attr = in_array($attr, self::get_multiple_attr()) ? array_merge($this->$attr, $data) : $data[0];
			break;
			case self::TYPE_HIDDEN:
				$this->$attr = $data[0];
			break;
			case self::TYPE_SELECT:
				$group = null;
				switch($attr) {
					case 'multiple_option':
						$group = $data[0];
						unset($data[0]);
						$data = array_values($data);
					case 'option':
						switch(count($data)) {
							case 1:
								$option['name'] = $data[0];
								$option['value'] = $data[0];
								$option['selected'] = false;
							break;
							case 2:
								$option['name'] = $data[0];
								if(is_bool($data[1])) {
									$option['value'] = $data[0];
									$option['selected'] = $data[1];
								} else {
									$option['value'] = $data[1];
									$option['selected'] = false;
								}
							break;
							case 3:
								$option['name'] = $data[0];
								$option['value'] = $data[1];
								$option['selected'] = $data[2];
							break;
							default:
								exit(__CLASS__ . ' said: something is wrong here :(');
							break;
						}
						if(!is_null($group))
							$this->{$attr}[$group][] = $option;
						else
							$this->{$attr}[] = $option;
					break;
                                        case 'data':
                                            $this->{$attr}[$data[0]] = $data[1];
                                        break;
					default:
						if(count($data) > 1 && !in_array($attr, self::get_multiple_attr()))
							exit(__CLASS__ . ' said: Hey! "' . $attr . '" receives only one param.');
						$this->$attr = in_array($attr, self::get_multiple_attr()) ? array_merge($this->$attr, $data) : $data[0];
					break;
				}
			break;
			default:
				exit(__CLASS__ . ' said: Houston, Rasmus, Mom!!! I have a problem, I don\'t know what it\'s "' . $this->type . '" :(');
			break;
		}
	}

	/**
	 * Generates a html from data on object.
	 *
	 * @return string Return a html generated.
	 */
	public function get() {
		if($this->required)
			$this->class[] = 'required';
		return $this->{'get_' . $this->type}();
	}

	/**
	 * Get a object.
	 *
	 * @return Object returns the object.
	 */
	public function get_object() {
		return $this;
	}

	/**
	 * Gets a HTML generated and print on screen.
	 *
	 * @return void
	 */
	public function render($spam = false) {
		if($spam)
			echo '<span>' . (self::BREAK_LINE ? "\n" : '');
		echo $this->get();
		if($spam)
			echo (self::BREAK_LINE ? "\n" : '') . '</span>' . (self::BREAK_LINE ? "\n" : '');
	}

	/**
	 * Dump the object and print on screen.
	 *
	 * @return void
	 */
	public function dump() {
		ob_start();
		var_dump($this);
		echo highlight_string("<?php\n " . ob_get_clean() . "?>", true);
	}

	/**
	 * Generates a general html for most inputs.
	 *
	 * @return string Return a html generated.
	 * @fixme Don't use button and input here, use on respectly get functions.
	 */
	private function get_standard() {
		$html = '';
		$html .= $this->get_html_label();
		$input[] = 'type="' . $this->type . '"';
		$input[] = 'name="' . $this->name . '"';
		if($this->id)
			$input[] = 'id="' . $this->id . '"';
		if(count($this->class) > 0)
			$input[] = 'class="' . implode(' ', $this->class) . '"';
		if($this->value && $this->type !== self::TYPE_BUTTON)
			$input[] = 'value="' . $this->value . '"';
		if($this->title)
			$input[] = 'title="' . $this->title . '"';
		if($this->maxlength && is_int($this->maxlength))
			$input[] = 'maxlength="' . $this->maxlength . '"';
		if($this->multiple && $this->type === self::TYPE_FILE)
			$input[] = 'multiple="multiple"';
		if($this->required)
			$input[] = 'required';
		if($this->disabled)
			$input[] = 'disabled="disabled"';
		$input = array_merge($input, $this->get_html_multiple_attr());
		$input = array_merge($input, $this->get_html_named_attr());
		if($this->type === self::TYPE_BUTTON)
			$html .= '<button ' . implode(' ', $input) . '>' . ($this->value ? $this->value : '') . '</button>';
		else
			$html .= '<input ' . implode(' ', $input) . ' />';
		return $html;
	}

	/**
	 * Generates a html for field with multiple value attributes.
	 *
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
	 * Generates a html for field with named attributes.
	 *
	 * @return string Return a html generated.
	 */
	private function get_html_named_attr() {
		$attr = array();
		if($this->data) {
			foreach ($this->data as $name => $value) {
				$attr[] = 'data-' . $name . '="' . $value . '"';
			}
		}
		return $attr;
	}

	/**
	 * Generates a <label> HTML.
	 *
	 * @return string Return a html generated.
	 */
	private function get_html_label() {
		if(!$this->label_text || in_array($this->type, array(self::TYPE_SUBMIT, self::TYPE_HIDDEN, self::TYPE_BUTTON)))
			return '';
		$label = array();
		if($this->id)
			$label[] = 'for="' . $this->id . '"';
		if($this->label_id)
			$label[] = 'id="' . $this->label_id . '"';
		if(count($this->label_class) > 0)
			$label[] = 'class="' . implode(' ', $this->label_class) . '"';
		if($this->title)
			$label[] = 'title="' . $this->title . '"';
		$t = (!in_array($this->type, array(self::TYPE_CHECKBOX, self::TYPE_RADIO)) ? ':' : '');
		return '<label' . (count($label) > 0 ? ' ' . implode(' ', $label) : '') . '>' . $this->label_text . $t . ($this->required ? '*' : '') . '</label>' . (self::BREAK_LINE ? "\n" : '');
	}

	/**
	 * Generates a html with input type text.
	 *
	 * @return string Return a html generated.
	 */
	private function get_text() {
		return $this->get_standard();
	}

	/**
	 * Generates a html with input type email.
	 *
	 * @return string Return a html generated.
	 */
	private function get_email() {
		return $this->get_standard();
	}

	/**
	 * Generates a html with input type password.
	 *
	 * @return string Return a html generated.
	 */
	private function get_password() {
		return $this->get_standard();
	}

	/**
	 * Generates a html with input type file.
	 *
	 * @return string Return a html generated.
	 */
	private function get_file() {
		return $this->get_standard();
	}

	/**
	 * Generates a html with input type submit.
	 *
	 * @return string Return a html generated.
	 */
	private function get_submit() {
		return $this->get_standard();
	}

	/**
	 * Generates a html with input type button.
	 *
	 * @return string Return a html generated.
	 */
	private function get_button() {
		return $this->get_standard();
	}

	/**
	 * Generates a html with textarea.
	 *
	 * @return string Return a html generated.
	 */
	private function get_textarea() {
		$html = '';
		$html .= $this->get_html_label();
		$input[] = 'name="' . $this->name . '"';
		if($this->id)
			$input[] = 'id="' . $this->id . '"';
		if(count($this->class) > 0)
			$input[] = 'class="' . implode(' ', $this->class) . '"';
		if($this->title)
			$input[] = 'title="' . $this->title . '"';
		if($this->required)
			$input[] = 'required';
		$input = array_merge($input, $this->get_html_multiple_attr());
		$html .= '<textarea ' . implode(' ', $input) . ' >' . ($this->value ? $this->value : '') . '</textarea>';
		return $html;
	}

	/**
	 * Generates a html with input type hidden.
	 *
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
	 *
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
		$html .= $this->get_html_label();
		return $html;
	}

	/**
	 * Generates a html with input type radio.
	 *
	 * @return string Return a html generated.
	 */
	private function get_radio() {
		return $this->get_checkbox();
	}

	/**
	 * Generates a html with select field.
	 *
	 * @return string Return a html generated.
	 */
	private function get_select() {
		$html = '';
		$html .= $this->get_html_label();
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
		$select = array_merge($select, $this->get_html_named_attr());
		if($this->required)
			$select[] = 'required';
		$options = array();
		$multiple_options = array();
		if(count($this->multiple_option) > 0)
		{
			$groups = array_keys($this->multiple_option);
			foreach($groups as $group) {
				$list_options = array();
				foreach ($this->multiple_option[$group] as $data) {
					$list_options[] = '<option' . (is_null($data['value']) ? '' : ' value="' . $data['value'] . '" ') . (is_bool($data['selected']) && $data['selected'] == true ? ' selected="selected"' : '' ) . '>' . $data['name'] . '</option>';
				}
				$multiple_options[] = '<optgroup label="' . $group . '">' . (self::BREAK_LINE ? "\n" : '') . implode((self::BREAK_LINE ? "\n" : ''), $list_options) . (self::BREAK_LINE ? "\n" : '') . '</optgroup>';
			}

		}
		if(count($this->option) > 0)
		{
			foreach ($this->option as $data) {
				$options[] = '<option' . (is_null($data['value']) ? '' : ' value="' . $data['value'] . '" ') . (is_bool($data['selected']) && $data['selected'] == true ? ' selected="selected"' : '' ) . '>' . $data['name'] . '</option>';
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
