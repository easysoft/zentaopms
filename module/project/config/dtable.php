<?php
global $lang,$app;
$config->project->dtable = new stdclass();
$config->project->dtable->team = new stdclass();
$config->project->dtable->testtask = new stdclass();
$config->project->dtable->defaultField = array('id', 'name', 'status', 'PM', 'budget', 'begin', 'end', 'progress', 'actions');

$config->project->dtable->fieldList['id']['title']    = $lang->idAB;
$config->project->dtable->fieldList['id']['name']     = 'id';
$config->project->dtable->fieldList['id']['type']     = 'checkID';
$config->project->dtable->fieldList['id']['sortType'] = true;
$config->project->dtable->fieldList['id']['checkbox'] = true;
$config->project->dtable->fieldList['id']['group']    = 1;
$config->project->dtable->fieldList['id']['required'] = true;

$config->project->dtable->fieldList['name']['title']      = $lang->project->name;
$config->project->dtable->fieldList['name']['name']       = 'name';
$config->project->dtable->fieldList['name']['type']       = 'title';
$config->project->dtable->fieldList['name']['sortType']   = true;
$config->project->dtable->fieldList['name']['link']       = array('module' => 'project', 'method' => 'index', 'params' => 'projectID={id}');
$config->project->dtable->fieldList['name']['iconRender'] = 'RAWJS<function(val,row){ if(row.data.model == \'scrum\') return \'icon-sprint text-gray\'; if([\'waterfall\', \'kanban\', \'agileplus\', \'waterfallplus\'].indexOf(row.data.model) !== -1) return \'icon-\' + row.data.model + \' text-gray\'; return \'\';}>RAWJS';
$config->project->dtable->fieldList['name']['group']      = 1;
$config->project->dtable->fieldList['name']['required']   = true;

if(!empty($config->setCode))
{
    $config->project->dtable->fieldList['code']['title']    = $lang->project->code;
    $config->project->dtable->fieldList['code']['name']     = 'code';
    $config->project->dtable->fieldList['code']['type']     = 'text';
    $config->project->dtable->fieldList['code']['sortType'] = true;
    $config->project->dtable->fieldList['code']['group']    = 1;
    $config->project->dtable->fieldList['code']['required'] = true;
}

$config->project->dtable->fieldList['status']['title']     = $lang->project->status;
$config->project->dtable->fieldList['status']['name']      = 'status';
$config->project->dtable->fieldList['status']['type']      = 'status';
$config->project->dtable->fieldList['status']['sortType']  = true;
$config->project->dtable->fieldList['status']['statusMap'] = $lang->project->statusList;
$config->project->dtable->fieldList['status']['group']     = 2;
$config->project->dtable->fieldList['status']['show']      = true;

$config->project->dtable->fieldList['hasProduct']['title']    = $lang->project->type;
$config->project->dtable->fieldList['hasProduct']['name']     = 'hasProduct';
$config->project->dtable->fieldList['hasProduct']['type']     = 'category';
$config->project->dtable->fieldList['hasProduct']['sortType'] = true;
$config->project->dtable->fieldList['hasProduct']['group']    = 2;

$config->project->dtable->fieldList['PM']['title']    = $lang->project->PM;
$config->project->dtable->fieldList['PM']['name']     = 'PM';
$config->project->dtable->fieldList['PM']['type']     = 'avatarBtn';
$config->project->dtable->fieldList['PM']['sortType'] = true;
$config->project->dtable->fieldList['PM']['group']    = 3;
$config->project->dtable->fieldList['PM']['required'] = true;

$config->project->dtable->fieldList['storyCount']['title']    = $lang->project->storyCount;
$config->project->dtable->fieldList['storyCount']['type']     = 'number';
$config->project->dtable->fieldList['storyCount']['group']    = 4;
$config->project->dtable->fieldList['storyCount']['show']     = true;
$config->project->dtable->fieldList['storyCount']['width']    = '120px';
$config->project->dtable->fieldList['storyCount']['sortType'] = false;

$config->project->dtable->fieldList['executionCount']['title']    = $lang->project->executionCount;
$config->project->dtable->fieldList['executionCount']['type']     = 'count';
$config->project->dtable->fieldList['executionCount']['group']    = 4;
$config->project->dtable->fieldList['executionCount']['show']     = 4;
$config->project->dtable->fieldList['executionCount']['sortType'] = false;

