<?php
$config->testreport->dtable = new stdclass();

global $lang, $app;

$config->testreport->actionList['edit']['icon']     = 'edit';
$config->testreport->actionList['edit']['hint']     = $lang->testreport->edit;
$config->testreport->actionList['edit']['text']     = $lang->testreport->edit;
$config->testreport->actionList['edit']['url']      = array('module' => 'testreport', 'method' => 'edit', 'params' => 'reportID={id}');
$config->testreport->actionList['edit']['order']    = 5;
$config->testreport->actionList['edit']['show']     = 'clickable';
$config->testreport->actionList['edit']['data-app'] = $app->tab;

$config->testreport->actionList['delete']['icon']         = 'trash';
$config->testreport->actionList['delete']['hint']         = $lang->testreport->delete;
$config->testreport->actionList['delete']['text']         = $lang->testreport->delete;
$config->testreport->actionList['delete']['url']          = array('module' => 'testreport', 'method' => 'delete', 'params' => 'reportID={id}');
$config->testreport->actionList['delete']['order']        = 10;
$config->testreport->actionList['delete']['show']         = 'clickable';
$config->testreport->actionList['delete']['class']        = 'ajax-submit';
$config->testreport->actionList['delete']['data-confirm'] = $lang->testreport->confirmDelete;

$config->testreport->dtable->fieldList['id']['name']     = 'id';
$config->testreport->dtable->fieldList['id']['title']    = $lang->idAB;
$config->testreport->dtable->fieldList['id']['type']     = 'ID';
$config->testreport->dtable->fieldList['id']['align']    = 'left';
$config->testreport->dtable->fieldList['id']['fixed']    = 'left';
$config->testreport->dtable->fieldList['id']['sortType'] = true;

$config->testreport->dtable->fieldList['title']['name']     = 'title';
$config->testreport->dtable->fieldList['title']['title']    = $lang->testreport->title;
$config->testreport->dtable->fieldList['title']['type']     = 'title';
$config->testreport->dtable->fieldList['title']['minWidth'] = '200';
$config->testreport->dtable->fieldList['title']['fixed']    = 'left';
$config->testreport->dtable->fieldList['title']['link']     = array('module' => 'testreport', 'method' => 'view', 'params' => 'testreportID={id}');
$config->testreport->dtable->fieldList['title']['sortType'] = true;
$config->testreport->dtable->fieldList['title']['data-app'] = $app->tab;

$config->testreport->dtable->fieldList['execution']['name']     = 'execution';
$config->testreport->dtable->fieldList['execution']['title']    = $lang->testreport->execution;
$config->testreport->dtable->fieldList['execution']['type']     = 'text';
$config->testreport->dtable->fieldList['execution']['sortType'] = true;

$config->testreport->dtable->fieldList['tasks']['name']  = 'tasks';
$config->testreport->dtable->fieldList['tasks']['title'] = $lang->testreport->testtask;
$config->testreport->dtable->fieldList['tasks']['type']  = 'text';

$config->testreport->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->testreport->dtable->fieldList['createdBy']['title']    = $lang->testreport->createdBy;
$config->testreport->dtable->fieldList['createdBy']['type']     = 'user';
$config->testreport->dtable->fieldList['createdBy']['sortType'] = true;
$config->testreport->dtable->fieldList['createdBy']['align']    = 'left';

$config->testreport->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->testreport->dtable->fieldList['createdDate']['title']    = $lang->testreport->createdDate;
$config->testreport->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->testreport->dtable->fieldList['createdDate']['sortType'] = true;

$config->testreport->dtable->fieldList['actions']['name']  = 'actions';
$config->testreport->dtable->fieldList['actions']['title'] = $lang->actions;
$config->testreport->dtable->fieldList['actions']['type']  = 'actions';
$config->testreport->dtable->fieldList['actions']['fixed'] = 'right';
$config->testreport->dtable->fieldList['actions']['list']  = $config->testreport->actionList;
$config->testreport->dtable->fieldList['actions']['menu']  = array('create', 'edit', 'delete');

$app->loadLang('story');
$app->loadModuleConfig('story');
$config->testreport->story = new stdclass();
$config->testreport->story->dtable = new stdclass();
$config->testreport->story->dtable->fieldList = array();
$config->testreport->story->dtable->fieldList['id']['name']  = 'id';
$config->testreport->story->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testreport->story->dtable->fieldList['id']['type']  = 'id';
$config->testreport->story->dtable->fieldList['id']['sort']  = false;

$config->testreport->story->dtable->fieldList['title']      = $config->story->dtable->fieldList['title'];
$config->testreport->story->dtable->fieldList['pri']        = $config->story->dtable->fieldList['pri'];
$config->testreport->story->dtable->fieldList['openedBy']   = $config->story->dtable->fieldList['openedBy'];
$config->testreport->story->dtable->fieldList['assignedTo'] = $config->story->dtable->fieldList['assignedTo'];
$config->testreport->story->dtable->fieldList['estimate']   = $config->story->dtable->fieldList['estimate'];
$config->testreport->story->dtable->fieldList['status']     = $config->story->dtable->fieldList['status'];
$config->testreport->story->dtable->fieldList['stage']      = $config->story->dtable->fieldList['stage'];
$config->testreport->story->dtable->fieldList['pri']        = $config->story->dtable->fieldList['pri'];

