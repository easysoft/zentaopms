<?php
namespace zin;

$formName = empty($formName) ? '#searchFormPanel[data-module="' . $module . '"]' : $formName;
$opts     = $this->search->buildSearchFormOptions($module, $fieldParams, $fields, $queries, $actionURL);

$opts->groupName       = array($lang->search->group1, $lang->search->group2);
$opts->savedQueryTitle = $lang->search->savedQuery;
$opts->formSession     = $formSession;
$opts->module          = $module;
$opts->actionURL       = $actionURL;
$opts->groupItems      = $groupItems;
$opts->submitText      = $lang->search->common;
$opts->resetText       = $lang->search->reset;
$opts->onSubmit        = jsRaw("window.onSearchFormResult.bind(null, '$formName')");

if(empty($opts->savedQuery)) unset($opts->savedQuery);

zui::searchform(set((array)$opts), set::_to($formName), set::_className('shadow'));

jsVar('options',          isset($options) ? $options : null);
jsVar('canSaveQuery',     !empty($_SESSION[$module . 'Query']));
jsVar('formSession',      $_SESSION[$module . 'Form']);
jsVar('onMenuBar',        $onMenuBar);

js($pageJS);

render('fragment');
