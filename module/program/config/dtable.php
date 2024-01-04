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
$config->program->dtable->fieldList['progress']['sortType'] = false;
$config->program->dtable->fieldList['progress']['type']     = 'progress';
$config->program->dtable->fieldList['progress']['align']    = 'center';

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
$config->program->productview->dtable->fieldList['name']['type']         = 'link';
$config->program->productview->dtable->fieldList['name']['iconRender']   = 'RAWJS<function(value, row){return window.iconRenderProductView(value, row);}>RAWJS';
$config->program->productview->dtable->fieldList['name']['link']         = "RAWJS<function(info){const {row, col} = info; if(row.data.type == 'product') return {url:$.createLink('product', 'browse', 'productID=' + row.data.id)}; if(row.data.type == 'program') return {url:$.createLink('program', 'view', 'programID=' + row.data.id.replace('program-', ''))};}>RAWJS";
$config->program->productview->dtable->fieldList['name']['nestedToggle'] = true;
$config->program->productview->dtable->fieldList['name']['checkbox']     = true;
$config->program->productview->dtable->fieldList['name']['show']         = true;
$config->program->productview->dtable->fieldList['name']['sortType']     = true;
$config->program->productview->dtable->fieldList['name']['minWidth']     = 350;
$config->program->productview->dtable->fieldList['name']['group']        = 'g1';

$config->program->productview->dtable->fieldList['PM']['name']  = 'PM';
$config->program->productview->dtable->fieldList['PM']['title'] = $lang->program->PM;
$config->program->productview->dtable->fieldList['PM']['type']  = 'avatarBtn';
$config->program->productview->dtable->fieldList['PM']['show']  = true;
$config->program->productview->dtable->fieldList['PM']['group'] = 'g2';

$config->program->productview->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->program->productview->dtable->fieldList['createdDate']['title']    = $lang->program->createdDate;
$config->program->productview->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->program->productview->dtable->fieldList['createdDate']['sortType'] = false;
$config->program->productview->dtable->fieldList['createdDate']['group']    = 'g3';

$config->program->productview->dtable->fieldList['createdBy']['name']  = 'createdBy';
$config->program->productview->dtable->fieldList['createdBy']['title'] = $lang->openedByAB;
$config->program->productview->dtable->fieldList['createdBy']['type']  = 'user';
$config->program->productview->dtable->fieldList['createdBy']['group'] = 'g3';

$config->program->productview->dtable->fieldList['totalUnclosedStories']['name']     = 'totalUnclosedStories';
$config->program->productview->dtable->fieldList['totalUnclosedStories']['title']    = $lang->program->totalUnclosedStories;
$config->program->productview->dtable->fieldList['totalUnclosedStories']['minWidth'] = 100;
$config->program->productview->dtable->fieldList['totalUnclosedStories']['type']     = 'number';
$config->program->productview->dtable->fieldList['totalUnclosedStories']['show']     = true;
$config->program->productview->dtable->fieldList['totalUnclosedStories']['sortType'] = false;
$config->program->productview->dtable->fieldList['totalUnclosedStories']['group']    = 'g4';

$config->program->productview->dtable->fieldList['totalStories']['name']     = 'totalStories';
$config->program->productview->dtable->fieldList['totalStories']['title']    = $lang->program->totalStories;
$config->program->productview->dtable->fieldList['totalStories']['minWidth'] = 100;
$config->program->productview->dtable->fieldList['totalStories']['type']     = 'number';
$config->program->productview->dtable->fieldList['totalStories']['sortType'] = false;
$config->program->productview->dtable->fieldList['totalStories']['group']    = 'g4';

$config->program->productview->dtable->fieldList['closedStoryRate']['name']     = 'closedStoryRate';
$config->program->productview->dtable->fieldList['closedStoryRate']['title']    = $lang->program->closedStoryRate;
$config->program->productview->dtable->fieldList['closedStoryRate']['minWidth'] = 100;
$config->program->productview->dtable->fieldList['closedStoryRate']['type']     = 'progress';
$config->program->productview->dtable->fieldList['closedStoryRate']['show']     = true;
$config->program->productview->dtable->fieldList['closedStoryRate']['sortType'] = false;
$config->program->productview->dtable->fieldList['closedStoryRate']['group']    = 'g4';