$config->testreport->story->dtable->fieldList['title']['sort']      = false;
$config->testreport->story->dtable->fieldList['pri']['sort']        = false;
$config->testreport->story->dtable->fieldList['openedBy']['sort']   = false;
$config->testreport->story->dtable->fieldList['assignedTo']['sort'] = false;
$config->testreport->story->dtable->fieldList['estimate']['sort']   = false;
$config->testreport->story->dtable->fieldList['status']['sort']     = false;
$config->testreport->story->dtable->fieldList['stage']['sort']      = false;
$config->testreport->story->dtable->fieldList['pri']['sort']        = false;

$config->testreport->story->dtable->fieldList['title']['nestedToggle'] = false;
$config->testreport->story->dtable->fieldList['assignedTo']['type']    = 'user';

$app->loadLang('bug');
$app->loadModuleConfig('bug');
$config->testreport->bug = new stdclass();
$config->testreport->bug->dtable = new stdclass();
$config->testreport->bug->dtable->fieldList = array();
$config->testreport->bug->dtable->fieldList['id']['name']  = 'id';
$config->testreport->bug->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testreport->bug->dtable->fieldList['id']['type']  = 'id';
$config->testreport->bug->dtable->fieldList['id']['sort']  = false;

$config->testreport->bug->dtable->fieldList['title']        = $config->bug->dtable->fieldList['title'];
$config->testreport->bug->dtable->fieldList['severity']     = $config->bug->dtable->fieldList['severity'];
$config->testreport->bug->dtable->fieldList['pri']          = $config->bug->dtable->fieldList['pri'];
$config->testreport->bug->dtable->fieldList['status']       = $config->bug->dtable->fieldList['status'];
$config->testreport->bug->dtable->fieldList['openedBy']     = $config->bug->dtable->fieldList['openedBy'];
$config->testreport->bug->dtable->fieldList['resolvedBy']   = $config->bug->dtable->fieldList['resolvedBy'];
$config->testreport->bug->dtable->fieldList['resolvedDate'] = $config->bug->dtable->fieldList['resolvedDate'];

$config->testreport->bug->dtable->fieldList['title']['sort']        = false;
$config->testreport->bug->dtable->fieldList['severity']['sort']     = false;
$config->testreport->bug->dtable->fieldList['pri']['sort']          = false;
$config->testreport->bug->dtable->fieldList['status']['sort']       = false;
$config->testreport->bug->dtable->fieldList['openedBy']['sort']     = false;
$config->testreport->bug->dtable->fieldList['resolvedBy']['sort']   = false;
$config->testreport->bug->dtable->fieldList['resolvedDate']['sort'] = false;

$app->loadLang('build');
$app->loadModuleConfig('build');
$config->testreport->build = new stdclass();
$config->testreport->build->dtable = new stdclass();
$config->testreport->build->dtable->fieldList = array();
$config->testreport->build->dtable->fieldList['id']['name']  = 'id';
$config->testreport->build->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testreport->build->dtable->fieldList['id']['type']  = 'id';
$config->testreport->build->dtable->fieldList['id']['sort']  = false;

$config->testreport->build->dtable->fieldList['name']    = $config->build->dtable->fieldList['name'];
$config->testreport->build->dtable->fieldList['builder'] = $config->build->dtable->fieldList['builder'];
$config->testreport->build->dtable->fieldList['date']    = $config->build->dtable->fieldList['date'];

$config->testreport->build->dtable->fieldList['name']['sort']    = false;
$config->testreport->build->dtable->fieldList['builder']['sort'] = false;
$config->testreport->build->dtable->fieldList['date']['sort']    = false;

$app->loadLang('testcase');
$app->loadModuleConfig('testcase');
$config->testreport->testcase = new stdclass();
$config->testreport->testcase->dtable = new stdclass();
$config->testreport->testcase->dtable->fieldList = array();
$config->testreport->testcase->dtable->fieldList['id']['name']  = 'id';
$config->testreport->testcase->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testreport->testcase->dtable->fieldList['id']['type']  = 'id';
$config->testreport->testcase->dtable->fieldList['id']['sort']  = false;

$config->testreport->testcase->dtable->fieldList['title']  = $config->testcase->dtable->fieldList['title'];
$config->testreport->testcase->dtable->fieldList['pri']    = $config->testcase->dtable->fieldList['pri'];
$config->testreport->testcase->dtable->fieldList['status'] = $config->testcase->dtable->fieldList['status'];
$config->testreport->testcase->dtable->fieldList['type']   = $config->testcase->dtable->fieldList['type'];

$config->testreport->testcase->dtable->fieldList['title']['sort']  = false;
$config->testreport->testcase->dtable->fieldList['pri']['sort']    = false;
$config->testreport->testcase->dtable->fieldList['status']['sort'] = false;
$config->testreport->testcase->dtable->fieldList['type']['sort']   = false;

$config->testreport->testcase->dtable->fieldList['assignedTo']['title'] = $lang->testcase->assignedTo;
$config->testreport->testcase->dtable->fieldList['assignedTo']['type']  = 'user';
$config->testreport->testcase->dtable->fieldList['assignedTo']['sort']  = false;

$config->testreport->testcase->dtable->fieldList['lastRunner']    = $config->testcase->dtable->fieldList['lastRunner'];
$config->testreport->testcase->dtable->fieldList['lastRunDate']   = $config->testcase->dtable->fieldList['lastRunDate'];
$config->testreport->testcase->dtable->fieldList['lastRunResult'] = $config->testcase->dtable->fieldList['lastRunResult'];

$config->testreport->testcase->dtable->fieldList['lastRunner']['sort']    = false;
$config->testreport->testcase->dtable->fieldList['lastRunDate']['sort']   = false;
$config->testreport->testcase->dtable->fieldList['lastRunResult']['sort'] = false;

$config->testreport->testcase->dtable->fieldList['title']['nestedToggle'] = false;
