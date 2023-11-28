<?php
global $app, $lang;
$app->loadLang('backup');

$config->backup->menu       = array('rmPHPHeader', 'restore', 'delete');
$config->backup->actionList = array();

$config->backup->actionList['rmPHPHeader']['icon']      = 'folder-download';
$config->backup->actionList['rmPHPHeader']['text']      = $lang->backup->rmPHPHeader;
$config->backup->actionList['rmPHPHeader']['hint']      = $lang->backup->rmPHPHeader;
$config->backup->actionList['rmPHPHeader']['url']       = helper::createLink('backup', 'rmPHPHeader', 'file={name}');
$config->backup->actionList['rmPHPHeader']['className'] = 'ajax-submit rmPHPHeader';

$config->backup->actionList['restore']['icon']         = 'restart';
$config->backup->actionList['restore']['text']         = $lang->backup->restore;
$config->backup->actionList['restore']['hint']         = $lang->backup->restore;
$config->backup->actionList['restore']['url']          = array('module' => 'backup', 'method' => 'restore', 'params' => 'file={name}');
$config->backup->actionList['restore']['className']    = 'ajax-submit restore';
$config->backup->actionList['restore']['data-confirm'] = $lang->backup->confirmRestore;

$config->backup->actionList['delete']['icon']         = 'trash';
$config->backup->actionList['delete']['text']         = $lang->delete;
$config->backup->actionList['delete']['hint']         = $lang->delete;
$config->backup->actionList['delete']['className']    = 'ajax-submit';
$config->backup->actionList['delete']['url']          = helper::createLink('backup', 'delete', 'file={name}');
$config->backup->actionList['delete']['data-confirm'] = $lang->backup->confirmDelete;

