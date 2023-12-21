<?php
global $app, $lang;
$app->loadLang('stakeholder');

$config->stakeholder->menu       = array('communicate', 'expect', 'edit', 'delete');
$config->stakeholder->actionList = array();

$config->stakeholder->actionList['communicate']['icon']        = 'chat-line';
$config->stakeholder->actionList['communicate']['text']        = $lang->stakeholder->communicate;
$config->stakeholder->actionList['communicate']['hint']        = $lang->stakeholder->communicate;
$config->stakeholder->actionList['communicate']['url']         = helper::createLink('stakeholder', 'communicate', 'id={id}');
$config->stakeholder->actionList['communicate']['data-toggle'] = 'modal';

$config->stakeholder->actionList['expect']['icon']        = 'flag';
$config->stakeholder->actionList['expect']['text']        = $lang->stakeholder->expect;
$config->stakeholder->actionList['expect']['hint']        = $lang->stakeholder->expect;
$config->stakeholder->actionList['expect']['url']         = array('module' => 'stakeholder', 'method' => 'expect', 'params' => 'id={id}');
$config->stakeholder->actionList['expect']['data-toggle'] = 'modal';

$config->stakeholder->actionList['edit']['icon'] = 'edit';
$config->stakeholder->actionList['edit']['text'] = $lang->edit;
$config->stakeholder->actionList['edit']['hint'] = $lang->edit;
$config->stakeholder->actionList['edit']['url']  = helper::createLink('stakeholder', 'edit', 'id={id}');

$config->stakeholder->actionList['delete']['icon']         = 'trash';
$config->stakeholder->actionList['delete']['text']         = $lang->delete;
$config->stakeholder->actionList['delete']['hint']         = $lang->delete;
$config->stakeholder->actionList['delete']['url']          = helper::createLink('stakeholder', 'delete', 'id={id}');
$config->stakeholder->actionList['delete']['className']    = 'ajax-submit';
$config->stakeholder->actionList['delete']['data-confirm'] = $lang->stakeholder->confirmDelete;

if(!isset($config->stakeholder->menu)) $config->stakeholder->menu = array_keys($config->stakeholder->actionList);