$config->program->productview->dtable->fieldList['totalPlans']['name']     = 'totalPlans';
$config->program->productview->dtable->fieldList['totalPlans']['title']    = $lang->productplan->shortCommon;
$config->program->productview->dtable->fieldList['totalPlans']['type']     = 'number';
$config->program->productview->dtable->fieldList['totalPlans']['show']     = true;
$config->program->productview->dtable->fieldList['totalPlans']['sortType'] = false;
$config->program->productview->dtable->fieldList['totalPlans']['group']    = 'g5';

$config->program->productview->dtable->fieldList['totalProjects']['name']     = 'totalProjects';
$config->program->productview->dtable->fieldList['totalProjects']['title']    = $lang->program->project;
$config->program->productview->dtable->fieldList['totalProjects']['type']     = 'number';
$config->program->productview->dtable->fieldList['totalProjects']['link']     = array('module' => 'product', 'method' => 'project', 'params' => 'status=all&&productID={id}');
$config->program->productview->dtable->fieldList['totalProjects']['sortType'] = false;
$config->program->productview->dtable->fieldList['totalProjects']['group']    = 'g5';

$config->program->productview->dtable->fieldList['totalExecutions']['name']     = 'totalExecutions';
$config->program->productview->dtable->fieldList['totalExecutions']['title']    = $lang->execution->common;
$config->program->productview->dtable->fieldList['totalExecutions']['type']     = 'number';
$config->program->productview->dtable->fieldList['totalExecutions']['show']     = true;
$config->program->productview->dtable->fieldList['totalExecutions']['sortType'] = false;
$config->program->productview->dtable->fieldList['totalExecutions']['group']    = 'g5';

$config->program->productview->dtable->fieldList['testCaseCoverage']['name']     = 'testCaseCoverage';
$config->program->productview->dtable->fieldList['testCaseCoverage']['title']    = $lang->program->testCaseCoverage;
$config->program->productview->dtable->fieldList['testCaseCoverage']['minWidth'] = 100;
$config->program->productview->dtable->fieldList['testCaseCoverage']['type']     = 'progress';
$config->program->productview->dtable->fieldList['testCaseCoverage']['show']     = true;
$config->program->productview->dtable->fieldList['testCaseCoverage']['sortType'] = false;
$config->program->productview->dtable->fieldList['testCaseCoverage']['group']    = 'g6';

$config->program->productview->dtable->fieldList['totalActivatedBugs']['name']     = 'totalActivatedBugs';
$config->program->productview->dtable->fieldList['totalActivatedBugs']['title']    = $lang->program->totalActivatedBugs;
$config->program->productview->dtable->fieldList['totalActivatedBugs']['minWidth'] = 86;
$config->program->productview->dtable->fieldList['totalActivatedBugs']['type']     = 'number';
$config->program->productview->dtable->fieldList['totalActivatedBugs']['show']     = true;
$config->program->productview->dtable->fieldList['totalActivatedBugs']['sortType'] = false;
$config->program->productview->dtable->fieldList['totalActivatedBugs']['group']    = 'g7';

$config->program->productview->dtable->fieldList['totalBugs']['name']     = 'totalBugs';
$config->program->productview->dtable->fieldList['totalBugs']['title']    = $lang->program->totalBugs;
$config->program->productview->dtable->fieldList['totalBugs']['minWidth'] = 86;
$config->program->productview->dtable->fieldList['totalBugs']['type']     = 'number';
$config->program->productview->dtable->fieldList['totalBugs']['sortType'] = false;
$config->program->productview->dtable->fieldList['totalBugs']['group']    = 'g7';

$config->program->productview->dtable->fieldList['fixedRate']['name']     = 'fixedRate';
$config->program->productview->dtable->fieldList['fixedRate']['title']    = $lang->program->fixedRate;
$config->program->productview->dtable->fieldList['fixedRate']['minWidth'] = 80;
$config->program->productview->dtable->fieldList['fixedRate']['type']     = 'progress';
$config->program->productview->dtable->fieldList['fixedRate']['show']     = true;
$config->program->productview->dtable->fieldList['fixedRate']['sortType'] = false;
$config->program->productview->dtable->fieldList['fixedRate']['group']    = 'g7';