$config->project->dtable->fieldList['budget']['title']    = $lang->project->budget;
$config->project->dtable->fieldList['budget']['name']     = 'budget';
$config->project->dtable->fieldList['budget']['type']     = 'money';
$config->project->dtable->fieldList['budget']['group']    = 5;
$config->project->dtable->fieldList['budget']['show']     = true;
$config->project->dtable->fieldList['budget']['sortType'] = true;

$config->project->dtable->fieldList['teamCount']['title']    = $lang->project->teamCount;
$config->project->dtable->fieldList['teamCount']['name']     = 'teamCount';
$config->project->dtable->fieldList['teamCount']['type']     = 'number';
$config->project->dtable->fieldList['teamCount']['group']    = 5;
$config->project->dtable->fieldList['teamCount']['sortType'] = false;

$config->project->dtable->fieldList['invested']['title']    = $lang->project->invested;
$config->project->dtable->fieldList['invested']['name']     = 'invested';
$config->project->dtable->fieldList['invested']['type']     = 'count';
$config->project->dtable->fieldList['invested']['group']    = 5;
$config->project->dtable->fieldList['invested']['show']     = true;
$config->project->dtable->fieldList['invested']['sortType'] = false;

$config->project->dtable->fieldList['begin']['title']    = $lang->project->begin;
$config->project->dtable->fieldList['begin']['name']     = 'begin';
$config->project->dtable->fieldList['begin']['type']     = 'date';
$config->project->dtable->fieldList['begin']['sortType'] = true;
$config->project->dtable->fieldList['begin']['group']    = 6;
$config->project->dtable->fieldList['begin']['show']     = true;

$config->project->dtable->fieldList['end']['title']    = $lang->project->end;
$config->project->dtable->fieldList['end']['name']     = 'end';
$config->project->dtable->fieldList['end']['type']     = 'date';
$config->project->dtable->fieldList['end']['sortType'] = true;
$config->project->dtable->fieldList['end']['group']    = 6;
$config->project->dtable->fieldList['end']['show']     = true;

$config->project->dtable->fieldList['realBegan']['title'] = $lang->project->realBeganAB;
$config->project->dtable->fieldList['realBegan']['type']  = 'date';
$config->project->dtable->fieldList['realBegan']['group'] = 6;

$config->project->dtable->fieldList['realEnd']['title'] = $lang->project->realEndAB;
$config->project->dtable->fieldList['realEnd']['type']  = 'date';
$config->project->dtable->fieldList['realEnd']['group'] = 6;

$config->project->dtable->fieldList['estimate']['title']    = $lang->project->estimate;
$config->project->dtable->fieldList['estimate']['name']     = 'estimate';
$config->project->dtable->fieldList['estimate']['type']     = 'number';
$config->project->dtable->fieldList['estimate']['sortType'] = true;
$config->project->dtable->fieldList['estimate']['group']    = 7;

$config->project->dtable->fieldList['consume']['title']    = $lang->project->consume;
$config->project->dtable->fieldList['consume']['name']     = 'consume';
$config->project->dtable->fieldList['consume']['type']     = 'number';
$config->project->dtable->fieldList['consume']['group']    = 7;
$config->project->dtable->fieldList['consume']['sortType'] = false;

$config->project->dtable->fieldList['progress']['title'] = $lang->project->progress;
$config->project->dtable->fieldList['progress']['name']  = 'progress';
$config->project->dtable->fieldList['progress']['type']  = 'progress';
$config->project->dtable->fieldList['progress']['group'] = 7;
$config->project->dtable->fieldList['progress']['show']  = true;

$config->project->dtable->fieldList['actions']['name']     = 'actions';
$config->project->dtable->fieldList['actions']['title']    = $lang->actions;
$config->project->dtable->fieldList['actions']['type']     = 'actions';
$config->project->dtable->fieldList['actions']['sortType'] = false;
$config->project->dtable->fieldList['actions']['width']    = '140';
$config->project->dtable->fieldList['actions']['list']     = $config->project->actionList;
$config->project->dtable->fieldList['actions']['menu']     = array(array('start|activate|close', 'other' => array('suspend', 'activate|close')), 'edit', 'group', 'perm', 'more' => array('link', 'whitelist', 'delete'));
$config->project->dtable->fieldList['actions']['show']     = true;

