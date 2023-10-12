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
$config->system->dtable->dbList->fieldList['actions']['menu']  = array('dblist');

$config->system->dtable->dbList->fieldList['actions']['list']['dblist']['icon'] = 'cog-outline';
$config->system->dtable->dbList->fieldList['actions']['list']['dblist']['hint'] = $lang->system->management;
$config->system->dtable->dbList->fieldList['actions']['list']['dblist']['url']  = array('method' => 'dblist');

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

$config->system->dtable->instanceList = new stdclass();

$config->system->dtable->instanceList->fieldList['name']['title']    = $lang->system->dbName;
$config->system->dtable->instanceList->fieldList['name']['type']     = 'title';
$config->system->dtable->instanceList->fieldList['name']['sortType'] = false;
$config->system->dtable->instanceList->fieldList['name']['link']     = array('module' => 'instance', 'method' => 'view', 'params' => 'id={id}');

$config->system->dtable->instanceList->fieldList['version']['title'] = $lang->instance->version;
$config->system->dtable->instanceList->fieldList['version']['name']  = 'appVersion';

$config->system->dtable->instanceList->fieldList['status']['title'] = $lang->system->dbStatus;
$config->system->dtable->instanceList->fieldList['status']['name']  = 'status';
$config->system->dtable->instanceList->fieldList['status']['map']   = $lang->instance->statusList;

$config->system->dtable->instanceList->fieldList['cpu']['title'] = array('html' => '<i class="icon icon-cpu mr-1"></i>' . $lang->instance->cpu);
$config->system->dtable->instanceList->fieldList['cpu']['name']     = 'cpu';
$config->system->dtable->instanceList->fieldList['cpu']['minWidth'] = '160';

$config->system->dtable->instanceList->fieldList['mem']['title'] = array('html' => '<i class="icon icon-memory mr-1"></i>' . $lang->instance->mem);
$config->system->dtable->instanceList->fieldList['mem']['name']     = 'mem';
$config->system->dtable->instanceList->fieldList['mem']['minWidth'] = '230';
