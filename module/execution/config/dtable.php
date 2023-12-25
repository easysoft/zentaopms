<?php
$config->execution->dtable = new stdclass();
$config->execution->team = new stdclass();
$config->execution->team->dtable = new stdclass();

$config->execution->dtable->fieldList['rawID']['title']    = $lang->idAB;
$config->execution->dtable->fieldList['rawID']['name']     = 'rawID';
$config->execution->dtable->fieldList['rawID']['type']     = 'checkID';
$config->execution->dtable->fieldList['rawID']['sortType'] = 'desc';
$config->execution->dtable->fieldList['rawID']['checkbox'] = true;
$config->execution->dtable->fieldList['rawID']['width']    = '80';
$config->execution->dtable->fieldList['rawID']['required'] = true;

$config->execution->dtable->fieldList['nameCol']['title']        = $lang->execution->name;
$config->execution->dtable->fieldList['nameCol']['name']         = 'nameCol';
$config->execution->dtable->fieldList['nameCol']['fixed']        = 'left';
$config->execution->dtable->fieldList['nameCol']['type']         = 'nestedTitle';
$config->execution->dtable->fieldList['nameCol']['sortType']     = true;
$config->execution->dtable->fieldList['nameCol']['minWidth']     = '356';
$config->execution->dtable->fieldList['nameCol']['nestedToggle'] = true;
$config->execution->dtable->fieldList['nameCol']['required']     = true;

if(isset($config->setCode) and $config->setCode == 1)
{
    $config->execution->dtable->fieldList['code']['title']    = $lang->execution->code;
    $config->execution->dtable->fieldList['code']['name']     = 'code';
    $config->execution->dtable->fieldList['code']['type']     = 'text';
    $config->execution->dtable->fieldList['code']['sortType'] = true;
    $config->execution->dtable->fieldList['code']['group']    = '1';
    $config->execution->dtable->fieldList['code']['width']    = '136';
    $config->execution->dtable->fieldList['code']['show']     = 'true';
}

$config->execution->dtable->fieldList['project']['title']    = $lang->execution->project;
$config->execution->dtable->fieldList['project']['name']     = 'project';
$config->execution->dtable->fieldList['project']['type']     = 'desc';
$config->execution->dtable->fieldList['project']['sortType'] = true;
$config->execution->dtable->fieldList['project']['width']    = '160';
$config->execution->dtable->fieldList['project']['group']    = '1';
$config->execution->dtable->fieldList['project']['show']     = true;

$config->execution->dtable->fieldList['status']['title']     = $lang->execution->status;
$config->execution->dtable->fieldList['status']['name']      = 'status';
$config->execution->dtable->fieldList['status']['type']      = 'status';
$config->execution->dtable->fieldList['status']['statusMap'] = $lang->execution->statusList;
$config->execution->dtable->fieldList['status']['sortType']  = true;
$config->execution->dtable->fieldList['status']['width']     = '80';
$config->execution->dtable->fieldList['status']['group']     = '1';
$config->execution->dtable->fieldList['status']['show']      = true;

$config->execution->dtable->fieldList['PM']['title']    = $lang->execution->PM;
$config->execution->dtable->fieldList['PM']['name']     = 'PM';
$config->execution->dtable->fieldList['PM']['type']     = 'avatarBtn';
$config->execution->dtable->fieldList['PM']['sortType'] = true;
$config->execution->dtable->fieldList['PM']['width']    = '100';
$config->execution->dtable->fieldList['PM']['group']    = '2';
$config->execution->dtable->fieldList['PM']['show']     = true;

$config->execution->dtable->fieldList['openedDate']['title']    = $lang->execution->openedDate;
$config->execution->dtable->fieldList['openedDate']['name']     = 'openedDate';
$config->execution->dtable->fieldList['openedDate']['type']     = 'date';
$config->execution->dtable->fieldList['openedDate']['sortType'] = true;
$config->execution->dtable->fieldList['openedDate']['width']    = '96';
$config->execution->dtable->fieldList['openedDate']['group']    = '3';

