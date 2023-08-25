<?php
global $lang, $app;
$app->loadLang('serverroom');
$app->loadLang('zahost');

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

$config->host->dtable->fieldList['heartbeat']['title']    = $lang->host->registerDate;
$config->host->dtable->fieldList['heartbeat']['name']     = 'heartbeat';
$config->host->dtable->fieldList['heartbeat']['type']     = 'datetime';
$config->host->dtable->fieldList['heartbeat']['sortType'] = true;
$config->host->dtable->fieldList['heartbeat']['width']    = 80;
$config->host->dtable->fieldList['heartbeat']['map']      = $lang->host->statusList;

$config->host->actionList = array();
$config->host->actionList['online']['icon']        = 'arrow-up';
$config->host->actionList['online']['text']        = $lang->host->online;
$config->host->actionList['online']['hint']        = $lang->host->online;
$config->host->actionList['online']['data-toggle'] = 'modal';
$config->host->actionList['online']['url']         = helper::createLink('host', 'changeStatus', 'id={id}&status=offline');


$config->host->actionList['offline']['icon']        = 'arrow-down';
$config->host->actionList['offline']['text']        = $lang->host->offline;
$config->host->actionList['offline']['hint']        = $lang->host->offline;
$config->host->actionList['offline']['data-toggle'] = 'modal';
$config->host->actionList['offline']['url']         = helper::createLink('host', 'changeStatus', 'id={id}&status=online');

$config->host->actionList['edit']['icon']        = 'edit';
$config->host->actionList['edit']['text']        = $lang->edit;
$config->host->actionList['edit']['hint']        = $lang->edit;
$config->host->actionList['edit']['showText']    = true;
$config->host->actionList['edit']['url']         = array('module' => 'host', 'method' => 'edit', 'params' => 'id={id}');

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

$config->host->imageDtable = new stdclass();

$config->host->imageDtable->fieldList['name']['title']    = $lang->zahost->image->name;
$config->host->imageDtable->fieldList['name']['name']     = 'name';
$config->host->imageDtable->fieldList['name']['sortType'] = true;
$config->host->imageDtable->fieldList['name']['width']    = 120;
$config->host->imageDtable->fieldList['name']['hint']     = true;

$config->host->imageDtable->fieldList['osName']['title']    = $lang->zahost->image->os;
$config->host->imageDtable->fieldList['osName']['name']     = 'osName';
$config->host->imageDtable->fieldList['osName']['sortType'] = true;
$config->host->imageDtable->fieldList['osName']['width']    = 120;
$config->host->imageDtable->fieldList['osName']['hint']     = true;

$config->host->imageDtable->fieldList['status']['title']    = $lang->zahost->status;
$config->host->imageDtable->fieldList['status']['name']     = 'status';
$config->host->imageDtable->fieldList['status']['sortType'] = false;
$config->host->imageDtable->fieldList['status']['width']    = 100;
$config->host->imageDtable->fieldList['status']['map']      = $lang->zahost->image->statusList;

$config->host->imageDtable->fieldList['path']['title']    = $lang->zahost->image->path;
$config->host->imageDtable->fieldList['path']['name']     = 'path';
$config->host->imageDtable->fieldList['path']['type']     = 'desc';
$config->host->imageDtable->fieldList['path']['sortType'] = false;
$config->host->imageDtable->fieldList['path']['hint']     = true;

$config->host->imageDtable->fieldList['progress']['title']    = $lang->zahost->image->progress;
$config->host->imageDtable->fieldList['progress']['name']     = 'progress';
$config->host->imageDtable->fieldList['progress']['sortType'] = false;
$config->host->imageDtable->fieldList['progress']['width']    = 120;
$config->host->imageDtable->fieldList['progress']['hint']     = true;

$config->host->imageActionList = array();
$config->host->imageActionList['download']['icon']         = 'download';
$config->host->imageActionList['download']['text']         = $lang->zahost->image->downloadImage;
$config->host->imageActionList['download']['hint']         = $lang->zahost->image->downloadImage;
$config->host->imageActionList['download']['url']          = helper::createLink('host', 'downloadImage', 'imageID={id}');
$config->host->imageActionList['download']['ajaxSubmit']   = true;
$config->host->imageActionList['download']['data-confirm'] = false;

$config->host->imageActionList['cancel']['icon']         = 'ban-circle';
$config->host->imageActionList['cancel']['text']         = $lang->cancel;
$config->host->imageActionList['cancel']['hint']         = $lang->cancel;
$config->host->imageActionList['cancel']['url']          = helper::createLink('host', 'cancelDownload', 'id={id}');
$config->host->imageActionList['cancel']['ajaxSubmit']   = true;
$config->host->imageActionList['cancel']['data-confirm'] = $lang->zahost->cancelDelete;

$config->host->imageDtable->fieldList['actions']['name']     = 'actions';
$config->host->imageDtable->fieldList['actions']['title']    = $lang->actions;
$config->host->imageDtable->fieldList['actions']['type']     = 'actions';
$config->host->imageDtable->fieldList['actions']['sortType'] = false;
$config->host->imageDtable->fieldList['actions']['fixed']    = 'right';
$config->host->imageDtable->fieldList['actions']['menu']     = array('download', 'cancel');
$config->host->imageDtable->fieldList['actions']['list']     = $config->host->imageActionList;
