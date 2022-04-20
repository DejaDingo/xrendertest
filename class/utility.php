<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * xrendertest module
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         XRenderTest
 * @since           2.5.11
 * @author          DejaDingo
 */

 /**
 * Class XrendertestUtility
 */
class XrendertestUtility
{
    /**
     * the form being constructed
     *
     * @var object
     * @access public
     */
    public $_form;

    /**
     * the list of form elements
     *
     * @var object
     * @access public
     */
    public $_elements = array();

    /**
     * construct a form containing the defined elements
     *
     * @param   string  $formName  the form name
     *
     * @return  XoopsThemeForm             the constructed form
     */
    public function asForm($formName, $formTitle = '')
    {
        $formTitle = "Form Testing - " . $formTitle;
        $form = new XoopsThemeForm($formTitle, $formName, "", 'post', true);
        foreach ($this->_elements as $item) {
            $item->appendTo($form);
        }

        return $form;
    }

    /**
     * add an element to be inserted into the form
     *
     * @param   XoopsForm{object} $element   the form elemenet
     * @param   boolean           $required  is element required?
     *
     * @return  ElementWrapper            an element wrapper
     */
    public function addElement($element, $required = false)
    {
        $item = new ElementWrapper($element, $required);
        $this->_elements[] = $item;
        return $item;
    }

    /**
     * get an array of the form's element wrappers
     *
     * @param boolean $recurse get elements recursively? (default = false)
     *
     * @return ElementWrapper[] array of element wrappers
     */
    public function &getElements($recurse = false)
    {
        if (!$recurse) {
            return $this->_elements;
        } else {
            $ret   = array();
            $count = count($this->_elements);
            for ($i = 0; $i < $count; ++$i) {
                if (is_object($this->_elements[$i])) {
                    if (!$this->_elements[$i]->isContainer()) {
                        $ret[] = &$this->_elements[$i];
                    } else {
                        $elements = &$this->_elements[$i]->getElements(true);
                        $count2   = count($elements);
                        for ($j = 0; $j < $count2; ++$j) {
                            $ret[] = &$elements[$j];
                        }
                        unset($elements);
                    }
                }
            }

            return $ret;
        }
    }

    /**
     * get an array of "name" attributes from element wrappers
     *
     * @return array array of form element names
     */
    public function getElementNames()
    {
        $ret      = array();
        $elements = &$this->getElements(true);
        $count    = count($elements);
        for ($i = 0; $i < $count; ++$i) {
            $ret[] = $elements[$i]->_name;
        }

        return $ret;
    }

    /**
     * get next form field name
     */
    public function nextFieldNumber($type)
    {
        $names = $this->getElementNames();
        $filtered = array_filter($names, function($e) use($type) {
            return stristr($e, $type) !== false;
        });
        if (empty($filtered)) {
            return 1;
        }
        rsort($filtered);
        $tokens = explode('_', $filtered[0]);
        return intval($tokens[1]) + 1;
    }

    /**
     * construct field description from $required parameter
     *
     * @param   boolean  $req  whether field is required
     *
     * @return  string        the field description
     */
    public function fieldDescription($req)
    {
        return $req ? "(Required)" : "";
    }

    /**
     * construct a new XoopsFormText object
     *
     * @return  XoopsFormText  the form element
     */
    public function newTextField()
    {
        $next = $this->nextFieldNumber('text');
        $caption = "Text $next";
        $value = "Dummy Text $next";
        $name = 'text_' . sprintf('%02d', $next);

        return new XoopsFormText($caption, $name, 30, 60, $value);
    }

    /**
     * construct a new XoopsFormRadio object
     *
     * @param   array $options  array of element options
     *
     * @return  XoopsFormRadio            the form element
     */
    public function newRadio($options)
    {
        $next = $this->nextFieldNumber('radio');
        $caption = "Radio $next";
        $name = 'radio_' . sprintf('%02d', $next);

        $radio = new XoopsFormRadio($caption, $name, 1);
        foreach ($options as $ky => $val) {
            $radio->addOption($ky, $val);
        }

        return $radio;
    }

    /**
     * construct a new XoopsFormRadio object with selections in columns
     *
     * @param   array  $options  array of element options
     * @param   int  $columns  how many columns to display selections
     *
     * @return  XoopsFormRadio            the form element
     */
    public function newRadioColumns($options, $columns)
    {
        $radio = $this->newRadio($options);
        $radio->columns = $columns;

        return $radio;
    }

