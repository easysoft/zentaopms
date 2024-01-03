<?php
global $lang, $app;
$app->loadLang('serverroom');

$config->host->dtable = new stdclass();

$config->host->dtable->fieldList['id']['title'] = 'ID';
$config->host->dtable->fieldList['id']['name']  = 'id';
$config->host->dtable->fieldList['id']['type']  = 'id';

$config->host->dtable->fieldList['group']['title']    = $lang->host->group;
$config->host->dtable->fieldList['group']['name']     = 'group';
$config->host->dtable->fieldList['group']['type']     = 'text';
$config->host->dtable->fieldList['group']['sortType'] = true;
$config->host->dtable->fieldList['group']['width']    = 80;

$config->host->dtable->fieldList['name']['title']    = $lang->host->name;
$config->host->dtable->fieldList['name']['name']     = 'name';
$config->host->dtable->fieldList['name']['type']     = 'desc';
$config->host->dtable->fieldList['name']['sortType'] = true;
$config->host->dtable->fieldList['name']['flex']     = 4;
$config->host->dtable->fieldList['name']['hint']     = true;
$config->host->dtable->fieldList['name']['link']     = array('module' => 'host', 'method' => 'view', 'params' => 'id={id}');
$config->host->dtable->fieldList['name']['data-toggle'] = 'modal';

$config->host->dtable->fieldList['admin']['title']    = $lang->host->admin;
$config->host->dtable->fieldList['admin']['name']     = 'admin';
$config->host->dtable->fieldList['admin']['type']     = 'text';
$config->host->dtable->fieldList['admin']['sortType'] = true;
$config->host->dtable->fieldList['admin']['link']     = array('module' => 'account', 'method' => 'view', 'params' => 'id={admin}');
$config->host->dtable->fieldList['admin']['data-toggle'] = 'modal';

$config->host->dtable->fieldList['serverRoom']['title']    = $lang->host->serverRoom;
$config->host->dtable->fieldList['serverRoom']['name']     = 'serverRoom';
$config->host->dtable->fieldList['serverRoom']['type']     = 'text';
$config->host->dtable->fieldList['serverRoom']['sortType'] = true;
$config->host->dtable->fieldList['serverRoom']['link']     = array('module' => 'serverroom', 'method' => 'view', 'params' => 'id={serverRoom}');
$config->host->dtable->fieldList['serverRoom']['data-toggle'] = 'modal';

$config->host->dtable->fieldList['intranet']['title']    = $lang->host->intranet;
$config->host->dtable->fieldList['intranet']['name']     = 'intranet';
$config->host->dtable->fieldList['intranet']['type']     = 'text';
$config->host->dtable->fieldList['intranet']['sortType'] = true;

$config->host->dtable->fieldList['extranet']['title']    = $lang->host->extranet;
$config->host->dtable->fieldList['extranet']['name']     = 'extranet';
$config->host->dtable->fieldList['extranet']['type']     = 'text';
$config->host->dtable->fieldList['extranet']['sortType'] = true;

$config->host->dtable->fieldList['osVersion']['title']    = $lang->host->osVersion;
$config->host->dtable->fieldList['osVersion']['name']     = 'osVersion';
$config->host->dtable->fieldList['osVersion']['type']     = 'text';
$config->host->dtable->fieldList['osVersion']['sortType'] = true;

$config->host->dtable->fieldList['status']['title']    = $lang->host->status;
$config->host->dtable->fieldList['status']['name']     = 'status';
$config->host->dtable->fieldList['status']['type']     = 'text';
$config->host->dtable->fieldList['status']['sortType'] = true;
$config->host->dtable->fieldList['status']['width']    = 80;
$config->host->dtable->fieldList['status']['map']      = $lang->host->statusList;

$config->host->actionList = array();
$config->host->actionList['online']['icon']        = 'arrow-up';
$config->host->actionList['online']['text']        = $lang->host->online;
$config->host->actionList['online']['hint']        = $lang->host->online;
$config->host->actionList['online']['data-toggle'] = 'modal';
$config->host->actionList['online']['url']         = array('module' => 'host', 'method' => 'changeStatus', 'params' => 'id={id}&status=online');

$config->host->actionList['offline']['icon']        = 'arrow-down';
$config->host->actionList['offline']['text']        = $lang->host->offline;
$config->host->actionList['offline']['hint']        = $lang->host->offline;
$config->host->actionList['offline']['data-toggle'] = 'modal';
$config->host->actionList['offline']['url']         = array('module' => 'host', 'method' => 'changeStatus', 'params' => 'id={id}&status=offline');

$config->host->actionList['edit']['icon']        = 'edit';
$config->host->actionList['edit']['text']        = $lang->edit;
$config->host->actionList['edit']['hint']        = $lang->edit;
$config->host->actionList['edit']['showText']    = true;
$config->host->actionList['edit']['url']         = array('module' => 'host', 'method' => 'edit', 'params' => 'id={id}');
if(isInModal()) $config->host->actionList['edit']['data-load'] = 'modal';

$config->host->actionList['delete']['icon']         = 'trash';
$config->host->actionList['delete']['text']         = $lang->delete;
$config->host->actionList['delete']['hint']         = $lang->delete;
$config->host->actionList['delete']['showText']     = true;
$config->host->actionList['delete']['ajaxSubmit']   = true;
$config->host->actionList['delete']['data-confirm'] = $lang->host->confirmDelete;
$config->host->actionList['delete']['url']          = array('module' => 'host', 'method' => 'delete', 'params' => 'id={id}');

$config->host->dtable->fieldList['actions']['name']     = 'actions';
$config->host->dtable->fieldList['actions']['title']    = $lang->actions;
$config->host->dtable->fieldList['actions']['type']     = 'actions';
$config->host->dtable->fieldList['actions']['sortType'] = false;
$config->host->dtable->fieldList['actions']['fixed']    = 'right';
$config->host->dtable->fieldList['actions']['menu']     = array('online|offline', 'edit', 'delete');
$config->host->dtable->fieldList['actions']['list']     = $config->host->actionList;
