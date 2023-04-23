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

$config->program->productView->dtable->fieldList['planCount']['name']     = 'plans';
$config->program->productView->dtable->fieldList['planCount']['title']    = $lang->productplan->shortCommon;
$config->program->productView->dtable->fieldList['planCount']['width']    = 60;
$config->program->productView->dtable->fieldList['planCount']['type']     = 'format';
$config->program->productView->dtable->fieldList['planCount']['sortType'] = true;

$config->program->productView->dtable->fieldList['executionCount']['name']     = 'executionCount';
$config->program->productView->dtable->fieldList['executionCount']['title']    = $lang->execution->common;
$config->program->productView->dtable->fieldList['executionCount']['width']    = 60;
$config->program->productView->dtable->fieldList['executionCount']['type']     = 'format';
$config->program->productView->dtable->fieldList['executionCount']['sortType'] = true;

$config->program->productView->dtable->fieldList['testCaseCoverage']['name']     = 'testCaseCoverage';
$config->program->productView->dtable->fieldList['testCaseCoverage']['title']    = $lang->program->testCaseCoverage;
$config->program->productView->dtable->fieldList['testCaseCoverage']['minWidth'] = 100;
$config->program->productView->dtable->fieldList['testCaseCoverage']['type']     = 'circleProgress';
$config->program->productView->dtable->fieldList['testCaseCoverage']['sortType'] = true;

$config->program->productView->dtable->fieldList['bugActivatedCount']['name']     = 'unResolvedBugs';
$config->program->productView->dtable->fieldList['bugActivatedCount']['title']    = $lang->program->bugActivatedCount;
$config->program->productView->dtable->fieldList['bugActivatedCount']['minWidth'] = 60;
$config->program->productView->dtable->fieldList['bugActivatedCount']['type']     = 'format';
$config->program->productView->dtable->fieldList['bugActivatedCount']['sortType'] = true;

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

/* DataTable fields of Project View. */
$config->program->projectView = new stdClass();
$config->program->projectView->dtable = new stdClass();
$config->program->projectView->dtable->fieldList = array();

$config->program->projectView->dtable->fieldList['name']['name']         = 'name';
$config->program->projectView->dtable->fieldList['name']['title']        = $lang->nameAB;
$config->program->projectView->dtable->fieldList['name']['width']        = 200;
$config->program->projectView->dtable->fieldList['name']['type']         = 'link';
$config->program->projectView->dtable->fieldList['name']['flex']         = 1;
$config->program->projectView->dtable->fieldList['name']['nestedToggle'] = true;
$config->program->projectView->dtable->fieldList['name']['checkbox']     = true;
$config->program->projectView->dtable->fieldList['name']['sortType']     = true;
$config->program->projectView->dtable->fieldList['name']['iconRender']   = 'RAWJS<function(row){ if(row.data.type === \'program\') return \'icon-cards-view text-gray\'; if(row.data.type === \'productLine\') return \'icon-scrum text-gray\'; return \'\';}>RAWJS';

$config->program->projectView->dtable->fieldList['status']['name']      = 'status';
$config->program->projectView->dtable->fieldList['status']['title']     = $lang->program->status;
$config->program->projectView->dtable->fieldList['status']['minWidth']  = 60;
$config->program->projectView->dtable->fieldList['status']['type']      = 'status';
$config->program->projectView->dtable->fieldList['status']['sortType']  = true;
$config->program->projectView->dtable->fieldList['status']['statusMap'] = $lang->program->statusList;

$config->program->projectView->dtable->fieldList['PM']['name']     = 'PM';
$config->program->projectView->dtable->fieldList['PM']['title']    = $lang->program->PM;
$config->program->projectView->dtable->fieldList['PM']['minWidth'] = 80;
$config->program->projectView->dtable->fieldList['PM']['type']     = 'avatarBtn';
$config->program->projectView->dtable->fieldList['PM']['sortType'] = true;

$config->program->projectView->dtable->fieldList['budget']['name']     = 'budget';
$config->program->projectView->dtable->fieldList['budget']['title']    = $lang->program->budget;
$config->program->projectView->dtable->fieldList['budget']['width']    = 90;
$config->program->projectView->dtable->fieldList['budget']['type']     = 'format';
$config->program->projectView->dtable->fieldList['budget']['sortType'] = true;

