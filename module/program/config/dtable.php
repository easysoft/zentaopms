<?php
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

/* DataTable fields of Product View. */
$config->program->productview = new stdClass();
$config->program->productview->dtable = new stdClass();
$config->program->productview->dtable->fieldList = array();

$config->program->productview->dtable->fieldList['name']['name']         = 'name';
$config->program->productview->dtable->fieldList['name']['title']        = $lang->nameAB;
$config->program->productview->dtable->fieldList['name']['type']         = 'title';
$config->program->productview->dtable->fieldList['name']['nestedToggle'] = true;
$config->program->productview->dtable->fieldList['name']['checkbox']     = true;
$config->program->productview->dtable->fieldList['name']['sortType']     = true;
$config->program->productview->dtable->fieldList['name']['iconRender']   = 'RAWJS<function(val,row){ if(row.data.type === \'program\') return \'icon-cards-view text-gray\'; if(row.data.type === \'productLine\') return \'icon-scrum text-gray\'; return \'\';}>RAWJS';
$config->program->productview->dtable->fieldList['name']['group']        = 'g1';

$config->program->productview->dtable->fieldList['PM']['name']  = 'PM';
$config->program->productview->dtable->fieldList['PM']['title'] = $lang->program->PM;
$config->program->productview->dtable->fieldList['PM']['type']  = 'avatarBtn';
$config->program->productview->dtable->fieldList['PM']['group'] = 'g2';

$config->program->productview->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->program->productview->dtable->fieldList['createdDate']['title']    = '创建时间';
$config->program->productview->dtable->fieldList['createdDate']['type']     = 'date';
$config->program->productview->dtable->fieldList['createdDate']['sortType'] = true;
$config->program->productview->dtable->fieldList['createdDate']['group']    = 'g3';

$config->program->productview->dtable->fieldList['createdBy']['name']  = 'createdBy';
$config->program->productview->dtable->fieldList['createdBy']['title'] = '创建者';
$config->program->productview->dtable->fieldList['createdBy']['type']  = 'user';
$config->program->productview->dtable->fieldList['createdBy']['group'] = 'g3';

$config->program->productview->dtable->fieldList['unclosedReqCount']['name']     = 'unclosedReqCount';
$config->program->productview->dtable->fieldList['unclosedReqCount']['title']    = $lang->program->unclosedReqCount;
$config->program->productview->dtable->fieldList['unclosedReqCount']['minWidth'] = 100;
$config->program->productview->dtable->fieldList['unclosedReqCount']['type']     = 'number';
$config->program->productview->dtable->fieldList['unclosedReqCount']['sortType'] = true;
$config->program->productview->dtable->fieldList['unclosedReqCount']['group']    = 'g4';

$config->program->productview->dtable->fieldList['totalReq']['name']     = 'totalReq';
$config->program->productview->dtable->fieldList['totalReq']['title']    = '需求总数';
$config->program->productview->dtable->fieldList['totalReq']['minWidth'] = 100;
$config->program->productview->dtable->fieldList['totalReq']['type']     = 'number';
$config->program->productview->dtable->fieldList['totalReq']['sortType'] = true;
$config->program->productview->dtable->fieldList['totalReq']['group']    = 'g4';

$config->program->productview->dtable->fieldList['closedReqRate']['name']     = 'closedReqRate';
$config->program->productview->dtable->fieldList['closedReqRate']['title']    = $lang->program->closedReqRate;
$config->program->productview->dtable->fieldList['closedReqRate']['minWidth'] = 100;
$config->program->productview->dtable->fieldList['closedReqRate']['type']     = 'progress';
$config->program->productview->dtable->fieldList['closedReqRate']['sortType'] = true;
$config->program->productview->dtable->fieldList['closedReqRate']['group']    = 'g4';

