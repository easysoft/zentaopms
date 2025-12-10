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
if($config->vision != 'lite') $config->project->dtable->fieldList['name']['iconRender'] = 'RAWJS<function(val,row){ if(row.data.model == \'scrum\') return \'icon-sprint text-gray\'; if([\'waterfall\', \'kanban\', \'agileplus\', \'waterfallplus\', \'ipd\'].indexOf(row.data.model) !== -1) return \'icon-\' + row.data.model + \' text-gray\'; return \'\';}>RAWJS';
$config->project->dtable->fieldList['name']['group']      = 1;
$config->project->dtable->fieldList['name']['required']   = true;

if(!empty($config->setCode))
{
    $config->project->dtable->fieldList['code']['title']    = $lang->project->code;
    $config->project->dtable->fieldList['code']['name']     = 'code';
    $config->project->dtable->fieldList['code']['type']     = 'text';
    $config->project->dtable->fieldList['code']['sortType'] = true;
    $config->project->dtable->fieldList['code']['group']    = 1;
    $config->project->dtable->fieldList['code']['required'] = false;
}

$config->project->dtable->fieldList['status']['title']     = $lang->project->status;
$config->project->dtable->fieldList['status']['name']      = 'status';
$config->project->dtable->fieldList['status']['type']      = 'status';
$config->project->dtable->fieldList['status']['sortType']  = true;
$config->project->dtable->fieldList['status']['statusMap'] = $lang->project->statusList;
$config->project->dtable->fieldList['status']['group']     = 2;
$config->project->dtable->fieldList['status']['show']      = true;

$config->project->dtable->fieldList['PM']['title']       = $lang->project->PM;
$config->project->dtable->fieldList['PM']['name']        = 'PM';
$config->project->dtable->fieldList['PM']['type']        = 'avatarBtn';
$config->project->dtable->fieldList['PM']['sortType']    = true;
$config->project->dtable->fieldList['PM']['link']        = array('module' => 'user', 'method' => 'profile', 'params' => 'userID={PMUserID}');
$config->project->dtable->fieldList['PM']['data-toggle'] = 'modal';
$config->project->dtable->fieldList['PM']['data-size']   = 'sm';
$config->project->dtable->fieldList['PM']['group']       = 3;
$config->project->dtable->fieldList['PM']['show']        = true;

$config->project->dtable->fieldList['storyPoints']['title']    = $lang->project->storyPoints;
$config->project->dtable->fieldList['storyPoints']['type']     = 'count';
$config->project->dtable->fieldList['storyPoints']['group']    = 4;
$config->project->dtable->fieldList['storyPoints']['show']     = true;
$config->project->dtable->fieldList['storyPoints']['width']    = '120px';
$config->project->dtable->fieldList['storyPoints']['sortType'] = false;

$config->project->dtable->fieldList['storyCount']['title']    = $lang->project->storyCount;
$config->project->dtable->fieldList['storyCount']['type']     = 'number';
$config->project->dtable->fieldList['storyCount']['show']     = true;
$config->project->dtable->fieldList['storyCount']['group']    = 4;
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

if(helper::hasFeature('deliverable') && in_array($config->edition, array('max', 'ipd')))
{
    $config->project->dtable->fieldList['deliverable']['title']    = $lang->project->deliverableAbbr;
    $config->project->dtable->fieldList['deliverable']['name']     = 'deliverable';
    $config->project->dtable->fieldList['deliverable']['type']     = 'html';
    $config->project->dtable->fieldList['deliverable']['width']    = '120px';
    $config->project->dtable->fieldList['deliverable']['group']    = 5;
    $config->project->dtable->fieldList['deliverable']['show']     = true;
    $config->project->dtable->fieldList['deliverable']['sortType'] = false;
}

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

if(!isset($config->project->execution)) $config->project->execution = new stdclass();
$config->project->execution->dtable = new stdclass();

