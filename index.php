<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Language;
use Xmf\Request;

/**
 * XOOPS Forms Test
 *
 * @copyright       (c) 2000-2022 XOOPS Project (www.xoops.org)
 * @license             GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author              DejaDingo
 */

$path = dirname(dirname(__DIR__));
require_once $path . '/mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'xrendertest_index.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

Language::load('global');
Language::load('user');
Language::load('admin\avatars', 'system');
Language::load('admin\smilies', 'system');

include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
xoops_load('utility', basename(__DIR__));

$op = Request::getString('op', '');
if ($op != '') {

	$GLOBALS['xoopsOption']['template_main'] = 'xrendertest_common.tpl';
	$tester = new XrendertestUtility('xoForm');

	switch ($op) {
		case 'avatar':
			$GLOBALS['xoopsTpl']->assign('no_action', true);
			$form = $tester->getAvatarForm('xoForm');
			break;

		case 'smilie':
			$GLOBALS['xoopsTpl']->assign('no_action', true);
			$form = $tester->getSmiliesForm('xoForm');
			break;

		case 'simple':
			$tester->addColorPicker("Color Picker");
			$tester->addTextField("Text Field", true);
			$tester->addPasswordTray("Password Tray");
			$tester->addBreak('<hr>');
			$tester->addElementDescription('Standard Form Buttons (with Delete)');
			$tester->addStandardFormButtons(true);
			$tester->addElementDescription('Standard Form Buttons (without Delete)');
			$tester->addStandardFormButtons();
			$form = $tester->asForm('xoForm', 'Simple Examples');
			break;

		case 'radio':
			$tester->addRadioYesNo("Radio Field", true);
			$tester->addBreak('<hr>');
			$tester->addRadioInline("Radio Inline|(10 Items - Responsive Columns)", 10);
			$tester->addRadioColumns("Radio One Column|(5 Items - Single Column)", 5, 1);
			$tester->addRadioColumns("Radio Columnar|(25 Items - 10 Columns, Responsive)", 25, 10);
			$form = $tester->asForm('xoForm', 'Radio Button Examples');
			break;

		case 'check':
			$tester->addCheckBoxSimple("CheckBox Field", true);
			$tester->addBreak('<hr>');
			$tester->addCheckBoxInline("CheckBox Inline|(10 Items - Responsive Columns)", 10);
			$tester->addCheckBoxColumns("CheckBox One Column|(5 Items - Single Column)", 5, 1);
			$tester->addCheckBoxColumns("CheckBox Columnar|(25 Items - 8 Columns, Responsive)", 25, 8);
			$form = $tester->asForm('xoForm', 'Checkbox Examples');
			break;

		case 'txtarea':
			$tester->addDhtmlTextArea("DHTML Text Area");
			$form = $tester->asForm('xoForm', 'Text Area Example');
			break;

		case 'select':
			$tester->addFileUpload("File Upload");
			$tester->addBreak('<hr>');
			$tester->addSelect("Select Single");
			$tester->addDateSelect("Date Select");
			$form = $tester->asForm('xoForm', 'Select Examples');
			break;

		case 'tray':
			$options = ['TOP', 'RIGHT', 'BOTTOM', 'LEFT'];
			$trayElements[] = $tester->newSelect($options);
			$trayElements[] = $tester->newDateSelect();
			$trayElements[] = $tester->newColorPicker();
			$tester->addElementTray("Stacked Tray|(Select + Date Select + Color Picker)", $trayElements, '<br>');
			unset($options, $trayElements);
			$tester->addBreak('<hr>');

			$options = ['ONE', 'TWO', 'THREE', 'FOUR'];
			$trayElements[] = $tester->newSelect($options);
			$trayElements[] = $tester->newFileUpload();
			$tester->addElementTray("Stacked Tray|(Select + File Upload)", $trayElements, '<br>');
			unset($options, $trayElements);
			$tester->addBreak('<hr>');

			$options = ['TOP', 'RIGHT', 'BOTTOM', 'LEFT'];
			$trayElements[] = $tester->newSelect($options);
			$trayElements[] = $tester->newDateSelect();
			$trayElements[] = $tester->newColorPicker();
			$tester->addElementTray("Inline Tray|(Select + Date Select + Color Picker)", $trayElements);
			unset($options, $trayElements);
			$tester->addBreak('<hr>');

			$options = ['ONE', 'TWO', 'THREE', 'FOUR'];
			$trayElements[] = $tester->newSelect($options);
			$trayElements[] = $tester->newFileUpload();
			$tester->addElementTray("Inline Tray|(Select + File Upload)", $trayElements);
			unset($options, $trayElements);
			$tester->addBreak('<hr>');

			$tester->addElementTrayDateRange("Inline Tray|(Dates with custom delimiter)");
			$form = $tester->asForm('xoForm', 'Element Tray Examples');
			break;

	}

	$form->assign($GLOBALS['xoopsTpl']);
}

include_once $GLOBALS['xoops']->path('footer.php');
