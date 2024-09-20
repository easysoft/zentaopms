<?php
global $app;
$app->loadLang('project');
$app->loadLang('story');
$app->loadLang('task');

$config->action->dtable = new stdclass();
$config->action->dtable->fieldList['objectType']['name']     = 'objectType';
$config->action->dtable->fieldList['objectType']['title']    = $lang->action->objectType;
$config->action->dtable->fieldList['objectType']['map']      = $lang->action->objectTypes;
$config->action->dtable->fieldList['objectType']['sortType'] = true;
$config->action->dtable->fieldList['objectType']['width']    = 160;
$config->action->dtable->fieldList['objectType']['fixed']    = 'left';

$config->action->dtable->fieldList['id']['name']     = 'objectID';
$config->action->dtable->fieldList['id']['title']    = $lang->idAB;
$config->action->dtable->fieldList['id']['sortType'] = true;
$config->action->dtable->fieldList['id']['width']    = 100;
$config->action->dtable->fieldList['id']['fixed']    = 'left';

$config->action->dtable->fieldList['objectName']['name']  = 'objectName';
$config->action->dtable->fieldList['objectName']['title'] = $lang->action->objectName;
$config->action->dtable->fieldList['objectName']['type']  = 'html';
$config->action->dtable->fieldList['objectName']['flex']  = 2;

$config->action->dtable->fieldList['project']['name']  = 'project';
$config->action->dtable->fieldList['project']['title'] = $lang->project->project;
$config->action->dtable->fieldList['project']['type']  = 'html';
$config->action->dtable->fieldList['project']['flex']  = 1;

$config->action->dtable->fieldList['product']['name']  = 'product';
$config->action->dtable->fieldList['product']['title'] = $lang->story->product;
$config->action->dtable->fieldList['product']['type']  = 'html';
$config->action->dtable->fieldList['product']['flex']  = 1;

$config->action->dtable->fieldList['execution']['name']  = 'execution';
$config->action->dtable->fieldList['execution']['title'] = $lang->task->execution;
$config->action->dtable->fieldList['execution']['type']  = 'html';
$config->action->dtable->fieldList['execution']['flex']  = 1;

$config->action->dtable->fieldList['actor']['name']  = 'actor';
$config->action->dtable->fieldList['actor']['title'] = $lang->action->actor;
$config->action->dtable->fieldList['actor']['type']  = 'user';
$config->action->dtable->fieldList['actor']['fixed'] = 'right';

$config->action->dtable->fieldList['date']['name']  = 'date';
$config->action->dtable->fieldList['date']['title'] = $lang->action->date;
$config->action->dtable->fieldList['date']['type']  = 'datetime';
$config->action->dtable->fieldList['date']['fixed'] = 'right';

$config->action->dtable->fieldList['actions']['name']     = 'actions';
$config->action->dtable->fieldList['actions']['title']    = $lang->actions;
$config->action->dtable->fieldList['actions']['type']     = 'actions';
$config->action->dtable->fieldList['actions']['sortType'] = false;
$config->action->dtable->fieldList['actions']['list']     = $config->action->actionList;
$config->action->dtable->fieldList['actions']['menu']     = array('undelete');