$config->project->execution->dtable->fieldList['rawID']['title']    = $lang->idAB;
$config->project->execution->dtable->fieldList['rawID']['name']     = 'rawID';
$config->project->execution->dtable->fieldList['rawID']['type']     = 'checkID';
$config->project->execution->dtable->fieldList['rawID']['sortType'] = 'desc';
$config->project->execution->dtable->fieldList['rawID']['checkbox'] = true;
$config->project->execution->dtable->fieldList['rawID']['width']    = '80';
$config->project->execution->dtable->fieldList['rawID']['show']     = true;

$config->project->execution->dtable->fieldList['name']['title']        = $lang->nameAB;
$config->project->execution->dtable->fieldList['name']['name']         = 'nameCol';
$config->project->execution->dtable->fieldList['name']['fixed']        = 'left';
$config->project->execution->dtable->fieldList['name']['flex']         = 1;
$config->project->execution->dtable->fieldList['name']['type']         = 'nestedTitle';
$config->project->execution->dtable->fieldList['name']['link']         = array('module' => 'execution', 'method' => 'task', 'params' => 'executionID={rawID}');
$config->project->execution->dtable->fieldList['name']['nestedToggle'] = true;
$config->project->execution->dtable->fieldList['name']['sortType']     = true;
$config->project->execution->dtable->fieldList['name']['show']         = true;

$config->project->execution->dtable->fieldList['productName']['title']    = $lang->execution->product;
$config->project->execution->dtable->fieldList['productName']['name']     = 'productName';
$config->project->execution->dtable->fieldList['productName']['type']     = 'desc';
$config->project->execution->dtable->fieldList['productName']['sortType'] = false;
$config->project->execution->dtable->fieldList['productName']['minWidth'] = '160';
$config->project->execution->dtable->fieldList['productName']['group']    = '1';
$config->project->execution->dtable->fieldList['productName']['show']     = true;

$config->project->execution->dtable->fieldList['status']['title']     = $lang->project->status;
$config->project->execution->dtable->fieldList['status']['name']      = 'status';
$config->project->execution->dtable->fieldList['status']['type']      = 'status';
$config->project->execution->dtable->fieldList['status']['statusMap'] = $lang->execution->statusList + $lang->task->statusList;
$config->project->execution->dtable->fieldList['status']['sortType']  = true;
$config->project->execution->dtable->fieldList['status']['width']     = '80';
$config->project->execution->dtable->fieldList['status']['group']     = '1';
$config->project->execution->dtable->fieldList['status']['show']      = true;

if(helper::hasFeature('deliverable') && in_array($config->edition, array('max', 'ipd')))
{
    $config->project->execution->dtable->fieldList['deliverable']['title']    = $lang->project->deliverableAbbr;
    $config->project->execution->dtable->fieldList['deliverable']['name']     = 'deliverable';
    $config->project->execution->dtable->fieldList['deliverable']['type']     = 'html';
    $config->project->execution->dtable->fieldList['deliverable']['width']    = '100px';
    $config->project->execution->dtable->fieldList['deliverable']['group']    = '1';
    $config->project->execution->dtable->fieldList['deliverable']['show']     = true;
    $config->project->execution->dtable->fieldList['deliverable']['sortType'] = false;
}

$config->project->execution->dtable->fieldList['PM']['title']    = $lang->project->PM;
$config->project->execution->dtable->fieldList['PM']['name']     = 'PM';
$config->project->execution->dtable->fieldList['PM']['type']     = 'avatarBtn';
$config->project->execution->dtable->fieldList['PM']['sortType'] = true;
$config->project->execution->dtable->fieldList['PM']['width']    = '100';
$config->project->execution->dtable->fieldList['PM']['group']    = '2';
$config->project->execution->dtable->fieldList['PM']['show']     = true;