    /**
     * construct a new XoopsFormCheckBox
     *
     * @param   array  $options  array of element options
     *
     * @return  XoopsFormCheckBox            the form element
     */
    public function newCheckBox($options)
    {
        $next = $this->nextFieldNumber('check');
        $caption = "CheckBox $next";
        $name = 'check_' . sprintf('%02d', $next);

        $checkbox = new XoopsFormCheckBox($caption, $name, 1);
        foreach ($options as $ky => $val) {
            $checkbox->addOption($ky, $val);
        }

        return $checkbox;
    }

    /**
     * construct a new XoopsFormCheckBox object with selections in columns
     *
     * @param   array  $options  array of element options
     * @param   int  $columns  how many columns to display selections
     *
     * @return  XoopsFormCheckBox            the form element
     */
    public function newCheckBoxColumns($options, $columns)
    {
        $checkbox = $this->newCheckBox($options);
        $checkbox->columns = $columns;

        return $checkbox;
    }

    /**
     * construct a new XoopsFormFile object
     *
     * @return  XoopsFormFile  the from element
     */
    public function newFileUpload()
    {
        $next = $this->nextFieldNumber('file');
        $caption = "Upload $next";
        $name = 'file_' . sprintf('%02d', $next);

        return new XoopsFormFile($caption, $name, 500000);
    }

    /**
     * construct a new XoopsFormSelect
     *
     * @param   array  $options  array of element options
     *
     * @return  XoopsFormSelect            the form element
     */
    public function newSelect($options)
    {
        $next = $this->nextFieldNumber('select');
        $caption = "Select $next";
        $name = 'select_' . sprintf('%02d', $next);

        $select = new XoopsFormSelect($caption, $name);
        $select->addOptionArray($options);

        return $select;
    }

    /**
     * construct a new XoopsFormTextDateSelect object
     *
     * @return  XoopsFormTextDateSelect  the from element
     */
    public function newDateSelect()
    {
        $next = $this->nextFieldNumber('date');
        $caption = "Date $next";
        $name = 'date_' . sprintf('%02d', $next);

        return new XoopsFormTextDateSelect($caption, $name, '15');
    }

    /**
     * construct a new XoopsFormColorPicker object
     *
     * @return  XoopsFormColorPicker  the from element
     */
    public function newColorPicker()
    {
        $next = $this->nextFieldNumber('color');
        $caption = "Color $next";
        $name = 'color_' . sprintf('%02d', $next);

        return new XoopsFormColorPicker($caption, $name, '#AEAEAE');
    }

    /**
     * construct a new XoopsFormElementTray object
     *
     * @param   string  $caption    (optional) the caption string
     * @param   string  $delimiter  (optional) the element delimiter (default = &nbsp;)
     *                                  (use: <br> for stacked items)
     *
     * @return  XoopsFormElementTray              the form element
     */
    public function newElementTray($caption = '', $delimiter = '&nbsp;')
    {
        return new XoopsFormElementTray($caption, $delimiter);
    }

    /**
     * add a XoopsFormButtonTray element
     *
     * @param   boolean $delete  should display Delete button? (default = false)
     *
     * @return  void
     */
    public function addStandardFormButtons($delete = false)
    {
        $next = $this->nextFieldNumber('submit');
        $name = 'submit_' . sprintf('%02d', $next);

        $this->addElement(new XoopsFormButtonTray($name, _SUBMIT, 'submit', '', $delete));
    }

    /**
     * add an insertBreak form element
     *
     * @param   string  $extra  any desired extra HTML
     *
     * @return  void
     */
    public function addBreak($extra)
    {
        $break = $this->addElement($extra);
        $break->_function = 'insertBreak';
    }

    /**
     * add text line to describe the element or element group
     *
     * @param   string  $title    the item title
     * @param   string  $descrip  (optional) the item description
     *
     * @return  void
     */
    public function addElementDescription($title, $descrip = null)
    {
        $text = '===== [ ' . $title;
        if ($descrip != null && $descrip != '') {
            $text .= '&nbsp; - &nbsp;' . $descrip;
        }
        $text .= ' ] =====';
        $this->addBreak($text);
    }

