<?php
global $lang, $app;
if(empty($app->rawModule)) $app->rawModule = 'mr';

$config->mr->dtable = new stdclass();

$config->mr->dtable->fieldList['id']['name']  = 'id';
$config->mr->dtable->fieldList['id']['title'] = $lang->idAB;
$config->mr->dtable->fieldList['id']['type']  = 'id';

$config->mr->dtable->fieldList['title']['name']     = 'title';
$config->mr->dtable->fieldList['title']['title']    = $lang->mr->title;
$config->mr->dtable->fieldList['title']['type']     = 'title';
$config->mr->dtable->fieldList['title']['data-app'] = $app->tab;
$config->mr->dtable->fieldList['title']['link']     = helper::createLink($app->rawModule, 'view', "MRID={id}");
$config->mr->dtable->fieldList['title']['sortType'] = true;
$config->mr->dtable->fieldList['title']['width']    = 0.3;

$config->mr->dtable->fieldList['sourceBranch']['name']  = 'sourceBranch';
$config->mr->dtable->fieldList['sourceBranch']['title'] = $lang->mr->sourceBranch;
$config->mr->dtable->fieldList['sourceBranch']['type']  = 'text';

$config->mr->dtable->fieldList['targetBranch']['name']  = 'targetBranch';
$config->mr->dtable->fieldList['targetBranch']['title'] = $lang->mr->targetBranch;
$config->mr->dtable->fieldList['targetBranch']['type']  = 'text';

$config->mr->dtable->fieldList['mergeStatus']['name']      = 'mergeStatus';
$config->mr->dtable->fieldList['mergeStatus']['title']     = $lang->mr->mergeStatus;
$config->mr->dtable->fieldList['mergeStatus']['type']      = 'status';
$config->mr->dtable->fieldList['mergeStatus']['sortType']  = true;
$config->mr->dtable->fieldList['mergeStatus']['width']     = '120';
$config->mr->dtable->fieldList['mergeStatus']['statusMap'] = $lang->mr->statusList + $lang->mr->mergeStatusList;

$config->mr->dtable->fieldList['approvalStatus']['name']     = 'approvalStatus';
$config->mr->dtable->fieldList['approvalStatus']['title']    = $lang->mr->approvalStatus;
$config->mr->dtable->fieldList['approvalStatus']['type']     = 'type';
$config->mr->dtable->fieldList['approvalStatus']['sortType'] = true;

$config->mr->dtable->fieldList['assignee']['name']     = 'assignee';
$config->mr->dtable->fieldList['assignee']['title']    = $lang->mr->reviewer;
$config->mr->dtable->fieldList['assignee']['type']     = 'user';
$config->mr->dtable->fieldList['assignee']['sortType'] = true;

$config->mr->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->mr->dtable->fieldList['createdBy']['title']    = $lang->mr->author;
$config->mr->dtable->fieldList['createdBy']['type']     = 'user';
$config->mr->dtable->fieldList['createdBy']['sortType'] = true;

$config->mr->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->mr->dtable->fieldList['createdDate']['title']    = $lang->mr->createdDate;
$config->mr->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->mr->dtable->fieldList['createdDate']['sortType'] = true;

$config->mr->dtable->fieldList['actions']['name']  = 'actions';
$config->mr->dtable->fieldList['actions']['title'] = $lang->actions;
$config->mr->dtable->fieldList['actions']['type']  = 'actions';
$config->mr->dtable->fieldList['actions']['menu']  = array('edit', 'delete');
$config->mr->dtable->fieldList['actions']['list']  = $config->mr->actionList;

$config->mr->taskDtable = new stdclass();
$config->mr->taskDtable->fieldList['id']['title']    = $lang->idAB;
$config->mr->taskDtable->fieldList['id']['type']     = 'checkID';
$config->mr->taskDtable->fieldList['id']['sortType'] = 'desc';
$config->mr->taskDtable->fieldList['id']['checkbox'] = false;
$config->mr->taskDtable->fieldList['id']['required'] = true;

