<?php
$config->provider->dtable = new stdclass();
$config->provider->dtable->fieldList['id']['title'] = $lang->idAB;
$config->provider->dtable->fieldList['id']['type']  = 'id';

$config->provider->dtable->fieldList['name']['title'] = $lang->provider->name;
$config->provider->dtable->fieldList['name']['type']  = 'shortTitle';
$config->provider->dtable->fieldList['name']['hint']  = true;

$config->provider->dtable->fieldList['type']['title']    = $lang->provider->type;
$config->provider->dtable->fieldList['type']['sortType'] = true;
$config->provider->dtable->fieldList['type']['hint']     = true;
$config->provider->dtable->fieldList['type']['width']    = 100;
$config->provider->dtable->fieldList['type']['map']      = $lang->provider->typeList;

$config->provider->dtable->fieldList['url']['title']    = $lang->provider->url;
$config->provider->dtable->fieldList['url']['sortType'] = false;
$config->provider->dtable->fieldList['url']['hint']     = true;

$config->provider->dtable->fieldList['createdBy']['title']    = $lang->provider->createdBy;
$config->provider->dtable->fieldList['createdBy']['type']     = 'user';
$config->provider->dtable->fieldList['createdBy']['sortType'] = true;
$config->provider->dtable->fieldList['createdBy']['hint']     = true;

$config->provider->dtable->fieldList['createdDate']['title']      = $lang->provider->createdDate;
$config->provider->dtable->fieldList['createdDate']['sortType']   = true;
$config->provider->dtable->fieldList['createdDate']['hint']       = true;
$config->provider->dtable->fieldList['createdDate']['type']       = 'datetime';
$config->provider->dtable->fieldList['createdDate']['formatDate'] = 'YYYY-MM-dd hh:mm';

$config->provider->dtable->fieldList['actions']['name']  = 'actions';
$config->provider->dtable->fieldList['actions']['title'] = $lang->actions;
$config->provider->dtable->fieldList['actions']['type']  = 'actions';
$config->provider->dtable->fieldList['actions']['width'] = '100';
$config->provider->dtable->fieldList['actions']['menu']  = array('edit', 'delete');
$config->provider->dtable->fieldList['actions']['list']  = $this->config->provider->actionList;