global $app;
$app->loadLang('execution');
$app->loadConfig('execution');

$config->projectExecution = new stdclass();
$config->projectExecution->dtable = new stdclass();

$config->projectExecution->dtable->fieldList['rawID']['title']    = $lang->idAB;
$config->projectExecution->dtable->fieldList['rawID']['name']     = 'rawID';
$config->projectExecution->dtable->fieldList['rawID']['type']     = 'checkID';
$config->projectExecution->dtable->fieldList['rawID']['sortType'] = false;
$config->projectExecution->dtable->fieldList['rawID']['checkbox'] = true;
$config->projectExecution->dtable->fieldList['rawID']['width']    = '80';

$config->projectExecution->dtable->fieldList['name']['title']        = $lang->nameAB;
$config->projectExecution->dtable->fieldList['name']['name']         = 'nameCol';
$config->projectExecution->dtable->fieldList['name']['fixed']        = 'left';
$config->projectExecution->dtable->fieldList['name']['flex']         = 1;
$config->projectExecution->dtable->fieldList['name']['type']         = 'nestedTitle';
$config->projectExecution->dtable->fieldList['name']['nestedToggle'] = true;
$config->projectExecution->dtable->fieldList['name']['sortType']     = false;

$config->projectExecution->dtable->fieldList['productName']['title']    = $lang->execution->product;
$config->projectExecution->dtable->fieldList['productName']['name']     = 'productName';
$config->projectExecution->dtable->fieldList['productName']['type']     = 'desc';
$config->projectExecution->dtable->fieldList['productName']['sortType'] = false;
$config->projectExecution->dtable->fieldList['productName']['minWidth'] = '160';
$config->projectExecution->dtable->fieldList['productName']['group']    = '1';

$config->projectExecution->dtable->fieldList['status']['title']     = $lang->project->status;
$config->projectExecution->dtable->fieldList['status']['name']      = 'status';
$config->projectExecution->dtable->fieldList['status']['type']      = 'status';
$config->projectExecution->dtable->fieldList['status']['statusMap'] = $lang->execution->statusList + $lang->task->statusList;
$config->projectExecution->dtable->fieldList['status']['sortType']  = false;
$config->projectExecution->dtable->fieldList['status']['width']     = '80';
$config->projectExecution->dtable->fieldList['status']['group']     = '1';

$config->projectExecution->dtable->fieldList['PM']['title']    = $lang->project->PM;
$config->projectExecution->dtable->fieldList['PM']['name']     = 'PM';
$config->projectExecution->dtable->fieldList['PM']['type']     = 'avatarBtn';
$config->projectExecution->dtable->fieldList['PM']['sortType'] = false;
$config->projectExecution->dtable->fieldList['PM']['width']    = '100';
$config->projectExecution->dtable->fieldList['PM']['group']    = '2';

$config->projectExecution->dtable->fieldList['begin']['title']    = $lang->execution->begin;
$config->projectExecution->dtable->fieldList['begin']['name']     = 'begin';
$config->projectExecution->dtable->fieldList['begin']['type']     = 'date';
$config->projectExecution->dtable->fieldList['begin']['sortType'] = false;
$config->projectExecution->dtable->fieldList['begin']['width']    = '96';
$config->projectExecution->dtable->fieldList['begin']['group']    = '3';

$config->projectExecution->dtable->fieldList['end']['title']    = $lang->execution->end;
$config->projectExecution->dtable->fieldList['end']['name']     = 'end';
$config->projectExecution->dtable->fieldList['end']['type']     = 'date';
$config->projectExecution->dtable->fieldList['end']['sortType'] = false;
$config->projectExecution->dtable->fieldList['end']['width']    = '96';
$config->projectExecution->dtable->fieldList['end']['group']    = '3';

$config->projectExecution->dtable->fieldList['totalEstimate']['title']    = $lang->execution->totalEstimate;
$config->projectExecution->dtable->fieldList['totalEstimate']['name']     = 'estimate';
$config->projectExecution->dtable->fieldList['totalEstimate']['type']     = 'number';
$config->projectExecution->dtable->fieldList['totalEstimate']['sortType'] = false;
$config->projectExecution->dtable->fieldList['totalEstimate']['width']    = '64';
$config->projectExecution->dtable->fieldList['totalEstimate']['group']    = '4';