$config->mr->taskDtable->fieldList['name']['fixed']        = 'left';
$config->mr->taskDtable->fieldList['name']['title']        = $lang->task->name;
$config->mr->taskDtable->fieldList['name']['flex']         = '';
$config->mr->taskDtable->fieldList['name']['type']         = 'nestedTitle';
$config->mr->taskDtable->fieldList['name']['nestedToggle'] = true;
$config->mr->taskDtable->fieldList['name']['sortType']     = true;
$config->mr->taskDtable->fieldList['name']['data-toggle']  = 'modal';
$config->mr->taskDtable->fieldList['name']['data-size']    = 'lg';
$config->mr->taskDtable->fieldList['name']['link']         = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={id}');
$config->mr->taskDtable->fieldList['name']['required']     = true;

$config->mr->taskDtable->fieldList['pri']['title']    = $lang->priAB;
$config->mr->taskDtable->fieldList['pri']['type']     = 'pri';
$config->mr->taskDtable->fieldList['pri']['sortType'] = true;
$config->mr->taskDtable->fieldList['pri']['show']     = true;

$config->mr->taskDtable->fieldList['assignedTo']['type']        = 'user';
$config->mr->taskDtable->fieldList['assignedTo']['title']       = $lang->task->assignedTo;
$config->mr->taskDtable->fieldList['assignedTo']['currentUser'] = '';
$config->mr->taskDtable->fieldList['assignedTo']['sortType']    = true;
$config->mr->taskDtable->fieldList['assignedTo']['show']        = true;

$config->mr->taskDtable->fieldList['finishedBy']['title']    = $lang->task->finishedByAB;
$config->mr->taskDtable->fieldList['finishedBy']['type']     = 'user';
$config->mr->taskDtable->fieldList['finishedBy']['sortType'] = true;
$config->mr->taskDtable->fieldList['finishedBy']['show']     = true;

$config->mr->taskDtable->fieldList['status']['title']     = $lang->statusAB;
$config->mr->taskDtable->fieldList['status']['type']      = 'status';
$config->mr->taskDtable->fieldList['status']['statusMap'] = $lang->task->statusList;
$config->mr->taskDtable->fieldList['status']['sortType']  = true;
$config->mr->taskDtable->fieldList['status']['show']      = true;

$config->mr->commitLogs = new stdclass();
$config->mr->commitLogs->dtable = new stdclass();

$app->loadLang('repo');

$config->mr->commitLogs->dtable->fieldList['id']['name']     = 'id';
$config->mr->commitLogs->dtable->fieldList['id']['title']    = $lang->repo->revisions;
$config->mr->commitLogs->dtable->fieldList['id']['type']     = 'text';
$config->mr->commitLogs->dtable->fieldList['id']['data-app'] = $app->tab;
$config->mr->commitLogs->dtable->fieldList['id']['link']     = helper::createLink('repo', 'diff', "repoID={repoID}&objectID=0&entry=&oldrevision=^&newRevision={id}");
$config->mr->commitLogs->dtable->fieldList['id']['minWidth'] = 40;

$config->mr->commitLogs->dtable->fieldList['committed_date']['name']     = 'committed_date';
$config->mr->commitLogs->dtable->fieldList['committed_date']['title']    = $lang->repo->time;
$config->mr->commitLogs->dtable->fieldList['committed_date']['type']     = 'datetime';
$config->mr->commitLogs->dtable->fieldList['committed_date']['sortType'] = false;

$config->mr->commitLogs->dtable->fieldList['committer_name']['name']  = 'committer_name';
$config->mr->commitLogs->dtable->fieldList['committer_name']['title'] = $lang->repo->committer;
$config->mr->commitLogs->dtable->fieldList['committer_name']['type']  = 'text';
$config->mr->commitLogs->dtable->fieldList['committer_name']['hint']  = '{committer_email}';

$config->mr->commitLogs->dtable->fieldList['title']['name']     = 'title';
$config->mr->commitLogs->dtable->fieldList['title']['title']    = $lang->repo->comment;
$config->mr->commitLogs->dtable->fieldList['title']['type']     = 'text';
$config->mr->commitLogs->dtable->fieldList['title']['minWidth'] = 342;
$config->mr->commitLogs->dtable->fieldList['title']['hint']     = '{message}';
