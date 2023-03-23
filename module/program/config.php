<?php
$config->program = new stdclass();
$config->program->showAllProjects = 0;

$config->program->editor = new stdclass();
$config->program->editor->create   = array('id' => 'desc',    'tools' => 'simpleTools');
$config->program->editor->edit     = array('id' => 'desc',    'tools' => 'simpleTools');
$config->program->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->start    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->finish   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->suspend  = array('id' => 'comment', 'tools' => 'simpleTools');

$config->program->list = new stdclass();
$config->program->list->exportFields = 'id,name,code,template,product,status,begin,end,budget,PM,end,desc';

$config->program->create = new stdclass();
$config->program->edit   = new stdclass();
$config->program->create->requiredFields = 'name,begin,end';
$config->program->edit->requiredFields   = 'name,begin,end';

$config->program->sortFields        = new stdclass();
$config->program->sortFields->id    = 'id';
$config->program->sortFields->begin = 'begin';
$config->program->sortFields->end   = 'end';

global $lang;
$config->program->search['module']                   = 'program';
$config->program->search['fields']['name']           = $lang->program->name;
$config->program->search['fields']['status']         = $lang->program->status;
$config->program->search['fields']['desc']           = $lang->program->desc;
$config->program->search['fields']['PM']             = $lang->program->PM;
$config->program->search['fields']['openedDate']     = $lang->program->openedDate;
$config->program->search['fields']['begin']          = $lang->program->begin;
$config->program->search['fields']['end']            = $lang->program->end;
$config->program->search['fields']['openedBy']       = $lang->program->openedBy;
$config->program->search['fields']['lastEditedDate'] = $lang->program->lastEditedDate;
$config->program->search['fields']['realBegan']      = $lang->program->realBegin;
$config->program->search['fields']['realEnd']        = $lang->program->realEnd;
$config->program->search['fields']['closedDate']     = $lang->program->closedDate;

