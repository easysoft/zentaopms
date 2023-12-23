<?php
if(!isset($config->execution)) $config->execution = new stdclass();
$config->execution->defaultWorkhours  = '7.0';
$config->execution->orderBy           = 'isDone,status,order_desc';
$config->execution->maxBurnDay        = '31';
$config->execution->weekend           = '2';
$config->execution->ownerFields       = array('PO', 'PM', 'QD', 'RD');
$config->execution->defaultBurnPeriod = 30;

$config->execution->list = new stdclass();
$config->execution->list->exportFields = 'id,name,projectName,PM,begin,end,status,estimate,consumed,left,progress';

$config->execution->modelList['scrum']         = 'sprint';
$config->execution->modelList['waterfall']     = 'stage';
$config->execution->modelList['kanban']        = 'kanban';
$config->execution->modelList['waterfallplus'] = 'stage';

$config->execution->statusActions = array('start', 'putoff', 'suspend', 'close', 'activate');

$config->execution->kanbanMethod = array('kanban', 'cfd', 'build', 'view', 'manageproducts', 'team', 'managemembers', 'whitelist', 'addwhitelist', 'edit');

global $lang, $app;
$app->loadLang('task');
$app->loadLang('programplan');
$config->execution->task   = new stdclass();
$config->execution->create = new stdclass();
$config->execution->edit   = new stdclass();
$config->execution->start  = new stdclass();
$config->execution->close  = new stdclass();
$config->execution->create->requiredFields  = 'name,code,begin,end';
$config->execution->edit->requiredFields    = 'name,code,begin,end';
$config->execution->start->requiredFields   = 'realBegan';
$config->execution->close->requiredFields   = 'realEnd';

$config->execution->customBatchEditFields = 'days,type,teamName,desc,PO,QD,PM,RD';

$config->execution->custom = new stdclass();
$config->execution->custom->batchEditFields = 'days,PM';

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

if($app->tab == 'my') $config->execution->search['fields']['project'] = $lang->task->project;
$config->execution->search['fields']['execution']      = $lang->task->execution;
$config->execution->search['fields']['module']         = $lang->task->module;
$config->execution->search['fields']['estimate']       = $lang->task->estimate;
$config->execution->search['fields']['left']           = $lang->task->left;
$config->execution->search['fields']['consumed']       = $lang->task->consumed;
$config->execution->search['fields']['type']           = $lang->task->type;
if($config->vision != 'lite') $config->execution->search['fields']['fromBug'] = $lang->task->fromBugID;
$config->execution->search['fields']['closedReason']   = $lang->task->closedReason;

$config->execution->search['fields']['openedBy']       = $lang->task->openedBy;
$config->execution->search['fields']['finishedBy']     = $lang->task->finishedBy;
$config->execution->search['fields']['closedBy']       = $lang->task->closedBy;
$config->execution->search['fields']['canceledBy']     = $lang->task->canceledBy;
$config->execution->search['fields']['lastEditedBy']   = $lang->task->lastEditedBy;

$config->execution->search['fields']['mailto']         = $lang->task->mailto;

$config->execution->search['fields']['openedDate']     = $lang->task->openedDate;
$config->execution->search['fields']['deadline']       = $lang->task->deadline;
$config->execution->search['fields']['estStarted']     = $lang->task->estStarted;
$config->execution->search['fields']['realStarted']    = $lang->task->realStarted;
$config->execution->search['fields']['assignedDate']   = $lang->task->assignedDate;
$config->execution->search['fields']['finishedDate']   = $lang->task->finishedDate;
$config->execution->search['fields']['closedDate']     = $lang->task->closedDate;
$config->execution->search['fields']['canceledDate']   = $lang->task->canceledDate;
$config->execution->search['fields']['lastEditedDate'] = $lang->task->lastEditedDate;
$config->execution->search['fields']['activatedDate']  = $lang->task->activatedDate;

$config->execution->search['params']['name']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->execution->search['params']['status']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->statusList);
$config->execution->search['params']['desc']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->execution->search['params']['assignedTo']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->search['params']['pri']            = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->priList);

