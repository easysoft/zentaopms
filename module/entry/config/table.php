<?php
global $lang;
$config->entry->actionList = array();

$config->entry->actionList['log']['icon'] = 'file-text';
$config->entry->actionList['log']['text'] = '';
$config->entry->actionList['log']['hint'] = $lang->entry->log;
$config->entry->actionList['log']['url']  = array('module' => 'entry', 'method' => 'log', 'params' => 'entryID={id}');

$config->entry->actionList['edit']['icon'] = 'edit';
$config->entry->actionList['edit']['text'] = '';
$config->entry->actionList['edit']['hint'] = $lang->entry->edit;
$config->entry->actionList['edit']['url']  = array('module' => 'entry', 'method' => 'edit', 'params' => 'entryID={id}');

$config->entry->actionList['delete']['icon']         = 'trash';
$config->entry->actionList['delete']['text']         = '';
$config->entry->actionList['delete']['hint']         = $lang->entry->delete;
$config->entry->actionList['delete']['url']          = array('module' => 'entry', 'method' => 'delete', 'params' => 'entryID={id}');
$config->entry->actionList['delete']['class']        = 'btn ghost toolbar-item text-primary square size-sm ajax-submit';
$config->entry->actionList['delete']['data-confirm'] = array('message' => $lang->entry->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->entry->dtable = new stdclass();
$config->entry->dtable->fieldList['id']['name']     = 'id';
$config->entry->dtable->fieldList['id']['title']    = $lang->idAB;
$config->entry->dtable->fieldList['id']['type']     = 'id';
$config->entry->dtable->fieldList['id']['fixed']    = 'left';
$config->entry->dtable->fieldList['id']['sortType'] = true;

$config->entry->dtable->fieldList['name']['name']     = 'name';
$config->entry->dtable->fieldList['name']['title']    = $lang->entry->name;
$config->entry->dtable->fieldList['name']['type']     = 'text';
$config->entry->dtable->fieldList['name']['fixed']    = 'left';
$config->entry->dtable->fieldList['name']['sortType'] = true;

$config->entry->dtable->fieldList['code']['name']     = 'code';
$config->entry->dtable->fieldList['code']['title']    = $lang->entry->code;
$config->entry->dtable->fieldList['code']['type']     = 'text';
$config->entry->dtable->fieldList['code']['sortType'] = true;

$config->entry->dtable->fieldList['key']['name']     = 'key';
$config->entry->dtable->fieldList['key']['title']    = $lang->entry->key;
$config->entry->dtable->fieldList['key']['type']     = 'text';
$config->entry->dtable->fieldList['key']['sortType'] = true;

$config->entry->dtable->fieldList['ip']['name']     = 'ip';
$config->entry->dtable->fieldList['ip']['title']    = $lang->entry->ip;
$config->entry->dtable->fieldList['ip']['type']     = 'text';
$config->entry->dtable->fieldList['ip']['sortType'] = true;

$config->entry->dtable->fieldList['desc']['name']     = 'desc';
$config->entry->dtable->fieldList['desc']['title']    = $lang->entry->desc;
$config->entry->dtable->fieldList['desc']['type']     = 'text';
$config->entry->dtable->fieldList['desc']['sortType'] = true;

$config->entry->dtable->fieldList['actions']['title'] = $lang->actions;
$config->entry->dtable->fieldList['actions']['type']  = 'actions';
$config->entry->dtable->fieldList['actions']['list']  = $config->entry->actionList;
$config->entry->dtable->fieldList['actions']['menu']  = array_keys($config->entry->actionList);

$config->entry->log = new stdclass();
$config->entry->log->dtable = new stdclass();
$config->entry->log->dtable->fieldList['id']['name']     = 'id';
$config->entry->log->dtable->fieldList['id']['title']    = $lang->idAB;
$config->entry->log->dtable->fieldList['id']['type']     = 'id';
$config->entry->log->dtable->fieldList['id']['sortType'] = true;

$config->entry->log->dtable->fieldList['date']['name']     = 'date';
$config->entry->log->dtable->fieldList['date']['title']    = $lang->entry->date;
$config->entry->log->dtable->fieldList['date']['type']     = 'text';
$config->entry->log->dtable->fieldList['date']['sortType'] = true;

$config->entry->log->dtable->fieldList['url']['name']     = 'url';
$config->entry->log->dtable->fieldList['url']['title']    = $lang->entry->url;
$config->entry->log->dtable->fieldList['url']['type']     = 'text';
$config->entry->log->dtable->fieldList['url']['sortType'] = true;