$config->program->projectView->dtable->fieldList['invested']['name']     = 'invested';
$config->program->projectView->dtable->fieldList['invested']['title']    = $lang->program->invested;
$config->program->projectView->dtable->fieldList['invested']['minWidth'] = 70;
$config->program->projectView->dtable->fieldList['invested']['type']     = 'format';
$config->program->projectView->dtable->fieldList['invested']['sortType'] = true;

$config->program->projectView->dtable->fieldList['begin']['name']     = 'begin';
$config->program->projectView->dtable->fieldList['begin']['title']    = $lang->program->begin;
$config->program->projectView->dtable->fieldList['begin']['minWidth'] = 90;
$config->program->projectView->dtable->fieldList['begin']['type']     = 'datetime';
$config->program->projectView->dtable->fieldList['begin']['sortType'] = true;

$config->program->projectView->dtable->fieldList['end']['name']     = 'end';
$config->program->projectView->dtable->fieldList['end']['title']    = $lang->program->end;
$config->program->projectView->dtable->fieldList['end']['minWidth'] = 90;
$config->program->projectView->dtable->fieldList['end']['type']     = 'datetime';
$config->program->projectView->dtable->fieldList['end']['sortType'] = true;

$config->program->projectView->dtable->fieldList['progress']['name']     = 'progress';
$config->program->projectView->dtable->fieldList['progress']['title']    = $lang->program->progressAB;
$config->program->projectView->dtable->fieldList['progress']['minWidth'] = 100;
$config->program->projectView->dtable->fieldList['progress']['type']     = 'circleProgress';

$config->program->projectView->dtable->fieldList['actions']['name']   = 'actions';
$config->program->projectView->dtable->fieldList['actions']['title']  = $lang->actions;
$config->program->projectView->dtable->fieldList['actions']['width']  = 160;
$config->program->projectView->dtable->fieldList['actions']['type']   = 'actions';
$config->program->projectView->dtable->fieldList['actions']['fixed']  = 'right';
$config->program->projectView->dtable->fieldList['actions']['actionsMap'] = array(
    'program_start'     => array('icon'  => 'icon-start',        'hint' => $lang->program->start),
    'program_suspend'   => array('icon'  => 'icon-pause',        'hint' => $lang->program->suspend),
    'program_close'     => array('icon'  => 'icon-off',          'hint' => $lang->program->close),
    'program_activate'  => array('icon'  => 'icon-active',       'hint' => $lang->program->activate),
    'program_other'     => array('caret' => true,                'hint' => $lang->program->other, 'type' => 'dropdown', ),
    'program_edit'      => array('icon'  => 'icon-edit',         'hint' => $lang->program->edit),
    'program_create'    => array('icon'  => 'icon-split',        'hint' => $lang->program->create),
    'program_delete'    => array('icon'  => 'icon-trash',        'hint' => $lang->program->delete),
    'project_start'     => array('icon'  => 'icon-start',        'hint' => $lang->project->start),
    'project_suspend'   => array('icon'  => 'icon-pause',        'hint' => $lang->project->suspend),
    'project_close'     => array('icon'  => 'icon-off',          'hint' => $lang->project->close),
    'project_activate'  => array('icon'  => 'icon-active',       'hint' => $lang->project->activate),
    'project_other'     => array('caret' => true,                'hint' => $lang->project->other, 'type' => 'dropdown', ),
    'project_edit'      => array('icon'  => 'icon-edit',         'hint' => $lang->project->edit),
    'project_team'      => array('icon'  => 'icon-groups',       'hint' => $lang->project->manageMembers),
    'project_group'     => array('icon'  => 'icon-lock',         'hint' => $lang->project->group),
    'project_more'      => array('icon'  => 'icon-ellipsis-v',   'hint' => $lang->project->moreActions, 'type' => 'dropdown', 'caret' => false),
    'project_link'      => array('icon'  => 'icon-link',         'hint' => $lang->project->manageProducts),
    'project_whitelist' => array('icon'  => 'icon-shield-check', 'hint' => $lang->project->whitelist),
    'project_delete'    => array('icon'  => 'icon-trash',        'hint' => $lang->project->delete)
);