if($app->tab == 'my') $config->execution->search['params']['project'] = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->execution->search['params']['execution']      = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->execution->search['params']['module']         = array('operator' => 'belong',  'control' => 'select', 'values' => '');
$config->execution->search['params']['estimate']       = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->execution->search['params']['left']           = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->execution->search['params']['consumed']       = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->execution->search['params']['type']           = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->typeList);
$config->execution->search['params']['fromBug']        = array('operator' => '=',       'control' => 'input',  'values' => $lang->task->typeList);
$config->execution->search['params']['closedReason']   = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->reasonList);

$config->execution->search['params']['openedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->search['params']['finishedBy']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->search['params']['closedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->search['params']['canceledBy']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->search['params']['lastEditedBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');

$config->execution->search['params']['mailto']         = array('operator' => 'include', 'control' => 'select', 'values' => 'users');

$config->execution->search['params']['openedDate']     = array('operator' => '=',      'control' => 'date',  'values' => '');
$config->execution->search['params']['deadline']       = array('operator' => '=',      'control' => 'date',  'values' => '');
$config->execution->search['params']['estStarted']     = array('operator' => '=',      'control' => 'date',  'values' => '');
$config->execution->search['params']['realStarted']    = array('operator' => '=',      'control' => 'date',  'values' => '');
$config->execution->search['params']['assignedDate']   = array('operator' => '=',      'control' => 'date',  'values' => '');
$config->execution->search['params']['finishedDate']   = array('operator' => '=',      'control' => 'date',  'values' => '');
$config->execution->search['params']['closedDate']     = array('operator' => '=',      'control' => 'date',  'values' => '');
$config->execution->search['params']['canceledDate']   = array('operator' => '=',      'control' => 'date',  'values' => '');
$config->execution->search['params']['lastEditedDate'] = array('operator' => '=',      'control' => 'date',  'values' => '');
$config->execution->search['params']['activatedDate']  = array('operator' => '=',      'control' => 'date',  'values' => '');

$app->loadLang('execution');
$config->execution->all = new stdclass();
$config->execution->all->search['module'] = 'execution';
$config->execution->all->search['fields']['name']           = $lang->execution->execName;
$config->execution->all->search['fields']['id']             = $lang->execution->execId;
$config->execution->all->search['fields']['status']         = $lang->execution->execStatus;
$config->execution->all->search['fields']['project']        = $lang->execution->project;
$config->execution->all->search['fields']['PM']             = $lang->execution->owner;
$config->execution->all->search['fields']['openedBy']       = $lang->execution->openedBy;
$config->execution->all->search['fields']['openedDate']     = $lang->execution->openedDate;
$config->execution->all->search['fields']['begin']          = $lang->execution->begin;
$config->execution->all->search['fields']['end']            = $lang->execution->end;
$config->execution->all->search['fields']['realBegan']      = $lang->execution->realBegan;
$config->execution->all->search['fields']['realEnd']        = $lang->execution->realEnd;
$config->execution->all->search['fields']['closedBy']       = $lang->execution->closedBy;
$config->execution->all->search['fields']['lastEditedDate'] = $lang->execution->lastEditedDate;
$config->execution->all->search['fields']['closedDate']     = $lang->execution->closedDate;
$config->execution->all->search['fields']['teamCount']      = $lang->execution->teamCount;

$config->execution->all->search['params']['name']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->execution->all->search['params']['id']             = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->execution->all->search['params']['status']         = array('operator' => '=',       'control' => 'select', 'values' => array('') + $lang->execution->statusList);
$config->execution->all->search['params']['project']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->execution->all->search['params']['PM']             = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->all->search['params']['openedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->all->search['params']['openedDate']     = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->execution->all->search['params']['begin']          = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->execution->all->search['params']['end']            = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->execution->all->search['params']['realBegan']      = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->execution->all->search['params']['realEnd']        = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->execution->all->search['params']['closedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->all->search['params']['lastEditedDate'] = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->execution->all->search['params']['closedDate']     = array('operator' => '=',       'control' => 'date',  'values' => '');
$config->execution->all->search['params']['teamCount']      = array('operator' => '=',       'control' => 'input',  'values' => '');

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

$config->execution->gantt = new stdclass();
$config->execution->gantt->linkType['end']['begin']   = 0;
$config->execution->gantt->linkType['begin']['begin'] = 1;
$config->execution->gantt->linkType['end']['end']     = 2;
$config->execution->gantt->linkType['begin']['end']   = 3;

$config->execution->actionList = array();
$config->execution->actionList['start']['icon']        = 'start';
$config->execution->actionList['start']['text']        = $lang->execution->start;
$config->execution->actionList['start']['hint']        = $lang->execution->start;
$config->execution->actionList['start']['url']         = helper::createLink('execution', 'start', "executionID={rawID}");
$config->execution->actionList['start']['data-toggle'] = 'modal';

$config->execution->actionList['createTask']['icon'] = 'plus';
$config->execution->actionList['createTask']['text'] = $lang->task->create;
$config->execution->actionList['createTask']['hint'] = $lang->task->create;
$config->execution->actionList['createTask']['url']  = helper::createLink('task', 'create', "executionID={rawID}");

$config->execution->actionList['createChildStage']['icon'] = 'split';
$config->execution->actionList['createChildStage']['text'] = $lang->programplan->createSubPlan;
$config->execution->actionList['createChildStage']['hint'] = $lang->programplan->createSubPlan;
$config->execution->actionList['createChildStage']['url']  = helper::createLink('programplan', 'create', "projectID={projectID}&productID={product}&executionID={rawID}");

$config->execution->actionList['edit']['icon']        = 'edit';
$config->execution->actionList['edit']['text']        = $lang->edit;
$config->execution->actionList['edit']['hint']        = $lang->edit;
$config->execution->actionList['edit']['url']         = helper::createLink('execution', 'edit', "executionID={rawID}");
$config->execution->actionList['edit']['data-size']   = 'lg';
$config->execution->actionList['edit']['data-toggle'] = 'modal';

$config->execution->actionList['close']['icon']        = 'off';
$config->execution->actionList['close']['text']        = $lang->execution->close;
$config->execution->actionList['close']['hint']        = $lang->execution->close;
$config->execution->actionList['close']['url']         = helper::createLink('execution', 'close', "executionID={rawID}");
$config->execution->actionList['close']['data-toggle'] = 'modal';

$config->execution->actionList['activate']['icon']        = 'magic';
$config->execution->actionList['activate']['text']        = $lang->execution->activate;
$config->execution->actionList['activate']['hint']        = $lang->execution->activate;
$config->execution->actionList['activate']['url']         = helper::createLink('execution', 'activate', "executionID={rawID}");
$config->execution->actionList['activate']['data-toggle'] = 'modal';

$config->execution->actionList['delete']['icon']      = 'trash';
$config->execution->actionList['delete']['className'] = 'ajax-submit';
$config->execution->actionList['delete']['text']      = $lang->delete;
$config->execution->actionList['delete']['hint']      = $lang->delete;
$config->execution->actionList['delete']['url']       = helper::createLink('execution', 'delete', "executionID={rawID}");

$config->execution->actionList['suspend']['icon']        = 'pause';
$config->execution->actionList['suspend']['text']        = $lang->execution->suspend;
$config->execution->actionList['suspend']['url']         = helper::createLink('execution', 'suspend', "executionID={rawID}");
$config->execution->actionList['suspend']['data-toggle'] = 'modal';

$config->execution->actionList['putoff']['icon']        = 'calendar';
$config->execution->actionList['putoff']['text']        = $lang->execution->putoff;
$config->execution->actionList['putoff']['url']         = helper::createLink('execution', 'putoff', "executionID={rawID}");
$config->execution->actionList['putoff']['data-toggle'] = 'modal';
$config->execution->actionList['putoff']['data-size']   = 'lg';

$config->execution->view = new stdclass();
$config->execution->view->operateList['main']   = array('putoff', 'start', 'activate', 'suspend', 'close');
$config->execution->view->operateList['common'] = array('edit', 'delete');
