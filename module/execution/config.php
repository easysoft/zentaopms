<?php
$config->execution = new stdclass();
$config->execution->defaultWorkhours = '7.0';
$config->execution->orderBy          = 'isDone,status,order_desc';
$config->execution->maxBurnDay       = '31';
$config->execution->weekend          = '2';

$config->execution->list = new stdclass();
$config->execution->list->exportFields = 'id,name,code,PM,end,status,totalEstimate,totalConsumed,totalLeft,progress';

$config->execution->modelList['scrum']     = 'sprint';
$config->execution->modelList['waterfall'] = 'stage';

global $lang, $app;
$app->loadLang('task');
$config->execution->task   = new stdclass();
$config->execution->create = new stdclass();
$config->execution->edit   = new stdclass();
$config->execution->create->requiredFields = 'name,code,begin,end';
$config->execution->edit->requiredFields   = 'name,code,begin,end';

$config->execution->customBatchEditFields = 'days,type,teamname,status,desc,PO,QD,PM,RD';

$config->execution->custom = new stdclass();
$config->execution->custom->batchEditFields = 'days,status,PM';

$config->execution->editor = new stdclass();
$config->execution->editor->create   = array('id' => 'desc',    'tools' => 'simpleTools');
$config->execution->editor->edit     = array('id' => 'desc',    'tools' => 'simpleTools');
$config->execution->editor->putoff   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->execution->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->execution->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->execution->editor->start    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->execution->editor->suspend  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->execution->editor->tree     = array('id' => 'comment', 'tools' => 'simpleTools');
$config->execution->editor->view     = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

$config->execution->search['module']                   = 'task';
$config->execution->search['fields']['name']           = $lang->task->name;
$config->execution->search['fields']['id']             = $lang->task->id;
$config->execution->search['fields']['status']         = $lang->task->status;
$config->execution->search['fields']['desc']           = $lang->task->desc;
$config->execution->search['fields']['assignedTo']     = $lang->task->assignedTo;
$config->execution->search['fields']['pri']            = $lang->task->pri;

$config->execution->search['fields']['execution']        = $lang->task->execution;
$config->execution->search['fields']['module']         = $lang->task->module;
$config->execution->search['fields']['estimate']       = $lang->task->estimate;
$config->execution->search['fields']['left']           = $lang->task->left;
$config->execution->search['fields']['consumed']       = $lang->task->consumed;
$config->execution->search['fields']['type']           = $lang->task->type;
$config->execution->search['fields']['fromBug']        = $lang->task->fromBug;
$config->execution->search['fields']['closedReason']   = $lang->task->closedReason;

$config->execution->search['fields']['openedBy']       = $lang->task->openedBy;
$config->execution->search['fields']['finishedBy']     = $lang->task->finishedBy;
$config->execution->search['fields']['closedBy']       = $lang->task->closedBy;
$config->execution->search['fields']['canceledBy']     = $lang->task->canceledBy;
$config->execution->search['fields']['lastEditedBy']   = $lang->task->lastEditedBy;

$config->execution->search['fields']['mailto']         = $lang->task->mailto;
$config->execution->search['fields']['finishedList']   = $lang->task->finishedList;

$config->execution->search['fields']['openedDate']     = $lang->task->openedDate;
$config->execution->search['fields']['deadline']       = $lang->task->deadline;
$config->execution->search['fields']['estStarted']     = $lang->task->estStarted;
$config->execution->search['fields']['realStarted']    = $lang->task->realStarted;
$config->execution->search['fields']['assignedDate']   = $lang->task->assignedDate;
$config->execution->search['fields']['finishedDate']   = $lang->task->finishedDate;
$config->execution->search['fields']['closedDate']     = $lang->task->closedDate;
$config->execution->search['fields']['canceledDate']   = $lang->task->canceledDate;
$config->execution->search['fields']['lastEditedDate'] = $lang->task->lastEditedDate;

$config->execution->search['params']['name']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->execution->search['params']['status']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->statusList);
$config->execution->search['params']['desc']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->execution->search['params']['assignedTo']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->search['params']['pri']            = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->priList);

$config->execution->search['params']['execution']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->execution->search['params']['module']         = array('operator' => 'belong',  'control' => 'select', 'values' => '');
$config->execution->search['params']['estimate']       = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->execution->search['params']['left']           = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->execution->search['params']['consumed']       = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->execution->search['params']['type']           = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->typeList);
$config->execution->search['params']['fromBug']        = array('operator' => '=',       'control' => 'input', 'values' => $lang->task->typeList);
$config->execution->search['params']['closedReason']   = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->reasonList);

$config->execution->search['params']['openedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->search['params']['finishedBy']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->search['params']['closedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->search['params']['cancelBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->search['params']['lastEditedBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');

$config->execution->search['params']['mailto']         = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->execution->search['params']['finishedList']   = array('operator' => 'include', 'control' => 'select', 'values' => 'users');

$config->execution->search['params']['openedDate']     = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['deadline']       = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['estStarted']     = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['realStarted']    = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['assignedDate']   = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['finishedDate']   = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['closedDate']     = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['canceledDate']   = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['lastEditedDate'] = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');

$config->printKanban = new stdClass();
$config->printKanban->col['story']  = 1;
$config->printKanban->col['wait']   = 2;
$config->printKanban->col['doing']  = 3;
$config->printKanban->col['done']   = 4;
$config->printKanban->col['closed'] = 5;

$config->execution->kanbanSetting = new stdclass();
$config->execution->kanbanSetting->colorList['wait']   = '#7EC5FF';
$config->execution->kanbanSetting->colorList['doing']  = '#0991FF';
$config->execution->kanbanSetting->colorList['pause']  = '#fdc137';
$config->execution->kanbanSetting->colorList['done']   = '#0BD986';
$config->execution->kanbanSetting->colorList['cancel'] = '#CBD0DB';
$config->execution->kanbanSetting->colorList['closed'] = '#838A9D';