$config->program->search['params']['name']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->program->search['params']['status']         = array('operator' => '=',       'control' => 'select', 'values' => array('' => '') + $lang->program->statusList);
$config->program->search['params']['desc']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->program->search['params']['PM']             = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->program->search['params']['openedDate']     = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['begin']          = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['end']            = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['openedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->program->search['params']['lastEditedDate'] = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['realBegan']      = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['realEnd']        = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['closedDate']     = array('operator' => '=',       'control' => 'date',   'values' => '');

/* Data table field config. */
global $lang;
$config->program->dtable = new stdclass();

$config->program->dtable->fieldList['name']['name']         = 'name';
$config->program->dtable->fieldList['name']['title']        = $lang->nameAB;
$config->program->dtable->fieldList['name']['width']        = 356;
$config->program->dtable->fieldList['name']['type']         = 'link';
$config->program->dtable->fieldList['name']['flex']         = 1;
$config->program->dtable->fieldList['name']['nestedToggle'] = true;
$config->program->dtable->fieldList['name']['checkbox']     = true;
$config->program->dtable->fieldList['name']['iconRender']   = true;
$config->program->dtable->fieldList['name']['sortType']     = false;

$config->program->dtable->fieldList['status']['name']      = 'status';
$config->program->dtable->fieldList['status']['title']     = $lang->program->status;
$config->program->dtable->fieldList['status']['minWidth']  = 60;
$config->program->dtable->fieldList['status']['type']      = 'status';
$config->program->dtable->fieldList['status']['sortType']  = true;
$config->program->dtable->fieldList['status']['statusMap'] = $lang->program->statusList;

$config->program->dtable->fieldList['PM']['name']     = 'PM';
$config->program->dtable->fieldList['PM']['title']    = $lang->program->PM;
$config->program->dtable->fieldList['PM']['minWidth'] = 100;
$config->program->dtable->fieldList['PM']['type']     = 'avatarBtn';
$config->program->dtable->fieldList['PM']['sortType'] = true;

$config->program->dtable->fieldList['budget']['name']     = 'budget';
$config->program->dtable->fieldList['budget']['title']    = $lang->program->budget;
$config->program->dtable->fieldList['budget']['minWidth'] = 70;
$config->program->dtable->fieldList['budget']['type']     = 'format';
$config->program->dtable->fieldList['budget']['sortType'] = true;

$config->program->dtable->fieldList['begin']['name']     = 'begin';
$config->program->dtable->fieldList['begin']['title']    = $lang->program->begin;
$config->program->dtable->fieldList['begin']['minWidth'] = 90;
$config->program->dtable->fieldList['begin']['type']     = 'datetime';
$config->program->dtable->fieldList['begin']['sortType'] = true;

$config->program->dtable->fieldList['end']['name']     = 'end';
$config->program->dtable->fieldList['end']['title']    = $lang->program->end;
$config->program->dtable->fieldList['end']['minWidth'] = 90;
$config->program->dtable->fieldList['end']['type']     = 'datetime';
$config->program->dtable->fieldList['end']['sortType'] = true;

$config->program->dtable->fieldList['progress']['name']     = 'progress';
$config->program->dtable->fieldList['progress']['title']    = $lang->program->progressAB;
$config->program->dtable->fieldList['progress']['minWidth'] = 100;
$config->program->dtable->fieldList['progress']['type']     = 'circleProgress';

$config->program->dtable->fieldList['actions']['name']   = 'actions';
$config->program->dtable->fieldList['actions']['title']  = $lang->actions;
$config->program->dtable->fieldList['actions']['width']  = 160;
$config->program->dtable->fieldList['actions']['type']   = 'actions';
$config->program->dtable->fieldList['actions']['fixed']  = 'right';
$config->program->dtable->fieldList['actions']['module'] = 'program';

global $app;
$app->loadLang('project');
$config->program->actionsMap['normal']            = array('start', 'suspend', 'close', 'activate', 'edit', 'create', 'delete', 'team', 'group');
$config->program->actionsMap['other']             = array('start', 'suspend', 'close', 'activate');
$config->program->actionsMap['more']              = array('link', 'whitelist', 'delete');
$config->program->actionsMap['hint']['create']    = $lang->program->children;
$config->program->actionsMap['hint']['delete']    = $lang->delete;
$config->program->actionsMap['hint']['team']      = $lang->project->team;
$config->program->actionsMap['hint']['group']     = $lang->project->group;
$config->program->actionsMap['text']['start']     = $lang->program->start;
$config->program->actionsMap['text']['suspend']   = $lang->program->suspend;
$config->program->actionsMap['text']['close']     = $lang->close;
$config->program->actionsMap['text']['activate']  = $lang->program->activate;
$config->program->actionsMap['text']['delete']    = $lang->delete;
$config->program->actionsMap['text']['link']      = $lang->project->manageProducts;
$config->program->actionsMap['text']['whitelist'] = $lang->project->whitelist;
$config->program->actionsMap['text']['delete']    = $lang->delete;

/* DataTable fields of Product View. */
$config->program->productView = new stdClass();
$config->program->productView->dtable = new stdClass();
$config->program->productView->dtable->fieldList = array();

$config->program->productView->dtable->fieldList['name']['name']         = 'name';
$config->program->productView->dtable->fieldList['name']['title']        = $lang->nameAB;
$config->program->productView->dtable->fieldList['name']['width']        = 200;
$config->program->productView->dtable->fieldList['name']['type']         = 'link';
$config->program->productView->dtable->fieldList['name']['flex']         = 1;
$config->program->productView->dtable->fieldList['name']['nestedToggle'] = true;
$config->program->productView->dtable->fieldList['name']['checkbox']     = true;
$config->program->productView->dtable->fieldList['name']['sortType']     = true;
$config->program->productView->dtable->fieldList['name']['iconRender']   = 'RAWJS<function(row){ if(row.data.type === \'program\') return \'icon-cards-view text-gray\'; if(row.data.type === \'productLine\') return \'icon-scrum text-gray\'; return \'\';}>RAWJS';

$config->program->productView->dtable->fieldList['PM']['name']     = 'PM';
$config->program->productView->dtable->fieldList['PM']['title']    = $lang->program->PM;
$config->program->productView->dtable->fieldList['PM']['minWidth'] = 80;
$config->program->productView->dtable->fieldList['PM']['type']     = 'avatarBtn';

$config->program->productView->dtable->fieldList['feedback']['name']     = 'feedback';
$config->program->productView->dtable->fieldList['feedback']['title']    = $lang->program->feedback;
$config->program->productView->dtable->fieldList['feedback']['width']    = 60;
$config->program->productView->dtable->fieldList['feedback']['type']     = 'format';
$config->program->productView->dtable->fieldList['feedback']['sortType'] = true;

$config->program->productView->dtable->fieldList['unclosedReqCount']['name']     = 'unclosedReqCount';
$config->program->productView->dtable->fieldList['unclosedReqCount']['title']    = $lang->program->unclosedReqCount;
$config->program->productView->dtable->fieldList['unclosedReqCount']['minWidth'] = 100;
$config->program->productView->dtable->fieldList['unclosedReqCount']['type']     = 'format';
$config->program->productView->dtable->fieldList['unclosedReqCount']['sortType'] = true;

$config->program->productView->dtable->fieldList['closedReqRate']['name']     = 'closedReqRate';
$config->program->productView->dtable->fieldList['closedReqRate']['title']    = $lang->program->closedReqRate;
$config->program->productView->dtable->fieldList['closedReqRate']['minWidth'] = 100;
$config->program->productView->dtable->fieldList['closedReqRate']['type']     = 'circleProgress';
$config->program->productView->dtable->fieldList['closedReqRate']['sortType'] = true;

$config->program->productView->dtable->fieldList['planCount']['name']     = 'planCount';
$config->program->productView->dtable->fieldList['planCount']['title']    = $lang->productplan->shortCommon;
$config->program->productView->dtable->fieldList['planCount']['width']    = 60;
$config->program->productView->dtable->fieldList['planCount']['type']     = 'format';
$config->program->productView->dtable->fieldList['planCount']['sortType'] = true;

$config->program->productView->dtable->fieldList['executionCount']['name']     = 'executionCount';
$config->program->productView->dtable->fieldList['executionCount']['title']    = $lang->execution->common;
$config->program->productView->dtable->fieldList['executionCount']['width']    = 60;
$config->program->productView->dtable->fieldList['executionCount']['type']     = 'format';
$config->program->productView->dtable->fieldList['executionCount']['sortType'] = true;

$config->program->productView->dtable->fieldList['testCaseCoverRate']['name']     = 'testCaseCoverRate';
$config->program->productView->dtable->fieldList['testCaseCoverRate']['title']    = $lang->program->testCaseCoverRate;
$config->program->productView->dtable->fieldList['testCaseCoverRate']['minWidth'] = 100;
$config->program->productView->dtable->fieldList['testCaseCoverRate']['type']     = 'circleProgress';
$config->program->productView->dtable->fieldList['testCaseCoverRate']['sortType'] = true;

$config->program->productView->dtable->fieldList['bugActivedCount']['name']     = 'bugActivedCount';
$config->program->productView->dtable->fieldList['bugActivedCount']['title']    = $lang->program->bugActivedCount;
$config->program->productView->dtable->fieldList['bugActivedCount']['minWidth'] = 60;
$config->program->productView->dtable->fieldList['bugActivedCount']['type']     = 'format';
$config->program->productView->dtable->fieldList['bugActivedCount']['sortType'] = true;

$config->program->productView->dtable->fieldList['fixedRate']['name']     = 'fixedRate';
$config->program->productView->dtable->fieldList['fixedRate']['title']    = $lang->program->fixedRate;
$config->program->productView->dtable->fieldList['fixedRate']['minWidth'] = 60;
$config->program->productView->dtable->fieldList['fixedRate']['type']     = 'circleProgress';
$config->program->productView->dtable->fieldList['fixedRate']['sortType'] = true;

$config->program->productView->dtable->fieldList['releaseCount']['name']     = 'releaseCount';
$config->program->productView->dtable->fieldList['releaseCount']['title']    = $lang->release->common;
$config->program->productView->dtable->fieldList['releaseCount']['width']    = 80;
$config->program->productView->dtable->fieldList['releaseCount']['type']     = 'html';
$config->program->productView->dtable->fieldList['releaseCount']['sortType'] = false;