$config->projectExecution->dtable->fieldList['totalConsumed']['title']    = $lang->execution->totalConsumed;
$config->projectExecution->dtable->fieldList['totalConsumed']['name']     = 'consumed';
$config->projectExecution->dtable->fieldList['totalConsumed']['type']     = 'number';
$config->projectExecution->dtable->fieldList['totalConsumed']['sortType'] = false;
$config->projectExecution->dtable->fieldList['totalConsumed']['width']    = '64';
$config->projectExecution->dtable->fieldList['totalConsumed']['group']    = '4';

$config->projectExecution->dtable->fieldList['totalLeft']['title']    = $lang->execution->totalLeft;
$config->projectExecution->dtable->fieldList['totalLeft']['name']     = 'left';
$config->projectExecution->dtable->fieldList['totalLeft']['type']     = 'number';
$config->projectExecution->dtable->fieldList['totalLeft']['sortType'] = false;
$config->projectExecution->dtable->fieldList['totalLeft']['width']    = '64';
$config->projectExecution->dtable->fieldList['totalLeft']['group']    = '4';

$config->projectExecution->dtable->fieldList['progress']['title']    = $lang->execution->progress;
$config->projectExecution->dtable->fieldList['progress']['name']     = 'progress';
$config->projectExecution->dtable->fieldList['progress']['type']     = 'progress';
$config->projectExecution->dtable->fieldList['progress']['sortType'] = false;
$config->projectExecution->dtable->fieldList['progress']['width']    = '64';
$config->projectExecution->dtable->fieldList['progress']['group']    = '4';

$config->projectExecution->dtable->fieldList['burn']['title']    = $lang->execution->burn;
$config->projectExecution->dtable->fieldList['burn']['name']     = 'burn';
$config->projectExecution->dtable->fieldList['burn']['type']     = 'burn';
$config->projectExecution->dtable->fieldList['burn']['sortType'] = false;
$config->projectExecution->dtable->fieldList['burn']['width']    = '88';
$config->projectExecution->dtable->fieldList['burn']['group']    = '4';

$config->projectExecution->dtable->fieldList['actions']['name']          = 'actions';
$config->projectExecution->dtable->fieldList['actions']['title']         = $lang->actions;
$config->projectExecution->dtable->fieldList['actions']['type']          = 'actions';
$config->projectExecution->dtable->fieldList['actions']['width']         = '160';
$config->projectExecution->dtable->fieldList['actions']['sortType']      = false;
$config->projectExecution->dtable->fieldList['actions']['fixed']         = 'right';
$config->projectExecution->dtable->fieldList['actions']['scrum']         = array('start', 'createTask', 'edit', 'close|activate', 'delete');
$config->projectExecution->dtable->fieldList['actions']['kanban']        = array('start', 'createTask', 'edit', 'close|activate', 'delete');
$config->projectExecution->dtable->fieldList['actions']['agileplus']     = array('start', 'createTask', 'edit', 'close|activate', 'delete');
$config->projectExecution->dtable->fieldList['actions']['waterfall']     = array('start', 'createTask', 'createChildStage', 'edit', 'close|activate', 'delete');
$config->projectExecution->dtable->fieldList['actions']['waterfallplus'] = array('start', 'createTask', 'createChildStage', 'edit', 'close|activate', 'delete');
$config->projectExecution->dtable->fieldList['actions']['task']          = array('startTask', 'finishTask', 'closeTask', 'recordWorkhour', 'editTask', 'batchCreate');
$config->projectExecution->dtable->fieldList['actions']['actionsMap']    = $config->execution->actionList;

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['startTask']['icon']        = 'play';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['startTask']['hint']        = $lang->task->start;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['startTask']['url']         = helper::createLink('task', 'start', 'taskID={rawID}');
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['startTask']['data-toggle'] = 'modal';

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['finishTask']['icon']        = 'checked';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['finishTask']['hint']        = $lang->task->finish;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['finishTask']['url']         = helper::createLink('task', 'finish', 'taskID={rawID}');
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['finishTask']['data-toggle'] = 'modal';

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['closeTask']['icon']        = 'off';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['closeTask']['hint']        = $lang->task->close;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['closeTask']['url']         = helper::createLink('task', 'close', 'taskID={rawID}');
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['closeTask']['data-toggle'] = 'modal';

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['icon'] = 'time';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['hint'] = $lang->task->record;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['url']  = helper::createLink('task', 'recordWorkhour', 'taskID={rawID}');
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['data-toggle'] = 'modal';

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['editTask']['icon']  = 'edit';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['editTask']['hint']  = $lang->task->edit;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['editTask']['url']   = helper::createLink('task', 'edit', 'taskID={rawID}');