$config->execution->dtable->fieldList['begin']['title']    = $lang->execution->begin;
$config->execution->dtable->fieldList['begin']['name']     = 'begin';
$config->execution->dtable->fieldList['begin']['type']     = 'date';
$config->execution->dtable->fieldList['begin']['sortType'] = true;
$config->execution->dtable->fieldList['begin']['width']    = '96';
$config->execution->dtable->fieldList['begin']['group']    = '3';
$config->execution->dtable->fieldList['begin']['show']     = true;

$config->execution->dtable->fieldList['end']['title']    = $lang->execution->end;
$config->execution->dtable->fieldList['end']['name']     = 'end';
$config->execution->dtable->fieldList['end']['type']     = 'date';
$config->execution->dtable->fieldList['end']['sortType'] = true;
$config->execution->dtable->fieldList['end']['width']    = '96';
$config->execution->dtable->fieldList['end']['group']    = '3';
$config->execution->dtable->fieldList['end']['show']     = true;

$config->execution->dtable->fieldList['realBegan']['title']    = $lang->execution->realBeganAB;
$config->execution->dtable->fieldList['realBegan']['name']     = 'realBegan';
$config->execution->dtable->fieldList['realBegan']['type']     = 'date';
$config->execution->dtable->fieldList['realBegan']['sortType'] = true;
$config->execution->dtable->fieldList['realBegan']['width']    = '106';
$config->execution->dtable->fieldList['realBegan']['group']    = '3';

$config->execution->dtable->fieldList['realEnd']['title']    = $lang->execution->realEndAB;
$config->execution->dtable->fieldList['realEnd']['name']     = 'realEnd';
$config->execution->dtable->fieldList['realEnd']['type']     = 'date';
$config->execution->dtable->fieldList['realEnd']['sortType'] = true;
$config->execution->dtable->fieldList['realEnd']['width']    = '106';
$config->execution->dtable->fieldList['realEnd']['group']    = '3';

$config->execution->dtable->fieldList['totalEstimate']['title']    = $lang->execution->totalEstimate;
$config->execution->dtable->fieldList['totalEstimate']['name']     = 'estimate';
$config->execution->dtable->fieldList['totalEstimate']['type']     = 'number';
$config->execution->dtable->fieldList['totalEstimate']['sortType'] = false;
$config->execution->dtable->fieldList['totalEstimate']['width']    = '64';
$config->execution->dtable->fieldList['totalEstimate']['group']    = '4';
$config->execution->dtable->fieldList['totalEstimate']['show']     = true;

$config->execution->dtable->fieldList['totalConsumed']['title']    = $lang->execution->totalConsumed;
$config->execution->dtable->fieldList['totalConsumed']['name']     = 'consumed';
$config->execution->dtable->fieldList['totalConsumed']['type']     = 'number';
$config->execution->dtable->fieldList['totalConsumed']['sortType'] = false;
$config->execution->dtable->fieldList['totalConsumed']['width']    = '64';
$config->execution->dtable->fieldList['totalConsumed']['group']    = '4';
$config->execution->dtable->fieldList['totalConsumed']['show']     = true;

$config->execution->dtable->fieldList['totalLeft']['title']    = $lang->execution->totalLeft;
$config->execution->dtable->fieldList['totalLeft']['name']     = 'left';
$config->execution->dtable->fieldList['totalLeft']['type']     = 'number';
$config->execution->dtable->fieldList['totalLeft']['sortType'] = false;
$config->execution->dtable->fieldList['totalLeft']['width']    = '64';
$config->execution->dtable->fieldList['totalLeft']['group']    = '4';
$config->execution->dtable->fieldList['totalLeft']['show']     = true;

$config->execution->dtable->fieldList['progress']['title']    = $lang->execution->progress;
$config->execution->dtable->fieldList['progress']['name']     = 'progress';
$config->execution->dtable->fieldList['progress']['type']     = 'progress';
$config->execution->dtable->fieldList['progress']['sortType'] = false;
$config->execution->dtable->fieldList['progress']['width']    = '64';
$config->execution->dtable->fieldList['progress']['group']    = '4';
$config->execution->dtable->fieldList['progress']['show']     = true;

