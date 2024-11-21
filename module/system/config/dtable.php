<?php
global $lang, $app;
$app->loadLang('instance');
$app->loadLang('backup');

$config->system->dtable = new stdclass();

$config->system->dtable->fieldList['id']['title']    = 'ID';
$config->system->dtable->fieldList['id']['name']     = 'id';
$config->system->dtable->fieldList['id']['type']     = 'id';
$config->system->dtable->fieldList['id']['sortType'] = true;

$config->system->dtable->fieldList['name']['title'] = $lang->system->name;
$config->system->dtable->fieldList['name']['name']  = 'name';
$config->system->dtable->fieldList['name']['type']  = 'title';
$config->system->dtable->fieldList['name']['flex']  = 4;
$config->system->dtable->fieldList['name']['hint']  = true;

$config->system->dtable->fieldList['latestRelease']['title'] = $lang->system->latestRelease;
$config->system->dtable->fieldList['latestRelease']['name']  = 'latestRelease';
$config->system->dtable->fieldList['latestRelease']['type']  = 'text';

$config->system->dtable->fieldList['children']['title']     = $lang->system->children;
$config->system->dtable->fieldList['children']['name']      = 'children';
$config->system->dtable->fieldList['children']['type']      = 'text';
$config->system->dtable->fieldList['children']['delimiter'] = ',';

$config->system->dtable->fieldList['status']['title']     = $lang->system->status;
$config->system->dtable->fieldList['status']['name']      = 'status';
$config->system->dtable->fieldList['status']['type']      = 'status';
$config->system->dtable->fieldList['status']['statusMap'] = $lang->system->statusList;
$config->system->dtable->fieldList['status']['width']     = 100;
$config->system->dtable->fieldList['status']['sortType']  = true;

$config->system->dtable->fieldList['actions']['name']     = 'actions';
$config->system->dtable->fieldList['actions']['title']    = $lang->actions;
$config->system->dtable->fieldList['actions']['type']     = 'actions';
$config->system->dtable->fieldList['actions']['sortType'] = false;
$config->system->dtable->fieldList['actions']['fixed']    = 'right';
$config->system->dtable->fieldList['actions']['menu']     = array('active|inactive', 'edit', 'delete');
$config->system->dtable->fieldList['actions']['list']     = $config->system->actionList;
$config->system->dtable->fieldList['actions']['width']    = 100;

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

$config->system->dtable->backup->fieldList['time']['title']       = $lang->backup->time;
$config->system->dtable->backup->fieldList['time']['type']        = 'datetime';
$config->system->dtable->backup->fieldList['time']['formatDate']  = 'yyyy-MM-dd hh:mm:ss';
$config->system->dtable->backup->fieldList['time']['sort']        = false;

$config->system->dtable->backup->fieldList['name']['title'] = $lang->backup->name;
$config->system->dtable->backup->fieldList['name']['sort']  = false;

$config->system->dtable->backup->fieldList['creator']['title'] = $lang->system->backup->creator;
$config->system->dtable->backup->fieldList['creator']['type']  = 'user';
$config->system->dtable->backup->fieldList['creator']['sort']  = false;

$config->system->dtable->backup->fieldList['type']['title']    = $lang->system->backup->type;
$config->system->dtable->backup->fieldList['type']['map']      = $lang->system->backup->typeList;
$config->system->dtable->backup->fieldList['type']['m-width']  = '40px';

$config->system->dtable->backup->fieldList['status']['title']  = $lang->backup->status;
$config->system->dtable->backup->fieldList['status']['hidden'] = false;
$config->system->dtable->backup->fieldList['status']['map']    = $lang->system->backup->statusList;
$config->system->dtable->backup->fieldList['status']['width']  = '20px';

$config->system->dtable->backup->fieldList['actions']['name']  = 'actions';
$config->system->dtable->backup->fieldList['actions']['title'] = $lang->actions;
$config->system->dtable->backup->fieldList['actions']['type']  = 'actions';
$config->system->dtable->backup->fieldList['actions']['menu']  = array('restore', 'delete');

$config->system->dtable->backup->fieldList['actions']['list']['restore']['icon']         = 'history';
$config->system->dtable->backup->fieldList['actions']['list']['restore']['text']         = $lang->backup->restore;
$config->system->dtable->backup->fieldList['actions']['list']['restore']['hint']         = $lang->backup->restore;
$config->system->dtable->backup->fieldList['actions']['list']['restore']['url']          = array('module' => 'system', 'method' => 'restoreBackup', 'params' => 'name={id}');
$config->system->dtable->backup->fieldList['actions']['list']['restore']['data-confirm'] = $lang->system->backup->confirmRestore;
$config->system->dtable->backup->fieldList['actions']['list']['restore']['className']    = 'ajax-submit origin-action';

$config->system->dtable->backup->fieldList['actions']['list']['delete']['icon']         = 'trash';
$config->system->dtable->backup->fieldList['actions']['list']['delete']['text']         = $lang->system->backup->delete;
$config->system->dtable->backup->fieldList['actions']['list']['delete']['hint']         = $lang->delete;
$config->system->dtable->backup->fieldList['actions']['list']['delete']['url']          = array('module' => 'system', 'method' => 'deleteBackup', 'params' => 'name={id}');
$config->system->dtable->backup->fieldList['actions']['list']['delete']['data-confirm'] = array('message' => $lang->system->backup->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->system->dtable->backup->fieldList['actions']['list']['delete']['className']    = 'ajax-submit origin-action';

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

$config->system->dtable->instanceList->fieldList['mem']['title']    = array('html' => '<i class="icon icon-memory mr-1"></i>' . $lang->instance->mem);
$config->system->dtable->instanceList->fieldList['mem']['name']     = 'mem';
$config->system->dtable->instanceList->fieldList['mem']['minWidth'] = '230';