$config->projectExecution->dtable->fieldList['actions']['actionsMap']['batchCreate']['icon'] = 'split';
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['batchCreate']['hint'] = $lang->task->batchCreate;
$config->projectExecution->dtable->fieldList['actions']['actionsMap']['batchCreate']['url']  = helper::createLink('task', 'batchCreate', 'execution={execution}&storyID={story}&moduleID={module}&taskID={rawID}');

$app->loadLang('group');
$config->projectGroup = new stdclass();
$config->projectGroup->dtable = new stdclass();

$config->projectGroup->dtable->fieldList['id']['title']    = $lang->idAB;
$config->projectGroup->dtable->fieldList['id']['name']     = 'id';
$config->projectGroup->dtable->fieldList['id']['type']     = 'checkID';
$config->projectGroup->dtable->fieldList['id']['sort']     = 'number';
$config->projectGroup->dtable->fieldList['id']['fixed']    = 'left';
$config->projectGroup->dtable->fieldList['id']['checkbox'] = false;
$config->projectGroup->dtable->fieldList['id']['width']    = '80';
$config->projectGroup->dtable->fieldList['id']['group']    = 1;

$config->projectGroup->dtable->fieldList['name']['title'] = $lang->group->name;
$config->projectGroup->dtable->fieldList['name']['name']  = 'name';
$config->projectGroup->dtable->fieldList['name']['fixed'] = 'left';
$config->projectGroup->dtable->fieldList['name']['flex']  = 1;
$config->projectGroup->dtable->fieldList['name']['type']  = 'title';
$config->projectGroup->dtable->fieldList['name']['sort']  = true;
$config->projectGroup->dtable->fieldList['name']['group'] = 1;

$config->projectGroup->dtable->fieldList['desc']['title'] = $lang->group->desc;
$config->projectGroup->dtable->fieldList['desc']['name']  = 'desc';
$config->projectGroup->dtable->fieldList['desc']['type']  = 'desc';
$config->projectGroup->dtable->fieldList['desc']['sort']  = true;
$config->projectGroup->dtable->fieldList['desc']['group'] = 2;

$config->projectGroup->dtable->fieldList['users']['title'] = $lang->group->users;
$config->projectGroup->dtable->fieldList['users']['name']  = 'users';
$config->projectGroup->dtable->fieldList['users']['type']  = 'desc';
$config->projectGroup->dtable->fieldList['users']['hint']  = true;
$config->projectGroup->dtable->fieldList['users']['sort']  = true;
$config->projectGroup->dtable->fieldList['users']['group'] = 3;

$config->projectGroup->dtable->fieldList['actions']['name']     = 'actions';
$config->projectGroup->dtable->fieldList['actions']['title']    = $lang->actions;
$config->projectGroup->dtable->fieldList['actions']['type']     = 'actions';
$config->projectGroup->dtable->fieldList['actions']['width']    = '140';
$config->projectGroup->dtable->fieldList['actions']['menu']     = array('managePriv', 'manageMember', 'edit', 'copy', 'delete');
$config->projectGroup->dtable->fieldList['actions']['sortType'] = false;
$config->projectGroup->dtable->fieldList['actions']['fixed']    = 'right';

$config->projectGroup->dtable->fieldList['actions']['list']['managePriv']['icon'] = 'lock';
$config->projectGroup->dtable->fieldList['actions']['list']['managePriv']['text'] = $lang->group->managePriv;
$config->projectGroup->dtable->fieldList['actions']['list']['managePriv']['hint'] = $lang->group->managePriv;
$config->projectGroup->dtable->fieldList['actions']['list']['managePriv']['url']  = helper::createLink('project', 'managePriv', "projectID={project}&groupID={id}");