$config->program->productview->dtable->fieldList['planCount']['name']     = 'plans';
$config->program->productview->dtable->fieldList['planCount']['title']    = $lang->productplan->shortCommon;
$config->program->productview->dtable->fieldList['planCount']['type']     = 'number';
$config->program->productview->dtable->fieldList['planCount']['sortType'] = true;
$config->program->productview->dtable->fieldList['planCount']['group']    = 'g5';

$config->program->productview->dtable->fieldList['projectCount']['name']     = 'projects';
$config->program->productview->dtable->fieldList['projectCount']['title']    = '项目';
$config->program->productview->dtable->fieldList['projectCount']['type']     = 'text';
$config->program->productview->dtable->fieldList['projectCount']['sortType'] = true;
$config->program->productview->dtable->fieldList['projectCount']['group']    = 'g5';

$config->program->productview->dtable->fieldList['executionCount']['name']     = 'executionCount';
$config->program->productview->dtable->fieldList['executionCount']['title']    = $lang->execution->common;
$config->program->productview->dtable->fieldList['executionCount']['type']     = 'number';
$config->program->productview->dtable->fieldList['executionCount']['sortType'] = true;
$config->program->productview->dtable->fieldList['executionCount']['group']    = 'g5';

$config->program->productview->dtable->fieldList['testCaseCoverage']['name']     = 'testCaseCoverage';
$config->program->productview->dtable->fieldList['testCaseCoverage']['title']    = $lang->program->testCaseCoverage;
$config->program->productview->dtable->fieldList['testCaseCoverage']['minWidth'] = 100;
$config->program->productview->dtable->fieldList['testCaseCoverage']['type']     = 'progress';
$config->program->productview->dtable->fieldList['testCaseCoverage']['sortType'] = true;
$config->program->productview->dtable->fieldList['testCaseCoverage']['group']    = 'g6';

$config->program->productview->dtable->fieldList['bugActivatedCount']['name']     = 'unResolvedBugs';
$config->program->productview->dtable->fieldList['bugActivatedCount']['title']    = $lang->program->bugActivatedCount;
$config->program->productview->dtable->fieldList['bugActivatedCount']['minWidth'] = 86;
$config->program->productview->dtable->fieldList['bugActivatedCount']['type']     = 'number';
$config->program->productview->dtable->fieldList['bugActivatedCount']['sortType'] = true;
$config->program->productview->dtable->fieldList['bugActivatedCount']['group']    = 'g7';

$config->program->productview->dtable->fieldList['bugTotalCount']['name']     = 'bugTotalCount';
$config->program->productview->dtable->fieldList['bugTotalCount']['title']    = 'Bug总数';
$config->program->productview->dtable->fieldList['bugTotalCount']['minWidth'] = 86;
$config->program->productview->dtable->fieldList['bugTotalCount']['type']     = 'number';
$config->program->productview->dtable->fieldList['bugTotalCount']['sortType'] = true;
$config->program->productview->dtable->fieldList['bugTotalCount']['group']    = 'g7';

$config->program->productview->dtable->fieldList['fixedRate']['name']     = 'fixedRate';
$config->program->productview->dtable->fieldList['fixedRate']['title']    = $lang->program->fixedRate;
$config->program->productview->dtable->fieldList['fixedRate']['minWidth'] = 80;
$config->program->productview->dtable->fieldList['fixedRate']['type']     = 'progress';
$config->program->productview->dtable->fieldList['fixedRate']['sortType'] = true;
$config->program->productview->dtable->fieldList['fixedRate']['group']    = 'g7';

$config->program->productview->dtable->fieldList['releaseCount']['name']     = 'releaseCount';
$config->program->productview->dtable->fieldList['releaseCount']['title']    = $lang->release->common;
$config->program->productview->dtable->fieldList['releaseCount']['type']     = 'number';
$config->program->productview->dtable->fieldList['releaseCount']['sortType'] = true;
$config->program->productview->dtable->fieldList['releaseCount']['group']    = 'g8';