    /**
     * add a text field to the form
     *
     * @param   string  $title     title for describing label
     * @param   boolean $required  is field required? (default = false)
     *
     * @return  void
     */
	public function addTextField($title, $required = false)
    {
        $descrip = $this->fieldDescription($required);
        $this->addElementDescription($title, $descrip);
        $this->addElement($this->newTextField(), $required);
    }

    /**
     * add a password tray to the form
     *
     * @param   string  $title     title for describing label
     * @param   boolean $required  is field required? (default = false)
     *
     * @return  void
     */
	public function addPasswordTray($title, $required = false)
    {
        $descrip = $this->fieldDescription($required);
        $tray = $this->newElementTray(_US_PASSWORD . '<br>' . _US_TYPEPASSTWICE);

        $next = $this->nextFieldNumber('password');
        $name1 = 'password_' . sprintf('%02d', $next);
        $name2 = 'password_' . sprintf('%02d', $next + 1);
        $pwd_text  = new XoopsFormPassword('', $name1, 10, 32);
        $pwd_text2 = new XoopsFormPassword('', $name2, 10, 32);
        $tray->addElement($pwd_text, $required);
        $tray->addElement($pwd_text2, $required);

        $this->addElementDescription($title, $descrip);
        $this->addElement($tray);
    }

    /**
     * add a Yes/No radio button to the form
     *
     * @param   string $title     title for describing label
     * @param   boolean $required  is element required? (default = false)
     *
     * @return  void
     */
    public function addRadioYesNo($title, $required = false)
    {
        $descrip = $this->fieldDescription($required);
        $options = [1 => 'Yes', 0 => 'No'];

        $this->addElementDescription($title, $descrip);
        $this->addElement($this->newRadio($options), $required);
    }

    /**
     * add an inline (horizontal and responsive) set of radio buttons
     *
     * @param   string $title      "title|description" for section label
     * @param   int $count         number of radio selections (default = 10)
     * @param   boolean $required  is element required? (default = false)
     *
     * @return  void
     */
    public function addRadioInline($title, $count = 10, $required = false)
    {
        $tokens = array_pad(explode('|', $title), 2, '');
        list($title, $descrip) = $tokens;
        if ($descrip == '') {
            $descrip = $this->fieldDescription($required);
        }
        for ($i=1; $i <= $count; $i++) {
            $options[$i] = 'Inline ' . sprintf('%02d', $i);
        }

        $this->addElementDescription($title, $descrip);
        $this->addElement($this->newRadio($options), $required);
    }

    /**
     * add a set of radio buttons with a specified max number of columns
     *
     * @param   string $title      "title|description" for section label
     * @param   int $count         number of radio selections (default = 10)
     * @param   boolean $required  is element required? (default = false)
     *
     * @return  void
     */
    public function addRadioColumns($title, $count = 10, $columns = 1, $required = false)
    {
        $tokens = array_pad(explode('|', $title), 2, '');
        list($title, $descrip) = $tokens;
        if ($descrip == '') {
            $descrip = $this->fieldDescription($required);
        }
        for ($i=1; $i <= $count; $i++) {
            $options[$i] = 'Col ' . sprintf('%02d', $i);
        }

        $this->addElementDescription($title, $descrip);
        $this->addElement($this->newRadioColumns($options, $columns), $required);
    }

    /**
     * add a checkbox element with 3 options
     *
     * @param   string $title     title for describing label
     * @param   boolean $required  is element required? (default = false)
     *
     * @return  void
     */
    public function addCheckBoxSimple($title, $required = false)
    {
        $descrip = $this->fieldDescription($required);
        $options = [1 => 'One', 2 => 'Two', 3 => 'Three'];

        $this->addElementDescription($title, $descrip);
        $this->addElement($this->newCheckBox($options), $required);
    }

    /**
     * add an inline (horizontal and responsive) set of checkbox items
     *
     * @param   string $title      "title|description" for section label
     * @param   int $count         number of checkbox selections (default = 10)
     * @param   boolean $required  is element required? (default = false)
     *
     * @return  void
     */
    public function addCheckBoxInline($title, $count = 10, $required = false)
    {
        $tokens = array_pad(explode('|', $title), 2, '');
        list($title, $descrip) = $tokens;
        if ($descrip == '') {
            $descrip = $this->fieldDescription($required);
        }
        for ($i=1; $i <= $count; $i++) {
            $options[$i] = 'Inline ' . sprintf('%02d', $i);
        }

        $this->addElementDescription($title, $descrip);
        $this->addElement($this->newCheckBox($options), $required);
    }