$config->project->execution->dtable->fieldList['openedDate']['title']    = $lang->execution->openedDate;
$config->project->execution->dtable->fieldList['openedDate']['name']     = 'openedDate';
$config->project->execution->dtable->fieldList['openedDate']['type']     = 'date';
$config->project->execution->dtable->fieldList['openedDate']['sortType'] = true;
$config->project->execution->dtable->fieldList['openedDate']['width']    = '96';
$config->project->execution->dtable->fieldList['openedDate']['group']    = '3';
$config->project->execution->dtable->fieldList['openedDate']['show']     = true;

$config->project->execution->dtable->fieldList['begin']['title']    = $lang->execution->begin;
$config->project->execution->dtable->fieldList['begin']['name']     = 'begin';
$config->project->execution->dtable->fieldList['begin']['type']     = 'date';
$config->project->execution->dtable->fieldList['begin']['sortType'] = true;
$config->project->execution->dtable->fieldList['begin']['width']    = '96';
$config->project->execution->dtable->fieldList['begin']['group']    = '3';
$config->project->execution->dtable->fieldList['begin']['show']     = true;

$config->project->execution->dtable->fieldList['end']['title']    = $lang->execution->end;
$config->project->execution->dtable->fieldList['end']['name']     = 'end';
$config->project->execution->dtable->fieldList['end']['type']     = 'date';
$config->project->execution->dtable->fieldList['end']['sortType'] = true;
$config->project->execution->dtable->fieldList['end']['width']    = '96';
$config->project->execution->dtable->fieldList['end']['group']    = '3';
$config->project->execution->dtable->fieldList['end']['show']     = true;

$config->project->execution->dtable->fieldList['realBegan']['title']    = $lang->execution->realBeganAB;
$config->project->execution->dtable->fieldList['realBegan']['name']     = 'realBegan';
$config->project->execution->dtable->fieldList['realBegan']['type']     = 'date';
$config->project->execution->dtable->fieldList['realBegan']['sortType'] = true;
$config->project->execution->dtable->fieldList['realBegan']['width']    = '106';
$config->project->execution->dtable->fieldList['realBegan']['group']    = '3';
$config->project->execution->dtable->fieldList['realBegan']['show']     = true;

$config->project->execution->dtable->fieldList['realEnd']['title']    = $lang->execution->realEndAB;
$config->project->execution->dtable->fieldList['realEnd']['name']     = 'realEnd';
$config->project->execution->dtable->fieldList['realEnd']['type']     = 'date';
$config->project->execution->dtable->fieldList['realEnd']['sortType'] = true;
$config->project->execution->dtable->fieldList['realEnd']['width']    = '106';
$config->project->execution->dtable->fieldList['realEnd']['group']    = '3';
$config->project->execution->dtable->fieldList['realEnd']['show']     = true;

$config->project->execution->dtable->fieldList['totalEstimate']['title']    = $lang->execution->totalEstimate;
$config->project->execution->dtable->fieldList['totalEstimate']['name']     = 'estimate';
$config->project->execution->dtable->fieldList['totalEstimate']['type']     = 'number';
$config->project->execution->dtable->fieldList['totalEstimate']['sortType'] = false;
$config->project->execution->dtable->fieldList['totalEstimate']['width']    = '64';
$config->project->execution->dtable->fieldList['totalEstimate']['group']    = '4';
$config->project->execution->dtable->fieldList['totalEstimate']['show']     = true;

$config->project->execution->dtable->fieldList['totalConsumed']['title']    = $lang->execution->totalConsumed;
$config->project->execution->dtable->fieldList['totalConsumed']['name']     = 'consumed';
$config->project->execution->dtable->fieldList['totalConsumed']['type']     = 'number';
$config->project->execution->dtable->fieldList['totalConsumed']['sortType'] = false;
$config->project->execution->dtable->fieldList['totalConsumed']['width']    = '64';
$config->project->execution->dtable->fieldList['totalConsumed']['group']    = '4';
$config->project->execution->dtable->fieldList['totalConsumed']['show']     = true;

