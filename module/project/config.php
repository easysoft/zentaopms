<?php
$config->project->projectCounts    = 50;
$config->project->defaultWorkhours = 7;
$config->project->orderBy          = 'status, id desc';

global $lang, $app;
$app->loadLang('task');
$config->project->create->requiredFields = 'name,code,team,begin,end';
$config->project->edit->requiredFields   = 'name,code,team,begin,end';

$config->project->editor->create = array('id' => 'desc,goal', 'tools' => 'simpleTools');
$config->project->editor->edit   = array('id' => 'desc,goal', 'tools' => 'simpleTools');

$config->project->search['module']                   = 'task';
$config->project->search['fields']['name']           = $lang->task->name;
$config->project->search['fields']['id']             = $lang->task->id;
$config->project->search['fields']['project']        = $lang->task->project;
$config->project->search['fields']['type']           = $lang->task->type;
$config->project->search['fields']['mailto']         = $lang->task->mailto;
$config->project->search['fields']['estimate']       = $lang->task->estimate;      
$config->project->search['fields']['left']           = $lang->task->left; 
$config->project->search['fields']['consumed']       = $lang->task->consumed;
$config->project->search['fields']['deadline']       = $lang->task->deadline;
$config->project->search['fields']['status']         = $lang->task->status;
$config->project->search['fields']['pri']            = $lang->task->pri;
$config->project->search['fields']['desc']           = $lang->task->desc;
$config->project->search['fields']['assignedTo']     = $lang->task->assignedTo;
$config->project->search['fields']['assignedDate']   = $lang->task->assignedDate;
$config->project->search['fields']['openedDate']     = $lang->task->openedDate;
$config->project->search['fields']['openedBy']       = $lang->task->openedBy;
$config->project->search['fields']['finishedBy']     = $lang->task->finishedBy;
$config->project->search['fields']['finishedDate']   = $lang->task->finishedDate;
$config->project->search['fields']['canceledBy']     = $lang->task->canceledBy;  
$config->project->search['fields']['canceledDate']   = $lang->task->canceledDate;
$config->project->search['fields']['closedBy']       = $lang->task->closedBy;
$config->project->search['fields']['closedDate']     = $lang->task->closedDate;
$config->project->search['fields']['closedReason']   = $lang->task->closedReason;
$config->project->search['fields']['lastEdited']     = $lang->task->lastEdited;

$config->project->search['params']['name']          = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->project->search['params']['desc']          = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->project->search['params']['project']       = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->project->search['params']['assignedTo']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->project->search['params']['openedBy']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->project->search['params']['closedBy']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->project->search['params']['cancelBy']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->project->search['params']['lastEdited']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->project->search['params']['finishedBy']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->project->search['params']['mailto']        = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->project->search['params']['status']        = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->statusList);
$config->project->search['params']['closedReason']  = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->reasonList);
$config->project->search['params']['pri']           = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->priList);
$config->project->search['params']['type']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->typeList);
$config->project->search['params']['assignedDate']  = array('operator' => '>=',      'control' => 'input',  'values' => '');
$config->project->search['params']['openedDate']    = array('operator' => '>=',      'control' => 'input',  'values' => '');
$config->project->search['params']['finishedDate']  = array('operator' => '>=',      'control' => 'input',  'values' => '');
$config->project->search['params']['closedDate']    = array('operator' => '>=',      'control' => 'input',  'values' => '');
$config->project->search['params']['canceledDate']  = array('operator' => '>=',      'control' => 'input',  'values' => '');
$config->project->search['params']['deadline']      = array('operator' => '=',       'control' => 'input',  'values' => '');
