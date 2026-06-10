<?php
$config->my->testtask = new stdclass();
$config->my->testtask->dtable = new stdclass();
$config->my->testtask->dtable->fieldList['id']['name']     = 'id';
$config->my->testtask->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->testtask->dtable->fieldList['id']['type']     = 'id';
$config->my->testtask->dtable->fieldList['id']['sortType'] = true;

$config->my->testtask->dtable->fieldList['title']['name']     = 'name';
$config->my->testtask->dtable->fieldList['title']['title']    = $lang->testtask->name;
$config->my->testtask->dtable->fieldList['title']['type']     = 'title';
$config->my->testtask->dtable->fieldList['title']['link']     = array('module' => 'testtask', 'method' => 'cases', 'params' => 'taskID={id}');
$config->my->testtask->dtable->fieldList['title']['fixed']    = 'left';
$config->my->testtask->dtable->fieldList['title']['sortType'] = true;
$config->my->testtask->dtable->fieldList['title']['data-app'] = 'qa';

$config->my->testtask->dtable->fieldList['pri']['name']  = 'pri';
$config->my->testtask->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->my->testtask->dtable->fieldList['pri']['type']  = 'pri';
$config->my->testtask->dtable->fieldList['pri']['show']  = true;

$config->my->testtask->dtable->fieldList['build']['name']     = 'buildName';
$config->my->testtask->dtable->fieldList['build']['title']    = $lang->testtask->build;
$config->my->testtask->dtable->fieldList['build']['type']     = 'text';
$config->my->testtask->dtable->fieldList['build']['link']     = array('module' => 'build', 'method' => 'view', 'params' => 'buildID={build}');
$config->my->testtask->dtable->fieldList['build']['group']    = 'text';
$config->my->testtask->dtable->fieldList['build']['sortType'] = true;

$config->my->testtask->dtable->fieldList['execution']['name']     = 'executionName';
$config->my->testtask->dtable->fieldList['execution']['title']    = $lang->testtask->execution;
$config->my->testtask->dtable->fieldList['execution']['type']     = 'text';
$config->my->testtask->dtable->fieldList['execution']['group']    = 'text';
$config->my->testtask->dtable->fieldList['execution']['sortType'] = true;

$config->my->testtask->dtable->fieldList['status']['name']      = 'status';
$config->my->testtask->dtable->fieldList['status']['title']     = $lang->testtask->status;
$config->my->testtask->dtable->fieldList['status']['type']      = 'status';
$config->my->testtask->dtable->fieldList['status']['statusMap'] = $lang->testtask->statusList;
$config->my->testtask->dtable->fieldList['status']['group']     = 'text';
$config->my->testtask->dtable->fieldList['status']['sortType']  = true;

$config->my->testtask->dtable->fieldList['begin']['name']     = 'begin';
$config->my->testtask->dtable->fieldList['begin']['title']    = $lang->testtask->begin;
$config->my->testtask->dtable->fieldList['begin']['type']     = 'date';
$config->my->testtask->dtable->fieldList['begin']['group']    = 'user';
$config->my->testtask->dtable->fieldList['begin']['sortType'] = true;

$config->my->testtask->dtable->fieldList['end']['name']     = 'end';
$config->my->testtask->dtable->fieldList['end']['title']    = $lang->testtask->end;
$config->my->testtask->dtable->fieldList['end']['type']     = 'date';
$config->my->testtask->dtable->fieldList['end']['group']    = 'user';
$config->my->testtask->dtable->fieldList['end']['sortType'] = true;

$config->my->testtask->dtable->fieldList['actions']['name']     = 'actions';
$config->my->testtask->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->testtask->dtable->fieldList['actions']['type']     = 'actions';
$config->my->testtask->dtable->fieldList['actions']['sortType'] = false;
$config->my->testtask->dtable->fieldList['actions']['list']     = $config->testtask->actionList;
$config->my->testtask->dtable->fieldList['actions']['menu']     = array('cases', 'linkCase', 'report', 'view', 'edit', 'delete');

$config->my->testtask->dtable->fieldList['actions']['list']['edit']['data-toggle'] = 'modal';
$config->my->testtask->dtable->fieldList['actions']['list']['edit']['data-size']   = 'lg';
