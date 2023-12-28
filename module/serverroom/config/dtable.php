<?php
global $lang;

$config->serverroom->dtable = new stdclass();

$config->serverroom->dtable->fieldList['id']['title'] = 'ID';
$config->serverroom->dtable->fieldList['id']['name']  = 'id';
$config->serverroom->dtable->fieldList['id']['type']  = 'id';

$config->serverroom->dtable->fieldList['name']['title']    = $lang->serverroom->name;
$config->serverroom->dtable->fieldList['name']['name']     = 'name';
$config->serverroom->dtable->fieldList['name']['type']     = 'desc';
$config->serverroom->dtable->fieldList['name']['sortType'] = true;
$config->serverroom->dtable->fieldList['name']['flex']     = 4;
$config->serverroom->dtable->fieldList['name']['hint']     = true;
$config->serverroom->dtable->fieldList['name']['link']     = array('module' => 'serverroom', 'method' => 'view', 'params' => 'id={id}');
$config->serverroom->dtable->fieldList['name']['data-toggle'] = 'modal';

$config->serverroom->dtable->fieldList['city']['title']    = $lang->serverroom->city;
$config->serverroom->dtable->fieldList['city']['name']     = 'city';
$config->serverroom->dtable->fieldList['city']['type']     = 'text';
$config->serverroom->dtable->fieldList['city']['sortType'] = true;
$config->serverroom->dtable->fieldList['city']['map']      = $lang->serverroom->cityList;

$config->serverroom->dtable->fieldList['line']['title']    = $lang->serverroom->line;
$config->serverroom->dtable->fieldList['line']['name']     = 'line';
$config->serverroom->dtable->fieldList['line']['type']     = 'text';
$config->serverroom->dtable->fieldList['line']['sortType'] = true;
$config->serverroom->dtable->fieldList['line']['map']      = $lang->serverroom->lineList;

$config->serverroom->dtable->fieldList['bandwidth']['title']    = $lang->serverroom->bandwidth;
$config->serverroom->dtable->fieldList['bandwidth']['name']     = 'bandwidth';
$config->serverroom->dtable->fieldList['bandwidth']['type']     = 'text';
$config->serverroom->dtable->fieldList['bandwidth']['sortType'] = true;
$config->serverroom->dtable->fieldList['bandwidth']['width']    = 80;

$config->serverroom->dtable->fieldList['provider']['title']    = $lang->serverroom->provider;
$config->serverroom->dtable->fieldList['provider']['name']     = 'provider';
$config->serverroom->dtable->fieldList['provider']['type']     = 'text';
$config->serverroom->dtable->fieldList['provider']['sortType'] = true;
$config->serverroom->dtable->fieldList['provider']['width']    = 80;
$config->serverroom->dtable->fieldList['provider']['map']      = $lang->serverroom->providerList;

$config->serverroom->dtable->fieldList['owner']['title']    = $lang->serverroom->owner;
$config->serverroom->dtable->fieldList['owner']['name']     = 'owner';
$config->serverroom->dtable->fieldList['owner']['type']     = 'user';
$config->serverroom->dtable->fieldList['owner']['sortType'] = true;

$config->serverroom->dtable->fieldList['createdBy']['title']    = $lang->serverroom->createdBy;
$config->serverroom->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->serverroom->dtable->fieldList['createdBy']['type']     = 'user';
$config->serverroom->dtable->fieldList['createdBy']['sortType'] = true;

$config->serverroom->dtable->fieldList['createdDate']['title']    = $lang->serverroom->createdDate;
$config->serverroom->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->serverroom->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->serverroom->dtable->fieldList['createdDate']['sortType'] = true;

$config->serverroom->actionList = array();
$config->serverroom->actionList['edit']['icon']     = 'edit';
$config->serverroom->actionList['edit']['text']     = $lang->serverroom->edit;
$config->serverroom->actionList['edit']['hint']     = $lang->serverroom->edit;
$config->serverroom->actionList['edit']['showText'] = true;
$config->serverroom->actionList['edit']['url']      = array('module' => 'serverroom', 'method' => 'edit', 'params' => 'id={id}');
if(isInModal()) $config->serverroom->actionList['edit']['data-load'] = 'modal';

$config->serverroom->actionList['delete']['icon']       = 'trash';
$config->serverroom->actionList['delete']['text']       = $lang->serverroom->delete;
$config->serverroom->actionList['delete']['hint']       = $lang->serverroom->delete;
$config->serverroom->actionList['delete']['ajaxSubmit'] = true;
$config->serverroom->actionList['delete']['url']        = array('module' => 'serverroom', 'method' => 'delete', 'params' => 'id={id}');

$config->serverroom->dtable->fieldList['actions']['name']     = 'actions';
$config->serverroom->dtable->fieldList['actions']['title']    = $lang->actions;
$config->serverroom->dtable->fieldList['actions']['type']     = 'actions';
$config->serverroom->dtable->fieldList['actions']['sortType'] = false;
$config->serverroom->dtable->fieldList['actions']['fixed']    = 'right';
$config->serverroom->dtable->fieldList['actions']['menu']     = array('edit', 'delete');
$config->serverroom->dtable->fieldList['actions']['list']     = $config->serverroom->actionList;
