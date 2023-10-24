<?php
global $lang, $app;
$app->loadLang('job');

$config->compile->dtable = new stdclass();

$config->compile->dtable->fieldList['id']['title']    = 'ID';
$config->compile->dtable->fieldList['id']['name']     = 'id';
$config->compile->dtable->fieldList['id']['type']     = 'id';
$config->compile->dtable->fieldList['id']['checkbox'] = false;

$config->compile->dtable->fieldList['name']['title']       = $lang->compile->name;
$config->compile->dtable->fieldList['name']['name']        = 'name';
$config->compile->dtable->fieldList['name']['type']        = 'shortTitle';
$config->compile->dtable->fieldList['name']['sortType']    = true;
$config->compile->dtable->fieldList['name']['hint']        = true;
$config->compile->dtable->fieldList['name']['checkbox']    = false;
$config->compile->dtable->fieldList['name']['link']        = array('module' => 'job', 'method' => 'view', 'params' => 'jobID={job}&compileID={id}');
$config->compile->dtable->fieldList['name']['data-toggle'] = 'modal';

$config->compile->dtable->fieldList['status']['title']    = $lang->compile->status;
$config->compile->dtable->fieldList['status']['name']     = 'status';
$config->compile->dtable->fieldList['status']['type']     = 'text';
$config->compile->dtable->fieldList['status']['sortType'] = true;
$config->compile->dtable->fieldList['status']['hint']     = true;
$config->compile->dtable->fieldList['status']['width']    = '80';
$config->compile->dtable->fieldList['status']['map']      = $lang->compile->statusList;

$config->compile->dtable->fieldList['repo']['title']    = $lang->compile->repo;
$config->compile->dtable->fieldList['repo']['name']     = 'repoName';
$config->compile->dtable->fieldList['repo']['type']     = 'shortTitle';
$config->compile->dtable->fieldList['repo']['sortType'] = false;
$config->compile->dtable->fieldList['repo']['hint']     = true;
$config->compile->dtable->fieldList['repo']['checkbox'] = false;
$config->compile->dtable->fieldList['repo']['fixed']    = false;

$config->compile->dtable->fieldList['buildType']['title']    = $lang->compile->buildType;
$config->compile->dtable->fieldList['buildType']['name']     = 'engine';
$config->compile->dtable->fieldList['buildType']['type']     = 'text';
$config->compile->dtable->fieldList['buildType']['sortType'] = false;
$config->compile->dtable->fieldList['buildType']['hint']     = true;
$config->compile->dtable->fieldList['buildType']['width']    = '60';
$config->compile->dtable->fieldList['buildType']['map']      = $lang->job->engineList;

$config->compile->dtable->fieldList['triggerType']['title']    = $lang->job->triggerType;
$config->compile->dtable->fieldList['triggerType']['name']     = 'triggerType';
$config->compile->dtable->fieldList['triggerType']['type']     = 'text';
$config->compile->dtable->fieldList['triggerType']['sortType'] = false;
$config->compile->dtable->fieldList['triggerType']['width']    = '80';
$config->compile->dtable->fieldList['triggerType']['hint']     = true;

$config->compile->dtable->fieldList['createdDate']['title']    = $lang->compile->atTime;
$config->compile->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->compile->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->compile->dtable->fieldList['createdDate']['sortType'] = true;
$config->compile->dtable->fieldList['createdDate']['hint']     = true;

$config->compile->actionList = array();
$config->compile->actionList['logs']['icon'] = 'history';
$config->compile->actionList['logs']['text'] = $lang->compile->logs;
$config->compile->actionList['logs']['hint'] = $lang->compile->logs;
$config->compile->actionList['logs']['url']  = array('module' => 'compile', 'method' => 'logs', 'params' => 'compileID={id}');

$config->compile->actionList['result']['icon'] = 'list-alt';
$config->compile->actionList['result']['text'] = $lang->compile->result;
$config->compile->actionList['result']['hint'] = $lang->compile->result;
$config->compile->actionList['result']['url']  = array('module' => 'testtask', 'method' => 'unitCases', 'params' => 'taskID={testtask}');

$config->compile->dtable->fieldList['actions']['name']     = 'actions';
$config->compile->dtable->fieldList['actions']['title']    = $lang->actions;
$config->compile->dtable->fieldList['actions']['type']     = 'actions';
$config->compile->dtable->fieldList['actions']['sortType'] = false;
$config->compile->dtable->fieldList['actions']['fixed']    = 'right';
$config->compile->dtable->fieldList['actions']['menu']     = array('logs', 'result');
$config->compile->dtable->fieldList['actions']['list']     = $config->compile->actionList;