$config->execution->dtable->fieldList['burn']['title']    = $lang->execution->burn;
$config->execution->dtable->fieldList['burn']['name']     = 'burns';
$config->execution->dtable->fieldList['burn']['type']     = 'burn';
$config->execution->dtable->fieldList['burn']['sortType'] = false;
$config->execution->dtable->fieldList['burn']['width']    = '88';
$config->execution->dtable->fieldList['burn']['group']    = '4';
$config->execution->dtable->fieldList['burn']['show']     = true;

$config->execution->team->actionList['unlink']['icon'] = 'unlink';
$config->execution->team->actionList['unlink']['hint'] = $lang->execution->unlinkMember;
$config->execution->team->actionList['unlink']['url']  = 'javascript:deleteMember("{root}", "{userID}")';

$config->execution->team->dtable->fieldList['account']['title']    = $lang->team->realname;
$config->execution->team->dtable->fieldList['account']['align']    = 'left';
$config->execution->team->dtable->fieldList['account']['name']     = 'realname';
$config->execution->team->dtable->fieldList['account']['type']     = 'user';
$config->execution->team->dtable->fieldList['account']['link']     = array('module' => 'user', 'method' => 'view', 'params' => 'userID={userID}');
$config->execution->team->dtable->fieldList['account']['sortType'] = false;

$config->execution->team->dtable->fieldList['role']['title']    = $lang->team->role;
$config->execution->team->dtable->fieldList['role']['type']     = 'user';
$config->execution->team->dtable->fieldList['role']['sortType'] = false;

$config->execution->team->dtable->fieldList['join']['title']    = $lang->team->join;
$config->execution->team->dtable->fieldList['join']['type']     = 'date';
$config->execution->team->dtable->fieldList['join']['sortType'] = false;

$config->execution->team->dtable->fieldList['days']['title']    = $lang->team->days;
$config->execution->team->dtable->fieldList['days']['type']     = 'number';
$config->execution->team->dtable->fieldList['days']['sortType'] = false;

$config->execution->team->dtable->fieldList['hours']['title']    = $lang->team->hours;
$config->execution->team->dtable->fieldList['hours']['type']     = 'number';
$config->execution->team->dtable->fieldList['hours']['sortType'] = false;

$config->execution->team->dtable->fieldList['total']['title']    = $lang->team->totalHours;
$config->execution->team->dtable->fieldList['total']['type']     = 'number';
$config->execution->team->dtable->fieldList['total']['sortType'] = false;

$config->execution->team->dtable->fieldList['limited']['title']    = $lang->team->limited;
$config->execution->team->dtable->fieldList['limited']['type']     = 'user';
$config->execution->team->dtable->fieldList['limited']['map']      = $lang->team->limitedList;
$config->execution->team->dtable->fieldList['limited']['sortType'] = false;

$config->execution->team->dtable->fieldList['actions']['type']       = 'actions';
$config->execution->team->dtable->fieldList['actions']['minWidth']   = 60;
$config->execution->team->dtable->fieldList['actions']['actionsMap'] = $config->execution->team->actionList;

global $app;
$app->loadLang('bug');
$app->loadLang('task');
$config->execution->importBug = new stdclass();
$config->execution->importBug->dtable = new stdclass();
$config->execution->importBug->dtable->fieldList['id']['title']    = $lang->idAB;
$config->execution->importBug->dtable->fieldList['id']['type']     = 'checkID';
$config->execution->importBug->dtable->fieldList['id']['sortType'] = false;

$config->execution->importBug->dtable->fieldList['severity']['title']    = $lang->bug->abbr->severity;
$config->execution->importBug->dtable->fieldList['severity']['type']     = 'severity';
$config->execution->importBug->dtable->fieldList['severity']['sortType'] = false;
$config->execution->importBug->dtable->fieldList['severity']['fixed']    = 'left';

$config->execution->importBug->dtable->fieldList['pri']['title']    = $lang->execution->pri;
$config->execution->importBug->dtable->fieldList['pri']['type']     = 'pri';
$config->execution->importBug->dtable->fieldList['pri']['sortType'] = false;
$config->execution->importBug->dtable->fieldList['pri']['fixed']    = 'left';

