<?php
global $lang, $app;
$app->loadLang('instance');
$app->loadLang('backup');

$config->system->dtable = new stdclass();

$config->system->dtable->dbList = new stdclass();

$config->system->dtable->dbList->fieldList['name']['title'] = $lang->system->dbName;
$config->system->dtable->dbList->fieldList['name']['group'] = 1;

$config->system->dtable->dbList->fieldList['dbType']['title'] = $lang->system->dbType;
$config->system->dtable->dbList->fieldList['dbType']['name']  = 'db_type';
$config->system->dtable->dbList->fieldList['dbType']['group'] = 2;

$config->system->dtable->dbList->fieldList['dbStatus']['title'] = $lang->system->dbStatus;
$config->system->dtable->dbList->fieldList['dbStatus']['name']  = 'status';
$config->system->dtable->dbList->fieldList['dbStatus']['map']   = $lang->instance->statusList;

$config->system->dtable->dbList->fieldList['actions']['name']  = 'actions';
$config->system->dtable->dbList->fieldList['actions']['title'] = $lang->actions;
$config->system->dtable->dbList->fieldList['actions']['type']  = 'actions';
$config->system->dtable->dbList->fieldList['actions']['menu']  = array('management');

$config->system->dtable->dbList->fieldList['actions']['list']['management']['icon'] = 'cog-outline';
$config->system->dtable->dbList->fieldList['actions']['list']['management']['hint'] = $lang->system->management;

$config->system->dtable->backup = new stdclass();

$config->system->dtable->backup->fieldList['time']['title'] = $lang->backup->time;
$config->system->dtable->backup->fieldList['time']['type']  = 'datetime';

$config->system->dtable->backup->fieldList['type']['title'] = $lang->system->backup->type;
$config->system->dtable->backup->fieldList['type']['map']   = $lang->system->backup->typeList;

$config->system->dtable->backup->fieldList['status']['title'] = $lang->backup->status;

$config->system->dtable->backup->fieldList['comment']['title'] = $lang->comment;
$config->system->dtable->backup->fieldList['comment']['type']  = 'html';

$config->system->dtable->backup->fieldList['actions']['name']  = 'actions';
$config->system->dtable->backup->fieldList['actions']['title'] = $lang->actions;
$config->system->dtable->backup->fieldList['actions']['type']  = 'actions';
$config->system->dtable->backup->fieldList['actions']['menu']  = array('restore', 'delete');

$config->system->dtable->backup->fieldList['actions']['list']['restore']['icon'] = 'history';
$config->system->dtable->backup->fieldList['actions']['list']['restore']['hint'] = $lang->backup->restore;
$config->system->dtable->backup->fieldList['actions']['list']['restore']['url']  = array('module' => 'system', 'method' => 'restoreBackup', 'params' => 'name={name}');
$config->system->dtable->backup->fieldList['actions']['list']['restore']['data-confirm'] = $lang->system->backup->confirmRestore;

$config->system->dtable->backup->fieldList['actions']['list']['delete']['icon'] = 'trash';
$config->system->dtable->backup->fieldList['actions']['list']['delete']['hint'] = $lang->delete;
$config->system->dtable->backup->fieldList['actions']['list']['delete']['url']  = array('module' => 'system', 'method' => 'deleteBackup', 'params' => 'name={name}');
$config->system->dtable->backup->fieldList['actions']['list']['delete']['data-confirm'] = $lang->system->backup->confirmDelete;