$config->program->productview->dtable->fieldList['totalReleases']['name']     = 'totalReleases';
$config->program->productview->dtable->fieldList['totalReleases']['title']    = $lang->release->common;
$config->program->productview->dtable->fieldList['totalReleases']['minWidth'] = 90;
$config->program->productview->dtable->fieldList['totalReleases']['type']     = 'number';
$config->program->productview->dtable->fieldList['totalReleases']['show']     = true;
$config->program->productview->dtable->fieldList['totalReleases']['sortType'] = false;
$config->program->productview->dtable->fieldList['totalReleases']['group']    = 'g8';

$config->program->productview->dtable->fieldList['latestReleaseDate']['name']     = 'latestReleaseDate';
$config->program->productview->dtable->fieldList['latestReleaseDate']['title']    = $lang->program->latestReleaseDate;
$config->program->productview->dtable->fieldList['latestReleaseDate']['minWidth'] = 120;
$config->program->productview->dtable->fieldList['latestReleaseDate']['type']     = 'date';
$config->program->productview->dtable->fieldList['latestReleaseDate']['sortType'] = false;
$config->program->productview->dtable->fieldList['latestReleaseDate']['group']    = 'g8';

$config->program->productview->dtable->fieldList['latestRelease']['name']       = 'latestRelease';
$config->program->productview->dtable->fieldList['latestRelease']['title']      = $lang->program->latestRelease;
$config->program->productview->dtable->fieldList['latestRelease']['minWidth']   = 80;
$config->program->productview->dtable->fieldList['latestRelease']['type']       = 'text';
$config->program->productview->dtable->fieldList['latestRelease']['filterType'] = true;
$config->program->productview->dtable->fieldList['latestRelease']['group']      = 'g8';

global $app;
$app->loadLang('project');

/* DataTable fields of browse View. */
$config->program->browse = new stdClass();
$config->program->browse->dtable = new stdClass();
$config->program->browse->dtable->fieldList = array();

$config->program->browse->dtable->fieldList['name']['name']         = 'name';
$config->program->browse->dtable->fieldList['name']['title']        = $lang->nameAB;
$config->program->browse->dtable->fieldList['name']['width']        = 200;
$config->program->browse->dtable->fieldList['name']['type']         = 'link';
$config->program->browse->dtable->fieldList['name']['link']         = "RAWJS<function(info){const {row, col} = info; if(row.data.type == 'project') return {url:$.createLink('project', 'index', 'projectID=' + row.data.id)}; if(row.data.type == 'program') return {url:$.createLink('program', 'project', 'programID=' + row.data.id)};}>RAWJS";
$config->program->browse->dtable->fieldList['name']['flex']         = 1;
$config->program->browse->dtable->fieldList['name']['nestedToggle'] = true;
$config->program->browse->dtable->fieldList['name']['checkbox']     = true;
$config->program->browse->dtable->fieldList['name']['sortType']     = true;
$config->program->browse->dtable->fieldList['name']['iconRender']   = 'RAWJS<function(val,row){ if(row.data.type === \'program\') return \'icon-cards-view text-gray\'; if(row.data.type === \'productLine\') return \'icon-scrum text-gray\'; if(row.data.type == \'project\') return \'icon-\' + (row.data.model == \'scrum\' ? \'sprint\' : row.data.model) + \' text-gray\'; return \'\';}>RAWJS';
$config->program->browse->dtable->fieldList['name']['required']     = true;
$config->program->browse->dtable->fieldList['name']['show']         = true;
$config->program->browse->dtable->fieldList['name']['group']        = 1;

$config->program->browse->dtable->fieldList['status']['name']      = 'status';
$config->program->browse->dtable->fieldList['status']['title']     = $lang->program->status;
$config->program->browse->dtable->fieldList['status']['minWidth']  = 60;
$config->program->browse->dtable->fieldList['status']['type']      = 'status';
$config->program->browse->dtable->fieldList['status']['sortType']  = true;
$config->program->browse->dtable->fieldList['status']['statusMap'] = $lang->program->statusList;
$config->program->browse->dtable->fieldList['status']['show']      = true;
$config->program->browse->dtable->fieldList['status']['group']     = 2;

$config->program->browse->dtable->fieldList['PM']['name']     = 'PM';
$config->program->browse->dtable->fieldList['PM']['title']    = $lang->program->PM;
$config->program->browse->dtable->fieldList['PM']['minWidth'] = 80;
$config->program->browse->dtable->fieldList['PM']['type']     = 'avatarBtn';
$config->program->browse->dtable->fieldList['PM']['sortType'] = true;
$config->program->browse->dtable->fieldList['PM']['show']     = true;
$config->program->browse->dtable->fieldList['PM']['group']    = 2;

