<?php
$config->my->testtask = new stdclass();
$config->my->testtask->dtable = new stdclass();
$config->my->testtask->dtable->fieldList['id']['name']  = 'id';
$config->my->testtask->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->testtask->dtable->fieldList['id']['type']  = 'id';
$config->my->testtask->dtable->fieldList['id']['show']  = true;

$config->my->testtask->dtable->fieldList['name']['name']     = 'name';
$config->my->testtask->dtable->fieldList['name']['title']    = $lang->testtask->name;
$config->my->testtask->dtable->fieldList['name']['type']     = 'title';
$config->my->testtask->dtable->fieldList['name']['link']     = array('module' => 'testtask', 'method' => 'cases', 'params' => 'taskID={id}');
$config->my->testtask->dtable->fieldList['name']['fixed']    = 'left';
$config->my->testtask->dtable->fieldList['name']['show']     = true;
$config->my->testtask->dtable->fieldList['name']['data-app'] = 'qa';

$config->my->testtask->dtable->fieldList['pri']['name']  = 'pri';
$config->my->testtask->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->my->testtask->dtable->fieldList['pri']['type']  = 'pri';
$config->my->testtask->dtable->fieldList['pri']['show']  = true;

$config->my->testtask->dtable->fieldList['productName']['name']  = 'productName';
$config->my->testtask->dtable->fieldList['productName']['title'] = $lang->testtask->product;
$config->my->testtask->dtable->fieldList['productName']['type']  = 'text';
$config->my->testtask->dtable->fieldList['productName']['group'] = 'text';
$config->my->testtask->dtable->fieldList['productName']['show']  = true;

$config->my->testtask->dtable->fieldList['buildName']['name']     = 'buildName';
$config->my->testtask->dtable->fieldList['buildName']['title']    = $lang->testtask->build;
$config->my->testtask->dtable->fieldList['buildName']['type']     = 'text';
$config->my->testtask->dtable->fieldList['buildName']['link']     = array('module' => 'build', 'method' => 'view', 'params' => 'buildID={build}');
$config->my->testtask->dtable->fieldList['buildName']['data-app'] = 'project';
$config->my->testtask->dtable->fieldList['buildName']['group']    = 'text';
$config->my->testtask->dtable->fieldList['buildName']['show']     = true;

$config->my->testtask->dtable->fieldList['executionName']['name']  = 'executionName';
$config->my->testtask->dtable->fieldList['executionName']['title'] = $lang->testtask->execution;
$config->my->testtask->dtable->fieldList['executionName']['type']  = 'text';
$config->my->testtask->dtable->fieldList['executionName']['group'] = 'text';
$config->my->testtask->dtable->fieldList['executionName']['show']  = true;

$config->my->testtask->dtable->fieldList['owner']['name']  = 'owner';
$config->my->testtask->dtable->fieldList['owner']['title'] = $lang->testtask->owner;
$config->my->testtask->dtable->fieldList['owner']['type']  = 'user';
$config->my->testtask->dtable->fieldList['owner']['group'] = 'user';
$config->my->testtask->dtable->fieldList['owner']['show']  = true;

$config->my->testtask->dtable->fieldList['members']['name']  = 'members';
$config->my->testtask->dtable->fieldList['members']['title'] = $lang->testtask->members;
$config->my->testtask->dtable->fieldList['members']['type']  = 'text';
$config->my->testtask->dtable->fieldList['members']['group'] = 'user';
$config->my->testtask->dtable->fieldList['members']['show']  = true;

$config->my->testtask->dtable->fieldList['begin']['name']  = 'begin';
$config->my->testtask->dtable->fieldList['begin']['title'] = $lang->testtask->begin;
$config->my->testtask->dtable->fieldList['begin']['type']  = 'date';
$config->my->testtask->dtable->fieldList['begin']['group'] = 'user';
$config->my->testtask->dtable->fieldList['begin']['show']  = true;

$config->my->testtask->dtable->fieldList['end']['name']  = 'end';
$config->my->testtask->dtable->fieldList['end']['title'] = $lang->testtask->end;
$config->my->testtask->dtable->fieldList['end']['type']  = 'date';
$config->my->testtask->dtable->fieldList['end']['group'] = 'user';
$config->my->testtask->dtable->fieldList['end']['show']  = true;

$config->my->testtask->dtable->fieldList['status']['name']      = 'status';
$config->my->testtask->dtable->fieldList['status']['title']     = $lang->testtask->status;
$config->my->testtask->dtable->fieldList['status']['type']      = 'status';
$config->my->testtask->dtable->fieldList['status']['statusMap'] = $lang->testtask->statusList;
$config->my->testtask->dtable->fieldList['status']['group']     = 'status';

$config->my->testtask->dtable->fieldList['actions']['name']     = 'actions';
$config->my->testtask->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->testtask->dtable->fieldList['actions']['type']     = 'actions';
$config->my->testtask->dtable->fieldList['actions']['sortType'] = false;
$config->my->testtask->dtable->fieldList['actions']['width']    = '120px';
$config->my->testtask->dtable->fieldList['actions']['list']     = $config->testtask->actionList;
$config->my->testtask->dtable->fieldList['actions']['menu']     = array(array('start', 'other' => array('activate', 'close')), 'cases', 'linkCase', 'report', 'view', 'edit', 'delete');