$config->execution->importBug->dtable->fieldList['title']['title']       = $lang->bug->title;
$config->execution->importBug->dtable->fieldList['title']['type']        = 'shortNestedTitle';
$config->execution->importBug->dtable->fieldList['title']['sortType']    = false;
$config->execution->importBug->dtable->fieldList['title']['data-toggle'] = 'modal';
$config->execution->importBug->dtable->fieldList['title']['link']        = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}');

$config->execution->importBug->dtable->fieldList['status']['title']     = $lang->bug->status;
$config->execution->importBug->dtable->fieldList['status']['type']      = 'status';
$config->execution->importBug->dtable->fieldList['status']['sortType']  = false;
$config->execution->importBug->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;

$config->execution->importBug->dtable->fieldList['taskPri']['title']        = $lang->bug->pri;
$config->execution->importBug->dtable->fieldList['taskPri']['name']         = 'pri';
$config->execution->importBug->dtable->fieldList['taskPri']['type']         = 'html';
$config->execution->importBug->dtable->fieldList['taskPri']['width']        = 68;
$config->execution->importBug->dtable->fieldList['taskPri']['sortType']     = false;
$config->execution->importBug->dtable->fieldList['taskPri']['type']         = 'control';
$config->execution->importBug->dtable->fieldList['taskPri']['control']      = 'picker';
$config->execution->importBug->dtable->fieldList['taskPri']['controlItems'] = $lang->task->priList;

$config->execution->importBug->dtable->fieldList['assignedTo']['title']    = $lang->bug->assignedTo;
$config->execution->importBug->dtable->fieldList['assignedTo']['width']    = 108;
$config->execution->importBug->dtable->fieldList['assignedTo']['sortType'] = false;
$config->execution->importBug->dtable->fieldList['assignedTo']['type']     = 'control';
$config->execution->importBug->dtable->fieldList['assignedTo']['control']  = 'picker';

$config->execution->importBug->dtable->fieldList['estimate']['title']    = $lang->task->estimate;
$config->execution->importBug->dtable->fieldList['estimate']['type']     = 'number';
$config->execution->importBug->dtable->fieldList['estimate']['sortType'] = false;
$config->execution->importBug->dtable->fieldList['estimate']['type']     = 'control';
$config->execution->importBug->dtable->fieldList['estimate']['control']  = 'input';

$config->execution->importBug->dtable->fieldList['estStarted']['title']    = $lang->task->estStarted;
$config->execution->importBug->dtable->fieldList['estStarted']['type']     = 'datetime';
$config->execution->importBug->dtable->fieldList['estStarted']['width']    = 100;
$config->execution->importBug->dtable->fieldList['estStarted']['sortType'] = false;
$config->execution->importBug->dtable->fieldList['estStarted']['type']     = 'control';
$config->execution->importBug->dtable->fieldList['estStarted']['control']  = 'datePicker';

$config->execution->importBug->dtable->fieldList['deadline']['title']    = $lang->task->deadline;
$config->execution->importBug->dtable->fieldList['deadline']['type']     = 'datetime';
$config->execution->importBug->dtable->fieldList['deadline']['width']    = 100;
$config->execution->importBug->dtable->fieldList['deadline']['sortType'] = false;
$config->execution->importBug->dtable->fieldList['deadline']['type']     = 'control';
$config->execution->importBug->dtable->fieldList['deadline']['control']  = 'datePicker';

$app->loadLang('story');
$config->execution->linkStory = new stdclass();
$config->execution->linkStory->dtable = new stdclass();
$config->execution->linkStory->dtable->fieldList['id']['title']    = $lang->idAB;
$config->execution->linkStory->dtable->fieldList['id']['type']     = 'checkID';
$config->execution->linkStory->dtable->fieldList['id']['sortType'] = true;
$config->execution->linkStory->dtable->fieldList['id']['fixed']    = 'left';
$config->execution->linkStory->dtable->fieldList['id']['group']    = 1;

