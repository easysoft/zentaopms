<?php
global $lang;
$config->holiday->actionList = array();
$config->holiday->actionList['edit']['icon']        = 'edit';
$config->holiday->actionList['edit']['text']        = '';
$config->holiday->actionList['edit']['hint']        = $lang->holiday->edit;
$config->holiday->actionList['edit']['url']         = array('module' => 'holiday', 'method' => 'edit', 'params' => 'holidayID={id}');
$config->holiday->actionList['edit']['data-toggle'] = 'modal';
$config->holiday->actionList['edit']['data-size']   = 'sm';

$config->holiday->actionList['delete']['icon']         = 'trash';
$config->holiday->actionList['delete']['text']         = '';
$config->holiday->actionList['delete']['hint']         = $lang->holiday->delete;
$config->holiday->actionList['delete']['url']          = array('module' => 'holiday', 'method' => 'delete', 'params' => 'holidayID={id}');
$config->holiday->actionList['delete']['className']    = 'ajax-submit';
$config->holiday->actionList['delete']['data-confirm'] = array('message' => $lang->holiday->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->holiday->dtable = new stdclass();
$config->holiday->dtable->fieldList['name']['name']  = 'name';
$config->holiday->dtable->fieldList['name']['title'] = $lang->holiday->name;
$config->holiday->dtable->fieldList['name']['type']  = 'text';

$config->holiday->dtable->fieldList['holiday']['name']  = 'holiday';
$config->holiday->dtable->fieldList['holiday']['title'] = $lang->holiday->holiday;
$config->holiday->dtable->fieldList['holiday']['type']  = 'text';

$config->holiday->dtable->fieldList['type']['name']  = 'type';
$config->holiday->dtable->fieldList['type']['title'] = $lang->holiday->type;
$config->holiday->dtable->fieldList['type']['type']  = 'text';
$config->holiday->dtable->fieldList['type']['map']   = $lang->holiday->typeList;

$config->holiday->dtable->fieldList['desc']['name']  = 'desc';
$config->holiday->dtable->fieldList['desc']['title'] = $lang->holiday->desc;
$config->holiday->dtable->fieldList['desc']['type']  = 'text';

$config->holiday->dtable->fieldList['actions']['title'] = $lang->actions;
$config->holiday->dtable->fieldList['actions']['type']  = 'actions';
$config->holiday->dtable->fieldList['actions']['list']  = $config->holiday->actionList;
$config->holiday->dtable->fieldList['actions']['menu']  = array_keys($config->holiday->actionList);

$config->holiday->dtable->import = new stdclass();
$config->holiday->dtable->import->fieldList['name']['name']  = 'name';
$config->holiday->dtable->import->fieldList['name']['title'] = $lang->holiday->name;
$config->holiday->dtable->import->fieldList['name']['type']  = 'text';

$config->holiday->dtable->import->fieldList['holiday']['name']  = 'holiday';
$config->holiday->dtable->import->fieldList['holiday']['title'] = $lang->holiday->holiday;
$config->holiday->dtable->import->fieldList['holiday']['type']  = 'text';
