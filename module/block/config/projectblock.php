<?php
global $lang, $app;
$app->loadLang('project');
$app->loadLang('task');

$config->block->project = new stdclass();
$config->block->project->dtable = new stdclass();
$config->block->project->dtable->fieldList = array();
$config->block->project->dtable->fieldList['name']        = array('name' => 'name',        'title' => $lang->project->name,        'type' => 'title',    'sortType' => true, 'flex' => 1, 'link' => array('module' => 'project', 'method' => 'index', 'params' => 'projectID={id}'), 'className' => 'projectName');
$config->block->project->dtable->fieldList['PM']          = array('name' => 'PM',          'title' => $lang->project->PM,          'type' => 'user',     'sortType' => true);
$config->block->project->dtable->fieldList['status']      = array('name' => 'status',      'title' => $lang->project->status,      'type' => 'status',   'sortType' => true, 'statusMap' => $lang->project->statusList);
$config->block->project->dtable->fieldList['consumed']    = array('name' => 'consumed',    'title' => $lang->task->consumed,       'type' => 'count',    'sortType' => true);
$config->block->project->dtable->fieldList['leftStories'] = array('name' => 'leftStories', 'title' => $lang->project->leftStories, 'type' => 'count',    'sortType' => true);
$config->block->project->dtable->fieldList['leftTasks']   = array('name' => 'leftTasks',   'title' => $lang->project->leftTasks,   'type' => 'count',    'sortType' => true);
$config->block->project->dtable->fieldList['leftBugs']    = array('name' => 'leftBugs',    'title' => $lang->project->leftBugs,    'type' => 'count',    'sortType' => true);
$config->block->project->dtable->fieldList['end']         = array('name' => 'end',         'title' => $lang->project->end,         'type' => 'date',     'sortType' => true);
$config->block->project->dtable->fieldList['progress']    = array('name' => 'progress',    'title' => $lang->project->progress,    'type' => 'progress', 'sortType' => true);