$config->program->browse->dtable->fieldList['budget']['name']     = 'budget';
$config->program->browse->dtable->fieldList['budget']['title']    = $lang->program->budget;
$config->program->browse->dtable->fieldList['budget']['width']    = 90;
$config->program->browse->dtable->fieldList['budget']['type']     = 'format';
$config->program->browse->dtable->fieldList['budget']['sortType'] = true;
$config->program->browse->dtable->fieldList['budget']['show']     = true;
$config->program->browse->dtable->fieldList['budget']['group']    = 3;

$config->program->browse->dtable->fieldList['invested']['name']     = 'invested';
$config->program->browse->dtable->fieldList['invested']['title']    = $lang->program->invested;
$config->program->browse->dtable->fieldList['invested']['minWidth'] = 70;
$config->program->browse->dtable->fieldList['invested']['width']    = 70;
$config->program->browse->dtable->fieldList['invested']['type']     = 'format';
$config->program->browse->dtable->fieldList['invested']['sortType'] = false;
$config->program->browse->dtable->fieldList['invested']['show']     = true;
$config->program->browse->dtable->fieldList['invested']['group']    = 3;

$config->program->browse->dtable->fieldList['openedDate']['name']     = 'openedDate';
$config->program->browse->dtable->fieldList['openedDate']['title']    = $lang->program->openedDate;
$config->program->browse->dtable->fieldList['openedDate']['type']     = 'date';
$config->program->browse->dtable->fieldList['openedDate']['sortType'] = true;
$config->program->browse->dtable->fieldList['openedDate']['minWidth'] = 90;
$config->program->browse->dtable->fieldList['openedDate']['group']    = 4;

$config->program->browse->dtable->fieldList['openedBy']['name']     = 'openedBy';
$config->program->browse->dtable->fieldList['openedBy']['title']    = $lang->program->openedBy;
$config->program->browse->dtable->fieldList['openedBy']['type']     = 'user';
$config->program->browse->dtable->fieldList['openedBy']['sortType'] = true;
$config->program->browse->dtable->fieldList['openedBy']['minWidth'] = 80;
$config->program->browse->dtable->fieldList['openedBy']['group']    = 4;

$config->program->browse->dtable->fieldList['begin']['name']     = 'begin';
$config->program->browse->dtable->fieldList['begin']['title']    = $lang->program->begin;
$config->program->browse->dtable->fieldList['begin']['minWidth'] = 90;
$config->program->browse->dtable->fieldList['begin']['type']     = 'date';
$config->program->browse->dtable->fieldList['begin']['sortType'] = true;
$config->program->browse->dtable->fieldList['begin']['show']     = true;
$config->program->browse->dtable->fieldList['begin']['group']    = 5;

$config->program->browse->dtable->fieldList['end']['name']     = 'end';
$config->program->browse->dtable->fieldList['end']['title']    = $lang->program->end;
$config->program->browse->dtable->fieldList['end']['minWidth'] = 90;
$config->program->browse->dtable->fieldList['end']['type']     = 'date';
$config->program->browse->dtable->fieldList['end']['sortType'] = true;
$config->program->browse->dtable->fieldList['end']['show']     = true;
$config->program->browse->dtable->fieldList['end']['group']    = 5;

$config->program->browse->dtable->fieldList['realBegan']['name']     = 'realBegan';
$config->program->browse->dtable->fieldList['realBegan']['title']    = $lang->program->realBeganAB;
$config->program->browse->dtable->fieldList['realBegan']['minWidth'] = 90;
$config->program->browse->dtable->fieldList['realBegan']['type']     = 'date';
$config->program->browse->dtable->fieldList['realBegan']['sortType'] = true;
$config->program->browse->dtable->fieldList['realBegan']['group']    = 5;

$config->program->browse->dtable->fieldList['realEnd']['name']     = 'realEnd';
$config->program->browse->dtable->fieldList['realEnd']['title']    = $lang->program->realEndAB;
$config->program->browse->dtable->fieldList['realEnd']['minWidth'] = 90;
$config->program->browse->dtable->fieldList['realEnd']['type']     = 'date';
$config->program->browse->dtable->fieldList['realEnd']['sortType'] = true;
$config->program->browse->dtable->fieldList['realEnd']['group']    = 5;