$config->projectGroup->dtable->fieldList['actions']['list']['manageMember']['icon']        = 'persons';
$config->projectGroup->dtable->fieldList['actions']['list']['manageMember']['text']        = $lang->group->manageMember;
$config->projectGroup->dtable->fieldList['actions']['list']['manageMember']['hint']        = $lang->group->manageMember;
$config->projectGroup->dtable->fieldList['actions']['list']['manageMember']['url']         = helper::createLink('project', 'manageGroupMember', "groupID={id}");
$config->projectGroup->dtable->fieldList['actions']['list']['manageMember']['data-toggle'] = 'modal';
$config->projectGroup->dtable->fieldList['actions']['list']['manageMember']['data-size']   = 'lg';

$config->projectGroup->dtable->fieldList['actions']['list']['edit']['icon']        = 'edit';
$config->projectGroup->dtable->fieldList['actions']['list']['edit']['text']        = $lang->group->edit;
$config->projectGroup->dtable->fieldList['actions']['list']['edit']['hint']        = $lang->group->edit;
$config->projectGroup->dtable->fieldList['actions']['list']['edit']['url']         = helper::createLink('project', 'editGroup', "groupID={id}");
$config->projectGroup->dtable->fieldList['actions']['list']['edit']['data-toggle'] = 'modal';
$config->projectGroup->dtable->fieldList['actions']['list']['edit']['data-size']   = 'sm';

$config->projectGroup->dtable->fieldList['actions']['list']['copy']['icon']        = 'copy';
$config->projectGroup->dtable->fieldList['actions']['list']['copy']['text']        = $lang->group->copy;
$config->projectGroup->dtable->fieldList['actions']['list']['copy']['hint']        = $lang->group->copy;
$config->projectGroup->dtable->fieldList['actions']['list']['copy']['url']         = helper::createLink('project', 'copyGroup', "groupID={id}");
$config->projectGroup->dtable->fieldList['actions']['list']['copy']['data-toggle'] = 'modal';
$config->projectGroup->dtable->fieldList['actions']['list']['copy']['data-size']   = 'sm';

$config->projectGroup->dtable->fieldList['actions']['list']['delete']['icon'] = 'trash';
$config->projectGroup->dtable->fieldList['actions']['list']['delete']['text'] = $lang->group->delete;
$config->projectGroup->dtable->fieldList['actions']['list']['delete']['hint'] = $lang->group->delete;
$config->projectGroup->dtable->fieldList['actions']['list']['delete']['url']  = 'javascript:confirmDelete("{id}", "{name}")';

$app->loadLang('execution');
$config->project->dtable->team->fieldList['account']['title']    = $lang->team->realname;
$config->project->dtable->team->fieldList['account']['align']    = 'left';
$config->project->dtable->team->fieldList['account']['name']     = 'realname';
$config->project->dtable->team->fieldList['account']['type']     = 'user';
$config->project->dtable->team->fieldList['account']['link']     = array('module' => 'user', 'method' => 'view', 'params' => 'userID={userID}');
$config->project->dtable->team->fieldList['account']['sortType'] = false;

$config->project->dtable->team->fieldList['role']['title']    = $lang->team->role;
$config->project->dtable->team->fieldList['role']['type']     = 'user';
$config->project->dtable->team->fieldList['role']['sortType'] = false;

$config->project->dtable->team->fieldList['join']['title']    = $lang->team->join;
$config->project->dtable->team->fieldList['join']['type']     = 'date';
$config->project->dtable->team->fieldList['join']['sortType'] = false;

$config->project->dtable->team->fieldList['days']['title']    = $lang->team->days;
$config->project->dtable->team->fieldList['days']['type']     = 'number';
$config->project->dtable->team->fieldList['days']['sortType'] = false;

$config->project->dtable->team->fieldList['hours']['title']    = $lang->team->hours;
$config->project->dtable->team->fieldList['hours']['type']     = 'number';
$config->project->dtable->team->fieldList['hours']['sortType'] = false;

$config->project->dtable->team->fieldList['total']['title']    = $lang->team->totalHours;
$config->project->dtable->team->fieldList['total']['type']     = 'number';
$config->project->dtable->team->fieldList['total']['sortType'] = false;

$config->project->dtable->team->fieldList['limited']['title']    = $lang->team->limited;
$config->project->dtable->team->fieldList['limited']['type']     = 'user';
$config->project->dtable->team->fieldList['limited']['map']      = $lang->team->limitedList;
$config->project->dtable->team->fieldList['limited']['sortType'] = false;