    /**
     * add a set of checkbox items with a specified max number of columns
     *
     * @param   string $title      "title|description" for section label
     * @param   int $count         number of checkbox selections (default = 10)
     * @param   boolean $required  is element required? (default = false)
     *
     * @return  void
     */
    public function addCheckBoxColumns($title, $count = 10, $columns = 1, $required = false)
    {
        $tokens = array_pad(explode('|', $title), 2, '');
        list($title, $descrip) = $tokens;
        if ($descrip == '') {
            $descrip = $this->fieldDescription($required);
        }
        for ($i=1; $i <= $count; $i++) {
            $options[$i] = 'Col ' . sprintf('%02d', $i);
        }

        $this->addElementDescription($title, $descrip);
        $this->addElement($this->newCheckBoxColumns($options, $columns), $required);
    }

    /**
     * add a DHTML text area element to the form
     *
     * @param   string  $title  title for describing label
     *
     * @return  void
     */
    public function addDhtmlTextArea($title)
    {
        $next = $this->nextFieldNumber('txtarea');
        $name = 'txtarea_' . sprintf('%02d', $next);
        $caption = "Text Area $next";

        $this->addElementDescription($title);
        $this->addElement(new XoopsFormDhtmlTextArea($caption, $name, 'Initial text ...'));
    }

    /**
     * add a file upload element to the form
     *
     * @param   string $title     title for describing label
     * @param   boolean $required  is element required? (default = false)
     *
     * @return  void
     */
    public function addFileUpload($title, $required = false)
    {
        $descrip = $this->fieldDescription($required);

        $this->addElementDescription($title, $descrip);
        $this->addElement($this->newFileUpload(), $required);
    }

    /**
     * add a select element to the form
     *
     * @param   string $title     title for describing label
     * @param   boolean $required  is element required? (default = false)
     *
     * @return  void
     */
    public function addSelect($title, $required = false)
    {
        $descrip = $this->fieldDescription($required);
        $options = ['TOP', 'RIGHT', 'BOTTOM', 'LEFT'];

        $this->addElementDescription($title, $descrip);
        $this->addElement($this->newSelect($options), $required);
    }

    /**
     * add a date select element to the form
     *
     * @param   string $title     title for describing label
     * @param   boolean $required  is element required? (default = false)
     *
     * @return  void
     */
    public function addDateSelect($title, $required = false)
    {
        $descrip = $this->fieldDescription($required);

        $this->addElementDescription($title, $descrip);
        $this->addElement($this->newDateSelect(), $required);
    }

    /**
     * add a color picker element to the form
     *
     * @param   string $title     title for describing label
     * @param   boolean $required  is element required? (default = false)
     *
     * @return  void
     */
    public function addColorPicker($title, $required = false)
    {
        $descrip = $this->fieldDescription($required);

        $this->addElementDescription($title, $descrip);
        $this->addElement($this->newColorPicker(), $required);
    }

    /**
     * add a tray element containing the given form elements
     *
     * @param   string  $title      "title|description" for section label
     * @param   array   $elements   array of form elements
     * @param   string  $delimiter  (optional) the element delimiter (default = &nbsp;)
     *                                  (use: <br> for stacked items)
     *
     * @return  void
     */
    public function addElementTray($title, $elements, $delimiter = '&nbsp;')
    {
        $tokens = array_pad(explode('|', $title), 2, '');
        list($title, $descrip) = $tokens;

        $tray = $this->newElementTray('', $delimiter);
        foreach ($elements as $element) {
            $tray->addElement($element);
        }

        $this->addElementDescription($title, $descrip);
        $this->addElement($tray);
    }