$config->project->execution->dtable->fieldList['totalLeft']['title']    = $lang->execution->totalLeft;
$config->project->execution->dtable->fieldList['totalLeft']['name']     = 'left';
$config->project->execution->dtable->fieldList['totalLeft']['type']     = 'number';
$config->project->execution->dtable->fieldList['totalLeft']['sortType'] = false;
$config->project->execution->dtable->fieldList['totalLeft']['width']    = '64';
$config->project->execution->dtable->fieldList['totalLeft']['group']    = '4';
$config->project->execution->dtable->fieldList['totalLeft']['show']     = true;

$config->project->execution->dtable->fieldList['progress']['title']    = $lang->execution->progress;
$config->project->execution->dtable->fieldList['progress']['name']     = 'progress';
$config->project->execution->dtable->fieldList['progress']['type']     = 'progress';
$config->project->execution->dtable->fieldList['progress']['sortType'] = false;
$config->project->execution->dtable->fieldList['progress']['width']    = '64';
$config->project->execution->dtable->fieldList['progress']['group']    = '4';
$config->project->execution->dtable->fieldList['progress']['show']     = true;

$config->project->execution->dtable->fieldList['burns']['title']    = $lang->execution->burn;
$config->project->execution->dtable->fieldList['burns']['name']     = 'burns';
$config->project->execution->dtable->fieldList['burns']['type']     = 'burn';
$config->project->execution->dtable->fieldList['burns']['sortType'] = false;
$config->project->execution->dtable->fieldList['burns']['width']    = '88';
$config->project->execution->dtable->fieldList['burns']['group']    = '4';
$config->project->execution->dtable->fieldList['burns']['show']     = true;

$config->project->execution->dtable->fieldList['actions']['name']       = 'actions';
$config->project->execution->dtable->fieldList['actions']['title']      = $lang->actions;
$config->project->execution->dtable->fieldList['actions']['type']       = 'actions';
$config->project->execution->dtable->fieldList['actions']['width']      = '160';
$config->project->execution->dtable->fieldList['actions']['sortType']   = false;
$config->project->execution->dtable->fieldList['actions']['fixed']      = 'right';
$config->project->execution->dtable->fieldList['actions']['actionsMap'] = $config->execution->actionList;

$config->project->execution->dtable->fieldList['actions']['actionsMap']['startTask']['icon']        = 'play';
$config->project->execution->dtable->fieldList['actions']['actionsMap']['startTask']['hint']        = $lang->task->start;
$config->project->execution->dtable->fieldList['actions']['actionsMap']['startTask']['url']         = helper::createLink('task', 'start', 'taskID={rawID}');
$config->project->execution->dtable->fieldList['actions']['actionsMap']['startTask']['data-toggle'] = 'modal';

$config->project->execution->dtable->fieldList['actions']['actionsMap']['finishTask']['icon']        = 'checked';
$config->project->execution->dtable->fieldList['actions']['actionsMap']['finishTask']['hint']        = $lang->task->finish;
$config->project->execution->dtable->fieldList['actions']['actionsMap']['finishTask']['url']         = helper::createLink('task', 'finish', 'taskID={rawID}');
$config->project->execution->dtable->fieldList['actions']['actionsMap']['finishTask']['data-toggle'] = 'modal';

$config->project->execution->dtable->fieldList['actions']['actionsMap']['closeTask']['icon']        = 'off';
$config->project->execution->dtable->fieldList['actions']['actionsMap']['closeTask']['hint']        = $lang->task->close;
$config->project->execution->dtable->fieldList['actions']['actionsMap']['closeTask']['url']         = helper::createLink('task', 'close', 'taskID={rawID}');
$config->project->execution->dtable->fieldList['actions']['actionsMap']['closeTask']['data-toggle'] = 'modal';

$config->project->execution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['icon'] = 'time';
$config->project->execution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['hint'] = $lang->task->record;
$config->project->execution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['url']  = helper::createLink('task', 'recordWorkhour', 'taskID={rawID}');
$config->project->execution->dtable->fieldList['actions']['actionsMap']['recordWorkhour']['data-toggle'] = 'modal';