$config->execution->linkStory->dtable->fieldList['title']['title']       = $lang->story->title;
$config->execution->linkStory->dtable->fieldList['title']['type']        = 'title';
$config->execution->linkStory->dtable->fieldList['title']['link']        = helper::createLink('story', 'view', 'storyID={id}');
$config->execution->linkStory->dtable->fieldList['title']['sortType']    = true;
$config->execution->linkStory->dtable->fieldList['title']['fixed']       = 'left';
$config->execution->linkStory->dtable->fieldList['title']['group']       = 2;
$config->execution->linkStory->dtable->fieldList['title']['data-app']    = $app->tab;
$config->execution->linkStory->dtable->fieldList['title']['data-toggle'] = 'modal';
$config->execution->linkStory->dtable->fieldList['title']['data-size']   = 'lg';

$config->execution->linkStory->dtable->fieldList['pri']['title']    = $lang->story->pri;
$config->execution->linkStory->dtable->fieldList['pri']['type']     = 'pri';
$config->execution->linkStory->dtable->fieldList['pri']['sortType'] = true;
$config->execution->linkStory->dtable->fieldList['pri']['fixed']    = 'left';
$config->execution->linkStory->dtable->fieldList['pri']['group']    = 3;

$config->execution->linkStory->dtable->fieldList['stage']['title']     = $lang->story->stageAB;
$config->execution->linkStory->dtable->fieldList['stage']['type']      = 'status';
$config->execution->linkStory->dtable->fieldList['stage']['statusMap'] = $lang->story->stageList;
$config->execution->linkStory->dtable->fieldList['stage']['sortType']  = true;
$config->execution->linkStory->dtable->fieldList['stage']['group']     = 4;

$config->execution->linkStory->dtable->fieldList['product']['title']    = $lang->story->product;
$config->execution->linkStory->dtable->fieldList['product']['type']     = 'text';
$config->execution->linkStory->dtable->fieldList['product']['link']     = helper::createLink('product', 'browse', 'productID={product}&branch={branch}');
$config->execution->linkStory->dtable->fieldList['product']['sortType'] = true;
$config->execution->linkStory->dtable->fieldList['product']['group']    = 5;

$config->execution->linkStory->dtable->fieldList['module']['title']    = $lang->story->module;
$config->execution->linkStory->dtable->fieldList['module']['type']     = 'text';
$config->execution->linkStory->dtable->fieldList['module']['sortType'] = true;
$config->execution->linkStory->dtable->fieldList['module']['group']    = 6;

$config->execution->linkStory->dtable->fieldList['planTitle']['title']    = $lang->story->plan;
$config->execution->linkStory->dtable->fieldList['planTitle']['type']     = 'text';
$config->execution->linkStory->dtable->fieldList['planTitle']['sortType'] = false;
$config->execution->linkStory->dtable->fieldList['planTitle']['group']    = 7;

$config->execution->linkStory->dtable->fieldList['branch']['title']    = '';
$config->execution->linkStory->dtable->fieldList['branch']['type']     = 'text';
$config->execution->linkStory->dtable->fieldList['branch']['sortType'] = true;
$config->execution->linkStory->dtable->fieldList['branch']['group']    = 8;

$config->execution->linkStory->dtable->fieldList['openedBy']['title']    = $lang->story->openedBy;
$config->execution->linkStory->dtable->fieldList['openedBy']['type']     = 'user';
$config->execution->linkStory->dtable->fieldList['openedBy']['sortType'] = true;
$config->execution->linkStory->dtable->fieldList['openedBy']['group']    = 9;

$config->execution->linkStory->dtable->fieldList['estimate']['title']    = $lang->story->estimateAB;
$config->execution->linkStory->dtable->fieldList['estimate']['type']     = 'number';
$config->execution->linkStory->dtable->fieldList['estimate']['sortType'] = true;
$config->execution->linkStory->dtable->fieldList['estimate']['group']    = 10;

$app->loadLang('testcase');
$app->loadLang('testtask');
$app->loadModuleConfig('testtask');

$config->execution->testtask = new stdclass();
$config->execution->testtask->dtable = new stdclass();

