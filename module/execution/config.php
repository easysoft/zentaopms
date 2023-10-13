<?php
$config->execution = new stdclass();
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
$config->execution->task   = new stdclass();
$config->execution->create = new stdclass();
$config->execution->edit   = new stdclass();
$config->execution->start  = new stdclass();
$config->execution->close  = new stdclass();
$config->execution->create->requiredFields  = 'name,code,begin,end';
$config->execution->edit->requiredFields    = 'name,code,begin,end';
$config->execution->start->requiredFields   = 'realBegan';
$config->execution->close->requiredFields   = 'realEnd';

$config->execution->customBatchEditFields = 'days,type,teamname,desc,PO,QD,PM,RD';

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

$config->execution->search['params']['openedDate']     = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['deadline']       = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['estStarted']     = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['realStarted']    = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['assignedDate']   = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['finishedDate']   = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['closedDate']     = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['canceledDate']   = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['lastEditedDate'] = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->search['params']['activatedDate']  = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');

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
$config->execution->all->search['params']['openedDate']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->all->search['params']['begin']          = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->all->search['params']['end']            = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->all->search['params']['realBegan']      = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->all->search['params']['realEnd']        = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->all->search['params']['closedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->execution->all->search['params']['lastEditedDate'] = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->execution->all->search['params']['closedDate']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
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

$config->execution->datatable = new stdclass();
if(isset($config->setCode) and $config->setCode == 1)
{
    $config->execution->datatable->defaultField = array('id', 'name', 'code', 'project', 'PM', 'status', 'progress', 'openedDate', 'begin', 'end', 'estimate', 'consumed', 'left', 'burn');
}
else
{
    $config->execution->datatable->defaultField = array('id', 'name', 'project', 'PM', 'status', 'progress', 'openedDate', 'begin', 'end', 'estimate', 'consumed', 'left', 'burn');
}

$config->execution->datatable->fieldList['id']['title']    = 'idAB';
$config->execution->datatable->fieldList['id']['type']     = 'checkID';
$config->execution->datatable->fieldList['id']['width']    = '80';
$config->execution->datatable->fieldList['id']['fixed']    = 'left';
$config->execution->datatable->fieldList['id']['sortType'] = true;
$config->execution->datatable->fieldList['id']['checkbox'] = true;

$config->execution->datatable->fieldList['name']['name']         = 'name';
$config->execution->datatable->fieldList['name']['title']        = $lang->execution->execName;
$config->execution->datatable->fieldList['name']['width']        = 'auto';
$config->execution->datatable->fieldList['name']['type']         = 'html';
$config->execution->datatable->fieldList['name']['fixed']        = 'left';
$config->execution->datatable->fieldList['name']['sortType']     = true;
$config->execution->datatable->fieldList['name']['nestedToggle'] = true;
$config->execution->datatable->fieldList['name']['iconRender']   = true;
$config->execution->datatable->fieldList['name']['required']     = 'yes';

if(isset($config->setCode) and $config->setCode == 1)
{
    $config->execution->datatable->fieldList['code']['title']    = 'execCode';
    $config->execution->datatable->fieldList['code']['type']     = 'html';
    $config->execution->datatable->fieldList['code']['width']    = '180';
    $config->execution->datatable->fieldList['code']['fixed']    = 'left';
    $config->execution->datatable->fieldList['code']['required'] = 'no';
}

$config->execution->datatable->fieldList['project']['title']    = 'project';
$config->execution->datatable->fieldList['project']['width']    = '220';
$config->execution->datatable->fieldList['project']['fixed']    = 'right';
$config->execution->datatable->fieldList['project']['required'] = 'no';
$config->execution->datatable->fieldList['project']['sortType'] = true;

$config->execution->datatable->fieldList['status']['title']    = 'execStatus';
$config->execution->datatable->fieldList['status']['type']     = 'status';
$config->execution->datatable->fieldList['status']['sortType'] = 'true';
$config->execution->datatable->fieldList['status']['width']    = '100';
$config->execution->datatable->fieldList['status']['fixed']    = 'right';
$config->execution->datatable->fieldList['status']['required'] = 'no';

$config->execution->datatable->fieldList['PM']['title']    = 'owner';
$config->execution->datatable->fieldList['PM']['width']    = '90';
$config->execution->datatable->fieldList['PM']['fixed']    = 'right';
$config->execution->datatable->fieldList['PM']['required'] = 'no';

$config->execution->datatable->fieldList['openedDate']['title']    = 'openedDate';
$config->execution->datatable->fieldList['openedDate']['width']    = '150';
$config->execution->datatable->fieldList['openedDate']['sortType'] = 'true';
$config->execution->datatable->fieldList['openedDate']['fixed']    = 'right';
$config->execution->datatable->fieldList['openedDate']['required'] = 'no';

$config->execution->datatable->fieldList['begin']['title']    = 'begin';
$config->execution->datatable->fieldList['begin']['width']    = '100';
$config->execution->datatable->fieldList['begin']['sortType'] = 'true';
$config->execution->datatable->fieldList['begin']['fixed']    = 'right';
$config->execution->datatable->fieldList['begin']['required'] = 'no';

$config->execution->datatable->fieldList['end']['title']    = 'end';
$config->execution->datatable->fieldList['end']['width']    = '90';
$config->execution->datatable->fieldList['end']['fixed']    = 'right';
$config->execution->datatable->fieldList['end']['required'] = 'no';
$config->execution->datatable->fieldList['end']['sortType'] = 'true';

$config->execution->datatable->fieldList['realBegan']['title']    = 'realBegan';
$config->execution->datatable->fieldList['realBegan']['minWidth'] = '110';
$config->execution->datatable->fieldList['realBegan']['fixed']    = 'right';
$config->execution->datatable->fieldList['realBegan']['required'] = 'no';
$config->execution->datatable->fieldList['realBegan']['sortType'] = 'true';

$config->execution->datatable->fieldList['realEnd']['title']    = 'realEnd';
$config->execution->datatable->fieldList['realEnd']['minWidth'] = '110';
$config->execution->datatable->fieldList['realEnd']['fixed']    = 'right';
$config->execution->datatable->fieldList['realEnd']['required'] = 'no';
$config->execution->datatable->fieldList['realEnd']['sortType'] = 'true';

$config->execution->datatable->fieldList['estimate']['title']    = 'estimate';
$config->execution->datatable->fieldList['estimate']['width']    = '70';
$config->execution->datatable->fieldList['estimate']['fixed']    = 'right';
$config->execution->datatable->fieldList['estimate']['required'] = 'no';
$config->execution->datatable->fieldList['estimate']['sort']     = 'no';

$config->execution->datatable->fieldList['consumed']['title']    = 'consumed';
$config->execution->datatable->fieldList['consumed']['width']    = '75';
$config->execution->datatable->fieldList['consumed']['fixed']    = 'right';
$config->execution->datatable->fieldList['consumed']['required'] = 'no';
$config->execution->datatable->fieldList['consumed']['sort']     = 'no';

$config->execution->datatable->fieldList['left']['title']    = 'left';
$config->execution->datatable->fieldList['left']['width']    = '70';
$config->execution->datatable->fieldList['left']['fixed']    = 'right';
$config->execution->datatable->fieldList['left']['required'] = 'no';
$config->execution->datatable->fieldList['left']['sort']     = 'no';

$config->execution->datatable->fieldList['progress']['title']    = 'progress';
$config->execution->datatable->fieldList['progress']['type']     = 'circleProgress';
$config->execution->datatable->fieldList['progress']['width']    = '70';
$config->execution->datatable->fieldList['progress']['fixed']    = 'right';
$config->execution->datatable->fieldList['progress']['required'] = 'no';
$config->execution->datatable->fieldList['progress']['sort']     = 'no';

$config->execution->datatable->fieldList['burn']['title']    = 'burn';
$config->execution->datatable->fieldList['burn']['width']    = '85';
$config->execution->datatable->fieldList['burn']['fixed']    = 'right';
$config->execution->datatable->fieldList['burn']['required'] = 'no';
$config->execution->datatable->fieldList['burn']['sort']     = 'no';