$config->project->execution->dtable->fieldList['actions']['actionsMap']['editTask']['icon']  = 'edit';
$config->project->execution->dtable->fieldList['actions']['actionsMap']['editTask']['hint']  = $lang->task->edit;
$config->project->execution->dtable->fieldList['actions']['actionsMap']['editTask']['url']   = helper::createLink('task', 'edit', 'taskID={rawID}');

$config->project->execution->dtable->fieldList['actions']['actionsMap']['batchCreate']['icon'] = 'split';
$config->project->execution->dtable->fieldList['actions']['actionsMap']['batchCreate']['hint'] = $lang->task->batchCreate;
$config->project->execution->dtable->fieldList['actions']['actionsMap']['batchCreate']['url']  = helper::createLink('task', 'batchCreate', 'execution={execution}&storyID={story}&moduleID={module}&taskID={rawID}');

$config->project->execution->dtable->fieldList['actions']['actionsMap']['confirmStoryChange']['icon']     = 'search';
$config->project->execution->dtable->fieldList['actions']['actionsMap']['confirmStoryChange']['hint']     = $lang->task->confirmStoryChange;
$config->project->execution->dtable->fieldList['actions']['actionsMap']['confirmStoryChange']['url']      = helper::createLink('task', 'confirmStoryChange', 'taskID={rawID}');
$config->project->execution->dtable->fieldList['actions']['actionsMap']['confirmStoryChange']['data-app'] = $app->tab;

$config->project->execution->dtable->actionsRule['scrum']         = array('start', 'createTask', 'edit', 'close|activate', 'delete');
$config->project->execution->dtable->actionsRule['kanban']        = array('start', 'createTask', 'edit', 'close|activate', 'delete');
$config->project->execution->dtable->actionsRule['agileplus']     = array('start', 'createTask', 'edit', 'close|activate', 'delete');
$config->project->execution->dtable->actionsRule['waterfall']     = array('start', 'createTask', 'createChildStage', 'edit', 'close|activate', 'delete');
$config->project->execution->dtable->actionsRule['waterfallplus'] = array('start', 'createTask', 'createChildStage', 'edit', 'close|activate', 'delete');
$config->project->execution->dtable->actionsRule['task']          = array('startTask', 'finishTask', 'closeTask', 'recordWorkhour', 'editTask', 'batchCreate');

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
$config->projectGroup->dtable->fieldList['actions']['menu']     = array('managePriv', 'manageGroupMember', 'edit', 'copyGroup', 'delete');
$config->projectGroup->dtable->fieldList['actions']['sortType'] = false;
$config->projectGroup->dtable->fieldList['actions']['fixed']    = 'right';

$config->projectGroup->dtable->fieldList['actions']['list']['managePriv']['icon'] = 'lock';
$config->projectGroup->dtable->fieldList['actions']['list']['managePriv']['text'] = $lang->group->managePriv;
$config->projectGroup->dtable->fieldList['actions']['list']['managePriv']['hint'] = $lang->group->managePriv;
$config->projectGroup->dtable->fieldList['actions']['list']['managePriv']['url']  = helper::createLink('project', 'managePriv', "projectID={project}&groupID={id}");

$config->projectGroup->dtable->fieldList['actions']['list']['manageGroupMember']['icon']        = 'persons';
$config->projectGroup->dtable->fieldList['actions']['list']['manageGroupMember']['text']        = $lang->group->manageMember;
$config->projectGroup->dtable->fieldList['actions']['list']['manageGroupMember']['hint']        = $lang->group->manageMember;
$config->projectGroup->dtable->fieldList['actions']['list']['manageGroupMember']['url']         = helper::createLink('project', 'manageGroupMember', "groupID={id}");
$config->projectGroup->dtable->fieldList['actions']['list']['manageGroupMember']['data-toggle'] = 'modal';
$config->projectGroup->dtable->fieldList['actions']['list']['manageGroupMember']['data-size']   = 'lg';