$config->execution->testtask->dtable->fieldList['product']['name']  = 'productName';
$config->execution->testtask->dtable->fieldList['product']['title'] = $lang->testtask->product;
$config->execution->testtask->dtable->fieldList['product']['type']  = 'text';
$config->execution->testtask->dtable->fieldList['product']['group'] = '1';

$config->execution->testtask->dtable->fieldList['id']['name']     = 'id';
$config->execution->testtask->dtable->fieldList['id']['title']    = $lang->idAB;
$config->execution->testtask->dtable->fieldList['id']['type']     = 'checkID';
$config->execution->testtask->dtable->fieldList['id']['checkbox'] = true;
$config->execution->testtask->dtable->fieldList['id']['group']    = '2';
$config->execution->testtask->dtable->fieldList['id']['fixed']    = false;

$config->execution->testtask->dtable->fieldList['title']['name']     = 'name';
$config->execution->testtask->dtable->fieldList['title']['title']    = $lang->testtask->name;
$config->execution->testtask->dtable->fieldList['title']['type']     = 'title';
$config->execution->testtask->dtable->fieldList['title']['link']     = array('module' => 'testtask', 'method' => 'cases', 'params' => 'taskID={id}');
$config->execution->testtask->dtable->fieldList['title']['group']    = '2';
$config->execution->testtask->dtable->fieldList['title']['fixed']    = false;
$config->execution->testtask->dtable->fieldList['title']['width']    = '356';
$config->execution->testtask->dtable->fieldList['title']['data-app'] = 'execution';

$config->execution->testtask->dtable->fieldList['build']['name']  = 'buildName';
$config->execution->testtask->dtable->fieldList['build']['title'] = $lang->testtask->build;
$config->execution->testtask->dtable->fieldList['build']['type']  = 'text';
$config->execution->testtask->dtable->fieldList['build']['link']  = array('module' => 'build', 'method' => 'view', 'params' => 'buildID={build}');
$config->execution->testtask->dtable->fieldList['build']['group'] = 'text';
$config->execution->testtask->dtable->fieldList['build']['group'] = '3';

$config->execution->testtask->dtable->fieldList['status']['name']      = 'status';
$config->execution->testtask->dtable->fieldList['status']['title']     = $lang->testtask->status;
$config->execution->testtask->dtable->fieldList['status']['type']      = 'status';
$config->execution->testtask->dtable->fieldList['status']['statusMap'] = $lang->testtask->statusList;
$config->execution->testtask->dtable->fieldList['status']['group']     = '4';

$config->execution->testtask->dtable->fieldList['owner']['name']    = 'owner';
$config->execution->testtask->dtable->fieldList['owner']['title']   = $lang->testtask->owner;
$config->execution->testtask->dtable->fieldList['owner']['type']    = 'user';
$config->execution->testtask->dtable->fieldList['owner']['group']   = '4';

$config->execution->testtask->dtable->fieldList['begin']['name']  = 'begin';
$config->execution->testtask->dtable->fieldList['begin']['title'] = $lang->testtask->begin;
$config->execution->testtask->dtable->fieldList['begin']['type']  = 'date';
$config->execution->testtask->dtable->fieldList['begin']['group'] = '4';

$config->execution->testtask->dtable->fieldList['end']['name']  = 'end';
$config->execution->testtask->dtable->fieldList['end']['title'] = $lang->testtask->end;
$config->execution->testtask->dtable->fieldList['end']['type']  = 'date';
$config->execution->testtask->dtable->fieldList['end']['group'] = '4';

$config->execution->testtask->dtable->fieldList['actions']['name']     = 'actions';
$config->execution->testtask->dtable->fieldList['actions']['title']    = $lang->actions;
$config->execution->testtask->dtable->fieldList['actions']['type']     = 'actions';
$config->execution->testtask->dtable->fieldList['actions']['sortType'] = false;
$config->execution->testtask->dtable->fieldList['actions']['fixed']    = false;
$config->execution->testtask->dtable->fieldList['actions']['list']     = $config->testtask->actionList;
$config->execution->testtask->dtable->fieldList['actions']['menu']     = array('cases', 'linkCase', 'report', 'view', 'edit', 'delete');