    /**
     * add a XoopsFormElementTray containing 2 date select elements and a custom delimiter
     *
     * @param   string  $title  "title|description" for section label
     *
     * @return  void
     */
    public function addElementTrayDateRange($title)
    {
        $tokens = array_pad(explode('|', $title), 2, '');
        list($title, $descrip) = $tokens;

        $delimiter = '&nbsp;-&nbsp;';
        $tray = $this->newElementTray('', $delimiter);

        $next = $this->nextFieldNumber('date');
        $name1 = 'date_' . sprintf('%02d', $next);
        $name2 = 'date_' . sprintf('%02d', $next + 1);

        //  Captions will impact alignment of custom delimiter
        $tray->addElement(new XoopsFormTextDateSelect('IN', $name1, '10'));
        $tray->addElement(new XoopsFormTextDateSelect('OUT', $name2, '10'));

        $this->addElementDescription($title, $descrip);
        $this->addElement($tray);
    }

    /**
     * Create Mockup of SystemAvatar->getForm()
     *
     * @param   string  $formName  form name
     *
     * @return  XoopsThemeForm             form object
     */
    public function getAvatarForm($formName) {
        //  Mock properties
        $blank_img = 'blank.gif';
        $avatar_name = 'user.gif';
        $avatar_id = 1;
        $avatar_weight = 1;
        $avatar_display = 1;

        //  Form construction
        // Get User Config
        /* @var XoopsConfigHandler $config_handler */
        $config_handler  = xoops_getHandler('config');
        $xoopsConfigUser = $config_handler->getConfigsByCat(XOOPS_CONF_USER);

        $form = new XoopsThemeForm(_AM_SYSTEM_AVATAR_ADD, $formName, '', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormText(_IMAGENAME, 'avatar_name', 50, 255, $avatar_name), true);

        $maxpixel = '<div class="small basic italic">' . _US_MAXPIXEL . '&nbsp;:&nbsp;' . $xoopsConfigUser['avatar_width'] . ' x ' . $xoopsConfigUser['avatar_height'] . '</div>';
        $maxsize  = '<div class="small basic italic">' . _US_MAXIMGSZ . '&nbsp;:&nbsp;' . $xoopsConfigUser['avatar_maxsize'] . '</div>';

        $uploadirectory_img = '';
        $imgtray_img        = new XoopsFormElementTray(_IMAGEFILE . '<br><br>' . $maxpixel . $maxsize, '<br>');
        $imageselect_img    = new XoopsFormSelect(sprintf(_AM_SYSTEM_AVATAR_USE_FILE, XOOPS_UPLOAD_PATH), 'avatar_file', $blank_img);
        $image_array_img    = XoopsLists::getImgListAsArray(XOOPS_UPLOAD_PATH);
        $imageselect_img->addOption("$blank_img", $blank_img);
        foreach ($image_array_img as $image_img) {
//            if (preg_match('#avt#', $image_img)) {
            if (false !== strpos($image_img, 'avt')) {
                $imageselect_img->addOption("$image_img", $image_img);
            }
        }
        $imageselect_img->setExtra("onchange='showImgSelected(\"image_img\", \"avatar_file\", \"" . $uploadirectory_img . "\", \"\", \"" . XOOPS_UPLOAD_URL . "\")'");
        $imgtray_img->addElement($imageselect_img, false);
        $imgtray_img->addElement(new XoopsFormLabel('', "<br><img src='" . XOOPS_UPLOAD_URL . '/' . $blank_img . "' name='image_img' id='image_img' alt='' />"));
        $fileseltray_img = new XoopsFormElementTray('<br>', '<br><br>');
        $fileseltray_img->addElement(new XoopsFormFile(_AM_SYSTEM_AVATAR_UPLOAD, 'avatar_file', 500000), false);
        $imgtray_img->addElement($fileseltray_img);
        $form->addElement($imgtray_img);

        $form->addElement(new XoopsFormText(_IMGWEIGHT, 'avatar_weight', 3, 4, $avatar_weight));
        $form->addElement(new XoopsFormRadioYN(_IMGDISPLAY, 'avatar_display', $avatar_display, _YES, _NO));
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('fct', 'avatars'));
        $form->addElement(new XoopsFormHidden('avatar_id', $avatar_id));
        $btn = new XoopsFormButton('', 'avt_button', _SUBMIT, 'submit');
        $btn->setExtra("onclick='return false'");
        $form->addElement($btn);

