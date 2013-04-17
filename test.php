<?php
/*
 * Text to generate a form fields.
 */

require(__DIR__ . '/field.php');

	Field::newer('username', Field::TYPE_TEXT)
		->set_label_text('Your username')
		->set_label_class('class1', 'class2')
		->set_label_id('zorongos')
		->set_id('blue')
		->set_required(true)
		->set_class('tongo1', 'tongo2')
		->set_class('tongo3')
		->set_value('your username here, okay?')
		->set_title('Insert here your username and be happy')
		->set_onclick('alert(\'lol\')', "alert('lol again')")
		->set_style('color: #0000FF', 'background-color: #00FFFF')
		->render();

/*
<label for="blue" id="zorongos" class="class1 class2" title="Insert here your username and be happy">Your username:*</label>
<input type="text" name="username" id="blue" class="tongo1 tongo2 tongo3 required" value="your username here, okay?" title="Insert here your username and be happy" required style="color: #0000FF; background-color: #00FFFF;" onclick="alert('lol'); alert('lol again');" />
*/

	Field::newer('newsletter', Field::TYPE_EMAIL)
		->set_label_text('Follow us')
		->set_label_class('class1')
		->set_label_class('class2')
		->set_label_class('class3', 'class4', 'class5')
		->set_id('news')
		->set_class('devs')
		->set_title('If you join us you will receive our newsletter')
		->set_onchange('alert(\' more one lol\')', "alert('more one lol again')")
		->set_style('color: #0000FF', 'background-color: #00FFFF')
		->render();

/*
<label for="news" class="class1 class2 class3 class4 class5" title="If you join us you will receive our newsletter">Follow us:</label>
<input type="email" name="newsletter" id="news" class="devs" title="If you join us you will receive our newsletter" style="color: #0000FF; background-color: #00FFFF;" onchange="alert(' more one lol'); alert('more one lol again');" />
*/

	Field::newer('Donnier', Field::TYPE_FILE)
		->set_label_text('Choose your file')
		->set_label_class('class1')
		->set_label_id('label_file_id')
		->set_id('filedev')
		->set_required(true)
		->set_class('tongo1', 'tongo2', 'files')
		->set_title('Upload a file')
		->set_multiple(true)
		->set_style('color: #FF00FF', 'background-color: #FFCCCC')
		->render();

/*
<label for="filedev" id="label_file_id" class="class1" title="Upload a file">Choose your file:*</label>
<input type="file" name="donnier" id="filedev" class="tongo1 tongo2 files required" title="Upload a file" multiple="multiple" required style="color: #FF00FF; background-color: #FFCCCC;" /></span>
*/

	Field::newer('go', Field::TYPE_SUBMIT)
		->set_id('now')
		->set_class('tongo1', 'tongo2')
		->set_value('Send! Send! Send!')
		->set_title('Send now!!!!')
		->set_style('color: #FF00FF', 'background-color: #FFCCCC')
		->render();

/*
<input type="submit" name="go" id="now" class="tongo1 tongo2" value="Send! Send! Send!" title="Send now!!!!" style="color: #FF00FF; background-color: #FFCCCC;" /></span>
*/

	Field::newer('option9257', Field::TYPE_CHECKBOX)
		->set_label_text('Option 925.7')
		->set_label_class('form1', 'form2')
		->set_label_id('form_label')
		->set_id('option9257')
		->set_checked(true)
		->set_class('input1', 'input2')
		->set_class('input3')
		->render();

/*
<input type="checkbox" name="option9257" id="option9257" class="input1 input2 input3" value="10928515" checked="checked" />
<label for="option9257" id="form_label" class="form1 form2">Option 925.7</label>
*/

	Field::newer('size', Field::TYPE_RADIO)
		->set_label_text('1280 x 720 pixels')
		->set_label_class('class1', 'class2')
		->set_label_id('zorongos')
		->set_id('screen_size')
		->set_required(true)
		->set_class('tongo1', 'tongo2')
		->set_class('tongo3')
		->set_value('720p')
		->render();

/*
<input type="radio" name="size" id="screen_size" class="tongo1 tongo2 tongo3 required" value="720p" />
<label for="screen_size" id="zorongos" class="class1 class2">1280 x 720 pixels*</label>
*/

	Field::newer('action', Field::TYPE_HIDDEN)
		->set_id('form_action')
		->set_value('edit')
		->render();

/*
<input type="hidden" name="action" id="form_action" value="edit" />
*/

	Field::newer('formcity', Field::TYPE_SELECT)
		->set_label_text('Cities')
		->set_label_class('formlabel')
		->set_id('formcity_id')
		->set_required(true)
		->set_multiple(true)
		->set_class('go1', 'go2')
		->set_class('dev1')
		->set_option('São Paulo', 'SP')
		->set_option('Tokyo', 'TO', true)
		->set_option('Berlin')
		->set_option('London', true)
		->render();

/*
<label for="formcity_id" class="formlabel">Cities:*</label>
<select name="formcity" id="formcity_id" class="go1 go2 dev1 required" multiple="multiple">
<option value="SP">São Paulo</option>
<option value="TO" selected="selected">Tokyo</option>
<option value="Berlin">Berlin</option>
<option value="London" selected="selected">London</option>
</select>
*/

	Field::newer('cars', Field::TYPE_SELECT)
		->set_label_text('List of cars')
		->set_id('cars')
		->set_class('sect1')
		->set_multiple_option('VW', 'Passati', 'vw_passati', true)
		->set_multiple_option('VW', 'Tiguan', true)
		->set_multiple_option('VW', 'Bora', 'vw_bora')
		->set_multiple_option('VW', 'Jetta')
		->set_option('Troller', 'br_troller')
		->set_multiple_option('Ford', 'Ranger')
		->set_option('Iosef Stalin 3', 'is3', true)
		->set_option('KV-1S')
		->set_multiple_option('Peugeot', '206')
		->set_multiple_option('Peugeot', '207')
		->render();

/*
<label for="cars">List of cars:</label>
<select name="cars" id="cars" class="sect1">
<optgroup label="VW">
<option value="vw_passati" selected="selected">Passati</option>
<option value="Tiguan" selected="selected">Tiguan</option>
<option value="vw_bora">Bora</option>
<option value="Jetta">Jetta</option>
</optgroup>
<optgroup label="Ford">
<option value="Ranger">Ranger</option>
</optgroup>
<optgroup label="Peugeot">
<option value="206">206</option>
<option value="207">207</option>
</optgroup>
<option value="br_troller">Troller</option>
<option value="is3" selected="selected">Iosef Stalin 3</option>
<option value="KV-1S">KV-1S</option>
</select>
*/