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

$config->testtask->cases->dtable = new stdclass();

foreach($config->testcase->dtable->fieldList as $field => $setting)
{
    if($field == 'actions') continue;

    $config->testtask->cases->dtable->fieldList[$field] = $setting;

    if($field == 'title') $config->testtask->cases->dtable->fieldList[$field]['nestedToggle'] = false;
    if($field == 'story') $config->testtask->cases->dtable->fieldList[$field]['name']         = 'storyTitle';

    if($field == 'keywords')
    {
        $config->testtask->cases->dtable->fieldList['assignedTo']['title'] = $lang->testcase->assignedTo;
        $config->testtask->cases->dtable->fieldList['assignedTo']['type']  = 'user';
        $config->testtask->cases->dtable->fieldList['assignedTo']['show']  = true;
        $config->testtask->cases->dtable->fieldList['assignedTo']['group'] = 99; // Set a different group between testcase.
    }
}

$config->testtask->cases->dtable->fieldList['actions']['name']  = 'actions';
$config->testtask->cases->dtable->fieldList['actions']['title'] = $lang->actions;
$config->testtask->cases->dtable->fieldList['actions']['type']  = 'actions';
$config->testtask->cases->dtable->fieldList['actions']['list']  = $config->testtask->cases->actionList;
$config->testtask->cases->dtable->fieldList['actions']['menu']  = array(array('confirmChange'), array('createBug', 'runCase', 'results', 'unlinkCase'));

$config->testtask->linkcase = new stdclass();
$config->testtask->linkcase->dtable = new stdclass();
$config->testtask->linkcase->dtable->fieldList['id']     = $config->testcase->dtable->fieldList['id'];
$config->testtask->linkcase->dtable->fieldList['title']  = $config->testcase->dtable->fieldList['title'];
$config->testtask->linkcase->dtable->fieldList['pri']    = $config->testcase->dtable->fieldList['pri'];
$config->testtask->linkcase->dtable->fieldList['type']   = $config->testcase->dtable->fieldList['type'];
$config->testtask->linkcase->dtable->fieldList['status'] = $config->testcase->dtable->fieldList['status'];

$config->testtask->linkcase->dtable->fieldList['version']['name']  = 'version';
$config->testtask->linkcase->dtable->fieldList['version']['title'] = $lang->testtask->linkVersion;
$config->testtask->linkcase->dtable->fieldList['version']['type']  = 'text';
$config->testtask->linkcase->dtable->fieldList['version']['group'] = 'version';

$config->testtask->linkcase->dtable->fieldList['openedBy']      = $config->testcase->dtable->fieldList['openedBy'];
$config->testtask->linkcase->dtable->fieldList['lastRunner']    = $config->testcase->dtable->fieldList['lastRunner'];
$config->testtask->linkcase->dtable->fieldList['lastRunDate']   = $config->testcase->dtable->fieldList['lastRunDate'];
$config->testtask->linkcase->dtable->fieldList['lastRunResult'] = $config->testcase->dtable->fieldList['lastRunResult'];
unset($config->testtask->linkcase->dtable->fieldList['title']['nestedToggle']);