$config->projectGroup->dtable->fieldList['actions']['list']['edit']['icon']        = 'edit';
$config->projectGroup->dtable->fieldList['actions']['list']['edit']['text']        = $lang->group->edit;
$config->projectGroup->dtable->fieldList['actions']['list']['edit']['hint']        = $lang->group->edit;
$config->projectGroup->dtable->fieldList['actions']['list']['edit']['url']         = helper::createLink('project', 'editGroup', "groupID={id}");
$config->projectGroup->dtable->fieldList['actions']['list']['edit']['data-toggle'] = 'modal';
$config->projectGroup->dtable->fieldList['actions']['list']['edit']['data-size']   = 'sm';

$config->projectGroup->dtable->fieldList['actions']['list']['copyGroup']['icon']        = 'copy';
$config->projectGroup->dtable->fieldList['actions']['list']['copyGroup']['text']        = $lang->group->copy;
$config->projectGroup->dtable->fieldList['actions']['list']['copyGroup']['hint']        = $lang->group->copy;
$config->projectGroup->dtable->fieldList['actions']['list']['copyGroup']['url']         = helper::createLink('project', 'copyGroup', "groupID={id}");
$config->projectGroup->dtable->fieldList['actions']['list']['copyGroup']['data-toggle'] = 'modal';
$config->projectGroup->dtable->fieldList['actions']['list']['copyGroup']['data-size']   = 'sm';

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
$config->project->dtable->team->fieldList['actions']['title']      = $lang->actions;
$config->project->dtable->team->fieldList['actions']['minWidth']   = 60;
$config->project->dtable->team->fieldList['actions']['actionsMap'] = $config->project->team->actionList;

$app->loadLang('testcase');
$app->loadLang('testtask');
$app->loadModuleConfig('testtask');

$config->project->dtable->testtask->fieldList['product']['name']  = 'productName';
$config->project->dtable->testtask->fieldList['product']['title'] = $lang->testtask->product;
$config->project->dtable->testtask->fieldList['product']['type']  = 'text';
$config->project->dtable->testtask->fieldList['product']['group'] = '1';
$config->project->dtable->testtask->fieldList['product']['fixed'] = 'left';

$config->project->dtable->testtask->fieldList['id']['name']     = 'idName';
$config->project->dtable->testtask->fieldList['id']['title']    = $lang->idAB;
$config->project->dtable->testtask->fieldList['id']['type']     = 'checkID';
$config->project->dtable->testtask->fieldList['id']['checkbox'] = true;
$config->project->dtable->testtask->fieldList['id']['group']    = '2';
$config->project->dtable->testtask->fieldList['id']['fixed']    = 'left';

$config->project->dtable->testtask->fieldList['title']['name']     = 'name';
$config->project->dtable->testtask->fieldList['title']['title']    = $lang->testtask->name;
$config->project->dtable->testtask->fieldList['title']['type']     = 'title';
$config->project->dtable->testtask->fieldList['title']['link']     = array('module' => 'testtask', 'method' => 'cases', 'params' => 'taskID={id}');
$config->project->dtable->testtask->fieldList['title']['group']    = '2';
$config->project->dtable->testtask->fieldList['title']['fixed']    = 'left';
$config->project->dtable->testtask->fieldList['title']['width']    = '356';
$config->project->dtable->testtask->fieldList['title']['data-app'] = 'project';

$config->project->dtable->testtask->fieldList['pri']['name']  = 'pri';
$config->project->dtable->testtask->fieldList['pri']['title'] = $lang->priAB;
$config->project->dtable->testtask->fieldList['pri']['type']  = 'pri';
$config->project->dtable->testtask->fieldList['pri']['show']  = true;

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
$config->project->dtable->testtask->fieldList['actions']['fixed']    = 'right';
$config->project->dtable->testtask->fieldList['actions']['list']     = $config->testtask->actionList;
$config->project->dtable->testtask->fieldList['actions']['menu']     = array(array('start', 'other' => array('activate', 'close')), 'cases', 'linkCase', 'report', 'edit', 'delete');
