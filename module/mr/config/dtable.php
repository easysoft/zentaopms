<?php
global $lang;

$config->mr->dtable = new stdclass();

$config->mr->dtable->fieldList['id']['name']  = 'id';
$config->mr->dtable->fieldList['id']['title'] = $lang->idAB;
$config->mr->dtable->fieldList['id']['type']  = 'id';

$config->mr->dtable->fieldList['title']['name']     = 'title';
$config->mr->dtable->fieldList['title']['title']    = $lang->mr->title;
$config->mr->dtable->fieldList['title']['type']     = 'text';
$config->mr->dtable->fieldList['title']['link']     = helper::createLink('mr', 'view', "MRID={id}");
$config->mr->dtable->fieldList['title']['sortType'] = true;

$config->mr->dtable->fieldList['sourceBranch']['name']     = 'sourceProject';
$config->mr->dtable->fieldList['sourceBranch']['title']    = $lang->mr->sourceBranch;
$config->mr->dtable->fieldList['sourceBranch']['type']     = 'text';
$config->mr->dtable->fieldList['sourceBranch']['minWidth'] = '200';

$config->mr->dtable->fieldList['targetBranch']['name']     = 'targetProject';
$config->mr->dtable->fieldList['targetBranch']['title']    = $lang->mr->targetBranch;
$config->mr->dtable->fieldList['targetBranch']['type']     = 'text';
$config->mr->dtable->fieldList['targetBranch']['minWidth'] = '200';

$config->mr->dtable->fieldList['mergeStatus']['name']     = 'mergeStatus';
$config->mr->dtable->fieldList['mergeStatus']['title']    = $lang->mr->mergeStatus;
$config->mr->dtable->fieldList['mergeStatus']['type']     = 'text';
$config->mr->dtable->fieldList['mergeStatus']['sortType'] = true;

$config->mr->dtable->fieldList['approvalStatus']['name']     = 'approvalStatus';
$config->mr->dtable->fieldList['approvalStatus']['title']    = $lang->mr->approvalStatus;
$config->mr->dtable->fieldList['approvalStatus']['type']     = 'text';
$config->mr->dtable->fieldList['approvalStatus']['sortType'] = true;

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
$config->mr->dtable->fieldList['actions']['menu']  = array('view', 'edit', 'diff', 'link', 'delete');
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
$config->mr->taskDtable->fieldList['name']['link']         = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={id}', 'target' => '_blank');
$config->mr->taskDtable->fieldList['name']['required']     = true;

$config->mr->taskDtable->fieldList['pri']['title']    = $lang->priAB;
$config->mr->taskDtable->fieldList['pri']['type']     = 'pri';
$config->mr->taskDtable->fieldList['pri']['sortType'] = true;
$config->mr->taskDtable->fieldList['pri']['show']     = true;

$config->mr->taskDtable->fieldList['assignedTo']['type']        = 'desc';
$config->mr->taskDtable->fieldList['assignedTo']['title']       = $lang->task->assignedTo;
$config->mr->taskDtable->fieldList['assignedTo']['currentUser'] = $app->user->account;
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