$config->program->browse->dtable->fieldList['progress']['name']     = 'progress';
$config->program->browse->dtable->fieldList['progress']['title']    = $lang->program->progressAB;
$config->program->browse->dtable->fieldList['progress']['minWidth'] = 100;
$config->program->browse->dtable->fieldList['progress']['type']     = 'progress';
$config->program->browse->dtable->fieldList['progress']['show']     = true;
$config->program->browse->dtable->fieldList['progress']['group']    = 5;

$config->program->browse->dtable->fieldList['actions']['name']       = 'actions';
$config->program->browse->dtable->fieldList['actions']['title']      = $lang->actions;
$config->program->browse->dtable->fieldList['actions']['width']      = 160;
$config->program->browse->dtable->fieldList['actions']['type']       = 'actions';
$config->program->browse->dtable->fieldList['actions']['fixed']      = 'right';
$config->program->browse->dtable->fieldList['actions']['actionsMap'] = array
(
    'program_start'     => array('icon'  => 'icon-start',        'hint' => $lang->program->start,          'url'  => helper::createLink('program', 'start', "programID={id}"),    'data-toggle' => 'modal'),
    'program_suspend'   => array('icon'  => 'icon-pause',        'hint' => $lang->program->suspend,        'url'  => helper::createLink('program', 'suspend', "programID={id}"),  'data-toggle' => 'modal'),
    'program_close'     => array('icon'  => 'icon-off',          'hint' => $lang->program->close,          'url'  => helper::createLink('program', 'close', "programID={id}"),    'data-toggle' => 'modal'),
    'program_activate'  => array('icon'  => 'icon-active',       'hint' => $lang->program->activate,       'url'  => helper::createLink('program', 'activate', "programID={id}"), 'data-toggle' => 'modal'),
    'program_other'     => array('caret' => true,                'hint' => $lang->other,                   'type' => 'dropdown'),
    'program_edit'      => array('icon'  => 'icon-edit',         'hint' => $lang->program->edit,           'url'  => helper::createLink('program', 'edit', "programID={id}")),
    'program_create'    => array('icon'  => 'icon-split',        'hint' => $lang->program->create,         'url'  => helper::createLink('program', 'create', "programID={id}")),
    'program_delete'    => array('icon'  => 'icon-trash',        'hint' => $lang->program->delete),
    'project_start'     => array('icon'  => 'icon-start',        'hint' => $lang->project->start,          'url'  => helper::createLink('project', 'start', "projectID={id}"),    'data-toggle' => 'modal'),
    'project_suspend'   => array('icon'  => 'icon-pause',        'hint' => $lang->project->suspend,        'url'  => helper::createLink('project', 'suspend', "projectID={id}"),  'data-toggle' => 'modal'),
    'project_close'     => array('icon'  => 'icon-off',          'hint' => $lang->project->close,          'url'  => helper::createLink('project', 'close', "projectID={id}"),    'data-toggle' => 'modal'),
    'project_activate'  => array('icon'  => 'icon-active',       'hint' => $lang->project->activate,       'url'  => helper::createLink('project', 'activate', "projectID={id}"), 'data-toggle' => 'modal'),
    'project_other'     => array('caret' => true,                'hint' => $lang->project->other,          'type' => 'dropdown'),
    'project_edit'      => array('icon'  => 'icon-edit',         'hint' => $lang->project->edit,           'url'  => helper::createLink('project', 'edit', "projectID={id}")),
    'project_team'      => array('icon'  => 'icon-groups',       'hint' => $lang->project->manageMembers,  'url'  => helper::createLink('project', 'team', "projectID={id}")),
    'project_group'     => array('icon'  => 'icon-lock',         'hint' => $lang->project->group,          'url'  => helper::createLink('project', 'group', "projectID={id}")),
    'project_more'      => array('icon'  => 'icon-ellipsis-v',   'hint' => $lang->project->moreActions,    'type' => 'dropdown', 'caret' => false),
    'project_link'      => array('icon'  => 'icon-link',         'hint' => $lang->project->manageProducts, 'url'  => helper::createLink('project', 'manageProducts', "projectID={id}")),
    'project_whitelist' => array('icon'  => 'icon-shield-check', 'hint' => $lang->project->whitelist,      'url'  => helper::createLink('project', 'whitelist', "projectID={id}")),
    'project_delete'    => array('icon'  => 'icon-trash',        'hint' => $lang->project->delete)
);
