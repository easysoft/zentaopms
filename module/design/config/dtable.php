<?php
global $lang,$app;
$config->design->dtable = new stdclass();

$config->design->dtable->fieldList['id']['title'] = $lang->idAB;
$config->design->dtable->fieldList['id']['type']  = 'id';

$config->design->dtable->fieldList['name']['type'] = 'title';
$config->design->dtable->fieldList['name']['link'] = array('module' => 'design', 'method' => 'view', 'params' => 'designID={id}');

$config->design->dtable->fieldList['product']['type'] = 'desc';

$config->design->dtable->fieldList['type']['type']      = 'status';
$config->design->dtable->fieldList['type']['statusMap'] = $lang->design->typeList;
$config->design->dtable->fieldList['type']['sortType']  = false;

$config->design->dtable->fieldList['assignedTo']['type']        = 'assign';
$config->design->dtable->fieldList['assignedTo']['currentUser'] = $app->user->account;
$config->design->dtable->fieldList['assignedTo']['assignLink']  = array('module' => 'design', 'method' => 'assignTo', 'params' => 'designID={id}');
$config->design->dtable->fieldList['assignedTo']['data-toggle'] = 'modal';
$config->design->dtable->fieldList['assignedTo']['sortType']    = false;

$config->design->dtable->fieldList['createdBy']['type']     = 'user';
$config->design->dtable->fieldList['createdBy']['sortType'] = false;

$config->design->dtable->fieldList['createdDate']['type'] = 'date';

$config->design->dtable->fieldList['actions']['type'] = 'actions';
$config->design->dtable->fieldList['actions']['menu'] = array('edit', 'viewCommit', 'delete');
$config->design->dtable->fieldList['actions']['list'] = $config->design->actionList;