$config->project->dtable->team->fieldList['actions']['type']       = 'actions';
$config->project->dtable->team->fieldList['actions']['minWidth']   = 60;
$config->project->dtable->team->fieldList['actions']['actionsMap'] = $config->project->team->actionList;

$app->loadLang('testcase');
$app->loadLang('testtask');
$app->loadModuleConfig('testtask');

$config->project->dtable->testtask->fieldList['product']['name']  = 'productName';
$config->project->dtable->testtask->fieldList['product']['title'] = $lang->testtask->product;
$config->project->dtable->testtask->fieldList['product']['type']  = 'text';
$config->project->dtable->testtask->fieldList['product']['group'] = '1';

$config->project->dtable->testtask->fieldList['id']['name']     = 'id';
$config->project->dtable->testtask->fieldList['id']['title']    = $lang->idAB;
$config->project->dtable->testtask->fieldList['id']['type']     = 'id';
$config->project->dtable->testtask->fieldList['id']['checkbox'] = true;
$config->project->dtable->testtask->fieldList['id']['group']    = '2';
$config->project->dtable->testtask->fieldList['id']['fixed']    = false;

$config->project->dtable->testtask->fieldList['title']['name']     = 'name';
$config->project->dtable->testtask->fieldList['title']['title']    = $lang->testtask->name;
$config->project->dtable->testtask->fieldList['title']['type']     = 'title';
$config->project->dtable->testtask->fieldList['title']['link']     = array('module' => 'testtask', 'method' => 'cases', 'params' => 'taskID={id}');
$config->project->dtable->testtask->fieldList['title']['group']    = '2';
$config->project->dtable->testtask->fieldList['title']['fixed']    = false;
$config->project->dtable->testtask->fieldList['title']['width']    = '356';
$config->project->dtable->testtask->fieldList['title']['data-app'] = 'project';

$config->project->dtable->testtask->fieldList['build']['name']  = 'buildName';
$config->project->dtable->testtask->fieldList['build']['title'] = $lang->testtask->build;
$config->project->dtable->testtask->fieldList['build']['type']  = 'text';
$config->project->dtable->testtask->fieldList['build']['link']  = array('module' => 'projectbuild', 'method' => 'view', 'params' => 'buildID={build}');
$config->project->dtable->testtask->fieldList['build']['group'] = 'text';
$config->project->dtable->testtask->fieldList['build']['group'] = '3';

$config->project->dtable->testtask->fieldList['owner']['name']    = 'owner';
$config->project->dtable->testtask->fieldList['owner']['title']   = $lang->testtask->owner;
$config->project->dtable->testtask->fieldList['owner']['type']    = 'user';
$config->project->dtable->testtask->fieldList['owner']['group']   = '4';

$config->project->dtable->testtask->fieldList['begin']['name']  = 'begin';
$config->project->dtable->testtask->fieldList['begin']['title'] = $lang->testtask->begin;
$config->project->dtable->testtask->fieldList['begin']['type']  = 'date';
$config->project->dtable->testtask->fieldList['begin']['group'] = '4';

$config->project->dtable->testtask->fieldList['end']['name']  = 'end';
$config->project->dtable->testtask->fieldList['end']['title'] = $lang->testtask->end;
$config->project->dtable->testtask->fieldList['end']['type']  = 'date';
$config->project->dtable->testtask->fieldList['end']['group'] = '4';

$config->project->dtable->testtask->fieldList['status']['name']      = 'status';
$config->project->dtable->testtask->fieldList['status']['title']     = $lang->testtask->status;
$config->project->dtable->testtask->fieldList['status']['type']      = 'status';
$config->project->dtable->testtask->fieldList['status']['statusMap'] = $lang->testtask->statusList;
$config->project->dtable->testtask->fieldList['status']['group']     = '4';

$config->project->dtable->testtask->fieldList['actions']['name']     = 'actions';
$config->project->dtable->testtask->fieldList['actions']['title']    = $lang->actions;
$config->project->dtable->testtask->fieldList['actions']['type']     = 'actions';
$config->project->dtable->testtask->fieldList['actions']['sortType'] = false;
$config->project->dtable->testtask->fieldList['actions']['fixed']    = false;
$config->project->dtable->testtask->fieldList['actions']['list']     = $config->testtask->actionList;
$config->project->dtable->testtask->fieldList['actions']['menu']     = array('cases', 'linkCase', 'report', 'edit', 'delete');
