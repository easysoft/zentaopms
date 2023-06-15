<?php
global $lang, $app;
$app->loadLang('testcase');
$app->loadModuleConfig('testcase');

$config->testtask->dtable = new stdclass();
$config->testtask->dtable->fieldList['id']['name']  = 'id';
$config->testtask->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testtask->dtable->fieldList['id']['type']  = 'id';

$config->testtask->dtable->fieldList['title']['name']  = 'name';
$config->testtask->dtable->fieldList['title']['title'] = $lang->testtask->name;
$config->testtask->dtable->fieldList['title']['type']  = 'title';
$config->testtask->dtable->fieldList['title']['link']  = array('module' => 'testtask', 'method' => 'cases', 'params' => 'taskID={id}');
$config->testtask->dtable->fieldList['title']['fixed'] = 'left';

$config->testtask->dtable->fieldList['build']['name']  = 'buildName';
$config->testtask->dtable->fieldList['build']['title'] = $lang->testtask->build;
$config->testtask->dtable->fieldList['build']['type']  = 'text';
$config->testtask->dtable->fieldList['build']['link']  = array('module' => 'build', 'method' => 'view', 'params' => 'buildID={build}');
$config->testtask->dtable->fieldList['build']['group'] = 'text';

$config->testtask->dtable->fieldList['product']['name']  = 'productName';
$config->testtask->dtable->fieldList['product']['title'] = $lang->testtask->product;
$config->testtask->dtable->fieldList['product']['type']  = 'text';
$config->testtask->dtable->fieldList['product']['group'] = 'text';

$config->testtask->dtable->fieldList['execution']['name']  = 'executionName';
$config->testtask->dtable->fieldList['execution']['title'] = $lang->testtask->execution;
$config->testtask->dtable->fieldList['execution']['type']  = 'text';
$config->testtask->dtable->fieldList['execution']['group'] = 'text';

$config->testtask->dtable->fieldList['owner']['name']    = 'owner';
$config->testtask->dtable->fieldList['owner']['title']   = $lang->testtask->owner;
$config->testtask->dtable->fieldList['owner']['type']    = 'user';
$config->testtask->dtable->fieldList['owner']['group']   = 'user';

$config->testtask->dtable->fieldList['begin']['name']  = 'begin';
$config->testtask->dtable->fieldList['begin']['title'] = $lang->testtask->begin;
$config->testtask->dtable->fieldList['begin']['type']  = 'date';
$config->testtask->dtable->fieldList['begin']['group'] = 'user';

$config->testtask->dtable->fieldList['end']['name']  = 'end';
$config->testtask->dtable->fieldList['end']['title'] = $lang->testtask->end;
$config->testtask->dtable->fieldList['end']['type']  = 'date';
$config->testtask->dtable->fieldList['end']['group'] = 'user';

$config->testtask->dtable->fieldList['status']['name']      = 'status';
$config->testtask->dtable->fieldList['status']['title']     = $lang->testtask->status;
$config->testtask->dtable->fieldList['status']['type']      = 'status';
$config->testtask->dtable->fieldList['status']['statusMap'] = $lang->testtask->statusList;

$config->testtask->dtable->fieldList['actions']['name']     = 'actions';
$config->testtask->dtable->fieldList['actions']['title']    = $lang->actions;
$config->testtask->dtable->fieldList['actions']['type']     = 'actions';
$config->testtask->dtable->fieldList['actions']['sortType'] = false;
$config->testtask->dtable->fieldList['actions']['list']     = $config->testtask->actionList;
$config->testtask->dtable->fieldList['actions']['menu']     = array('cases', 'linkCase', 'report', 'view', 'edit', 'delete');

$config->testtask->testcase = new stdclass();
$config->testtask->testcase->dtable = new stdclass();
$config->testtask->testcase->dtable->fieldList['id']['name']  = 'id';
$config->testtask->testcase->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testtask->testcase->dtable->fieldList['id']['type'] = 'checkID';
$config->testtask->testcase->dtable->fieldList['id']['fixed'] = 'left';

$config->testtask->testcase->dtable->fieldList['title']['name']  = 'title';
$config->testtask->testcase->dtable->fieldList['title']['title'] = $lang->testcase->title;
$config->testtask->testcase->dtable->fieldList['title']['type']  = 'title';
$config->testtask->testcase->dtable->fieldList['title']['link']  = array('module' => 'testcase', 'method' => 'view', 'params' => 'caseID={id}&version={version}&from=testtask&taskID={task}');
$config->testtask->testcase->dtable->fieldList['title']['fixed'] = 'left';

foreach($config->testcase->dtable->fieldList as $key => $fieldList)
{
    if($key == 'id' || $key == 'title') continue;
    $config->testtask->testcase->dtable->fieldList[$key] = $fieldList;

    if($key == 'keywords')
    {
        $config->testtask->testcase->dtable->fieldList['assignedTo']['name']  = 'assignedTo';
        $config->testtask->testcase->dtable->fieldList['assignedTo']['title'] = $lang->testcase->assignedTo;
        $config->testtask->testcase->dtable->fieldList['assignedTo']['type']  = 'user';
    }
}

$config->testtask->testcase->dtable->fieldList['actions']['menu'] = array('runCase', 'runResult', 'createBug', 'unlinkCase');
