<?php
/**
 * The translate module zh-cn file of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     translate
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->translate->common       = 'Translate';
$lang->translate->index        = 'Home';
$lang->translate->addLang      = 'Add Language';
$lang->translate->module       = 'Translate Module';
$lang->translate->review       = 'Review';
$lang->translate->reviewAction = 'Review Translation';
$lang->translate->result       = 'Save Review Result';
$lang->translate->batchPass    = 'Batch Pass';
$lang->translate->export       = 'Export Lang File';
$lang->translate->setting      = 'Settings';
$lang->translate->chooseModule = 'Choose Module';

$lang->translate->name        = 'Language';
$lang->translate->code        = 'Code';
$lang->translate->key         = 'Key';
$lang->translate->reference   = 'Reference Language';
$lang->translate->status      = 'Status';
$lang->translate->refreshPage = 'Refresh';
$lang->translate->reason      = 'Reason';

$lang->translate->reviewTurnon = 'Review';
$lang->translate->reviewTurnonList['1'] = 'On';
$lang->translate->reviewTurnonList['0'] = 'Off';

$lang->translate->resultList['pass']   = 'Pass';
$lang->translate->resultList['reject'] = 'Reject';

$lang->translate->group              = 'View';
$lang->translate->allTotal           = 'Total';
$lang->translate->translatedTotal    = 'Translated';
$lang->translate->changedTotal       = 'Modified';
$lang->translate->reviewedTotal      = 'Reviewed';
$lang->translate->translatedProgress = 'Translation %';
$lang->translate->reviewedProgress   = 'Review %';

$lang->translate->builtIn  = 'Built-in Language';
$lang->translate->finished = 'Finished Translation';
$lang->translate->progress = '%s Done';
$lang->translate->count    = '（%s languages）';

$lang->translate->finishedLang    = 'Translated';
$lang->translate->translatingLang = 'Translating';
$lang->translate->allItems        = 'All Items：%s';

$lang->translate->statusList['waiting']    = 'Waiting';
$lang->translate->statusList['translated'] = 'Done';
$lang->translate->statusList['reviewed']   = 'Reviewed';
$lang->translate->statusList['rejected']   = 'Rejected';
$lang->translate->statusList['changed']    = 'Modified';

$lang->translate->notice = new stdclass();
$lang->translate->notice->failDirPriv  = "You don't have the privilege to write this directory. Please edit your privilege. <br /><code>%s</code>";
$lang->translate->notice->failCopyFile = "Failed to copy %s to %s. Please edit your privilege.";
$lang->translate->notice->failUnique   = "Item with Code %s exists.";
$lang->translate->notice->failMaxInput = "Edit the value of max_input_vars to % in php.ini to make sure your form is submitted.";
$lang->translate->notice->failRuleCode = "『Code』 shoud be letters, numbers and underlines.";