        return $form;
    }

    /**
     * Create Mockup of SystemSmilies->getForm()
     *
     * @param   string  $formName  form name
     *
     * @return  XoopsThemeForm             form object
     */
    public function getSmiliesForm($formName)
    {
        //  Mock properties
        $blank_img = 'smil3dbd4d4e4c4f2.gif';
        $title = sprintf(_AM_SYSTEM_SMILIES_EDIT);
        $smilie_code = ':-D';
        $smilie_emotion = 'Very Happy';

        //  Form construction
        $form = new XoopsThemeForm($title, $formName, '', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormText(_AM_SYSTEM_SMILIES_CODE, 'code', 26, 25, $smilie_code), true);
        $form->addElement(new XoopsFormText(_AM_SYSTEM_SMILIES_DESCRIPTION, 'emotion', 50, 50, $smilie_emotion), true);

        $imgtray_img     = new XoopsFormElementTray(_AM_SYSTEM_SMILIES_FILE, '<br>');
        $imgpath_img     = sprintf(_AM_SYSTEM_SMILIES_IMAGE_PATH, XOOPS_UPLOAD_PATH . '/smilies/');
        $imageselect_img = new XoopsFormSelect($imgpath_img, 'smile_url', $blank_img);
        $image_array_img = XoopsLists::getImgListAsArray(XOOPS_UPLOAD_PATH . '/smilies');
        $imageselect_img->addOption("$blank_img", $blank_img);
        foreach ($image_array_img as $image_img) {
            $imageselect_img->addOption("$image_img", $image_img);
        }
        $imageselect_img->setExtra('onchange="showImgSelected(\'xo-smilies-img\', \'smile_url\', \'smilies\', \'\', \'' . XOOPS_UPLOAD_URL . '\' )"');
        $imgtray_img->addElement($imageselect_img, false);
        $imgtray_img->addElement(new XoopsFormLabel('', "<br><img src='" . XOOPS_UPLOAD_URL . '/smilies/' . $blank_img . "' name='image_img' id='xo-smilies-img' alt='' />"));

        $fileseltray_img = new XoopsFormElementTray('<br>', '<br><br>');
        $fileseltray_img->addElement(new XoopsFormFile(_AM_SYSTEM_SMILIES_UPLOADS, 'smile_url', 500000), false);
        $fileseltray_img->addElement(new XoopsFormLabel(''), false);
        $imgtray_img->addElement($fileseltray_img);
        $form->addElement($imgtray_img);

        $display = 0;
        $form->addElement(new XoopsFormRadioYN(_AM_SYSTEM_SMILIES_OFF, 'display', $display));

        $form->addElement(new XoopsFormHidden('op', 'save_smilie'));
        $btn = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
        $btn->setExtra("onclick='return false'");
        $form->addElement($btn);

        return $form;
    }

}

 /**
 * Class ElementWrapper
 */
class ElementWrapper
{
    public $_name;
    public $_required;
    public $_element;
    public $_function = null;
    public $_elements = [];

    public function __construct($element, $required = false)
    {
        $this->_name = $element->_name;
        $this->_required = $required;
        $this->_element = $element;
    }

    public function isContainer()
    {
        if (!is_object($this->_element)) return false;
        return $this->_element->isContainer();
    }

    public function addElement($element, $required = false)
    {
        $class = self::class;
        $item = new $class($element, $required);
        $this->_elements[] = $item;
    }

    public function &getElements($recurse = false)
    {
        if (!$recurse) {
            return $this->_elements;
        } else {
            $ret   = array();
            $count = count($this->_elements);
            for ($i = 0; $i < $count; ++$i) {
                if (is_object($this->_elements[$i])) {
                    if (!$this->_elements[$i]->isContainer()) {
                        $ret[] = &$this->_elements[$i];
                    } else {
                        $elements = &$this->_elements[$i]->getElements(true);
                        $count2   = count($elements);
                        for ($j = 0; $j < $count2; ++$j) {
                            $ret[] = &$elements[$j];
                        }
                        unset($elements);
                    }
                }
            }

            return $ret;
        }
    }

    public function appendTo($target)
    {
        if (is_a($target, 'XoopsForm') || !$target->isContainer()) {
            if (is_object($this->_element)) {
                $target->addElement($this->_element, $this->_required);
            } elseif (is_string($this->_function)) {
                $target_function = $this->_function;
                $target->$target_function($this->_element);
            }
        } else {
            foreach ($this->_elements as $elem) {
                $elem->appendTo($this->_element);
            }
        }
    }

}