$config->program->productview->dtable->fieldList['latestReleaseDate']['name']     = 'latestReleaseDate';
$config->program->productview->dtable->fieldList['latestReleaseDate']['title']    = '最新发布时间';
$config->program->productview->dtable->fieldList['latestReleaseDate']['minWidth'] = 120;
$config->program->productview->dtable->fieldList['latestReleaseDate']['type']     = 'date';
$config->program->productview->dtable->fieldList['latestReleaseDate']['sortType'] = true;
$config->program->productview->dtable->fieldList['latestReleaseDate']['group']    = 'g8';

$config->program->productview->dtable->fieldList['latestRelease']['name']       = 'latestRelease';
$config->program->productview->dtable->fieldList['latestRelease']['title']      = '最新发布';
$config->program->productview->dtable->fieldList['latestRelease']['minWidth'] = 80;
$config->program->productview->dtable->fieldList['latestRelease']['type']       = 'text';
$config->program->productview->dtable->fieldList['latestRelease']['filterType'] = true;
$config->program->productview->dtable->fieldList['latestRelease']['group']      = 'g8';

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
$config->program->projectView->dtable->fieldList['name']['iconRender']   = 'RAWJS<function(val,row){ if(row.data.type === \'program\') return \'icon-cards-view text-gray\'; if(row.data.type === \'productLine\') return \'icon-scrum text-gray\'; return \'\';}>RAWJS';

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

global $app;
$app->loadLang('project');

$config->program->projectView->dtable->fieldList['actions']['name']       = 'actions';
$config->program->projectView->dtable->fieldList['actions']['title']      = $lang->actions;
$config->program->projectView->dtable->fieldList['actions']['width']      = 160;
$config->program->projectView->dtable->fieldList['actions']['type']       = 'actions';
$config->program->projectView->dtable->fieldList['actions']['fixed']      = 'right';
$config->program->projectView->dtable->fieldList['actions']['actionsMap'] = array
(
    'program_start'     => array('icon'  => 'icon-start',        'hint' => $lang->program->start),
    'program_suspend'   => array('icon'  => 'icon-pause',        'hint' => $lang->program->suspend),
    'program_close'     => array('icon'  => 'icon-off',          'hint' => $lang->program->close),
    'program_activate'  => array('icon'  => 'icon-active',       'hint' => $lang->program->activate),
    'program_other'     => array('caret' => true,                'hint' => $lang->other, 'type' => 'dropdown'),
    'program_edit'      => array('icon'  => 'icon-edit',         'hint' => $lang->program->edit),
    'program_create'    => array('icon'  => 'icon-split',        'hint' => $lang->program->create),
    'program_delete'    => array('icon'  => 'icon-trash',        'hint' => $lang->program->delete),
    'project_start'     => array('icon'  => 'icon-start',        'hint' => $lang->project->start),
    'project_suspend'   => array('icon'  => 'icon-pause',        'hint' => $lang->project->suspend),
    'project_close'     => array('icon'  => 'icon-off',          'hint' => $lang->project->close),
    'project_activate'  => array('icon'  => 'icon-active',       'hint' => $lang->project->activate),
    'project_other'     => array('caret' => true,                'hint' => $lang->project->other, 'type' => 'dropdown'),
    'project_edit'      => array('icon'  => 'icon-edit',         'hint' => $lang->project->edit),
    'project_team'      => array('icon'  => 'icon-groups',       'hint' => $lang->project->manageMembers),
    'project_group'     => array('icon'  => 'icon-lock',         'hint' => $lang->project->group),
    'project_more'      => array('icon'  => 'icon-ellipsis-v',   'hint' => $lang->project->moreActions, 'type' => 'dropdown', 'caret' => false),
    'project_link'      => array('icon'  => 'icon-link',         'hint' => $lang->project->manageProducts),
    'project_whitelist' => array('icon'  => 'icon-shield-check', 'hint' => $lang->project->whitelist),
    'project_delete'    => array('icon'  => 'icon-trash',        'hint' => $lang->project->delete)
);
