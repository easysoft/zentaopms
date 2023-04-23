<?php
namespace zin;

$opts = $this->search->buildSearchFormOptions($module, $fieldParams, $fields, $queries);

$opts->groupName       = array($lang->search->group1, $lang->search->group2);
$opts->savedQueryTitle = $lang->search->savedQuery;
$opts->applyQueryURL   = $actionURL;
$opts->deleteQueryURL  = createLink('search', 'deleteQuery', 'queryID=myQueryID');
$opts->formSession     = $formSession;
$opts->module          = $module;
$opts->actionURL       = $actionURL;
$opts->groupItems      = $groupItems;
$opts->onDeleteQuery   = jsRaw('window.onDeleteQuery');

if(empty($opts->savedQuery)) unset($opts->savedQuery);

zui::searchform(set($opts), set::_to('#searchFormPanel'), set::className('shadow'));

jsVar('onDeleteQueryURL', $opts->deleteQueryURL);
jsVar('options',          isset($options) ? $options : null);
jsVar('canSaveQuery',     !empty($_SESSION[$module . 'Query']));
jsVar('formSession',      $_SESSION[$module . 'Form']);
jsVar('onMenuBar',        $onMenuBar);

js($pageJS);

render('fragment');
