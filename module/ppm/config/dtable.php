<?php
global $lang, $app;
if(empty($app->rawModule)) $app->rawModule = 'mr';

$config->ppm->dtable = new stdclass();

$config->ppm->dtable->fieldList['id']['name']  = 'id';
$config->ppm->dtable->fieldList['id']['title'] = $lang->idAB;
$config->ppm->dtable->fieldList['id']['type']  = 'id';

$config->ppm->dtable->fieldList['title']['name']     = 'title';
$config->ppm->dtable->fieldList['title']['title']    = $lang->ppm->title;
$config->ppm->dtable->fieldList['title']['type']     = 'title';
$config->ppm->dtable->fieldList['title']['data-app'] = $app->tab;
$config->ppm->dtable->fieldList['title']['link']     = helper::createLink($app->rawModule, 'view', "id={id}");
$config->ppm->dtable->fieldList['title']['sortType'] = true;
$config->ppm->dtable->fieldList['title']['width']    = 0.3;
$config->ppm->dtable->fieldList['title']['order']    = 5;

$config->ppm->dtable->fieldList['sourceBranch']['name']  = 'sourceBranch';
$config->ppm->dtable->fieldList['sourceBranch']['title'] = $lang->ppm->sourceBranch;
$config->ppm->dtable->fieldList['sourceBranch']['type']  = 'text';
$config->ppm->dtable->fieldList['sourceBranch']['order'] = 10;

$config->ppm->dtable->fieldList['targetBranch']['name']  = 'targetBranch';
$config->ppm->dtable->fieldList['targetBranch']['title'] = $lang->ppm->targetBranch;
$config->ppm->dtable->fieldList['targetBranch']['type']  = 'text';
$config->ppm->dtable->fieldList['targetBranch']['order'] = 15;

#$config->ppm->dtable->fieldList['mergeStatus']['name']      = 'mergeStatus';
#$config->ppm->dtable->fieldList['mergeStatus']['title']     = $lang->ppm->mergeStatus;
#$config->ppm->dtable->fieldList['mergeStatus']['type']      = 'status';
#$config->ppm->dtable->fieldList['mergeStatus']['sortType']  = true;
#$config->ppm->dtable->fieldList['mergeStatus']['width']     = '120';
#$config->ppm->dtable->fieldList['mergeStatus']['statusMap'] = $lang->ppm->statusList + $lang->ppm->mergeStatusList;

$config->ppm->dtable->fieldList['status']['name']     = 'status';
$config->ppm->dtable->fieldList['status']['title']    = $lang->ppm->status;
$config->ppm->dtable->fieldList['status']['type']     = 'type';
$config->ppm->dtable->fieldList['status']['sortType'] = true;
$config->ppm->dtable->fieldList['status']['map']      = $lang->ppm->statusList;
$config->ppm->dtable->fieldList['status']['order']    = 25;

#$config->ppm->dtable->fieldList['assignee']['name']     = 'assignee';
#$config->ppm->dtable->fieldList['assignee']['title']    = $lang->ppm->reviewer;
#$config->ppm->dtable->fieldList['assignee']['type']     = 'user';
#$config->ppm->dtable->fieldList['assignee']['sortType'] = true;

$config->ppm->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->ppm->dtable->fieldList['createdBy']['title']    = $lang->ppm->author;
$config->ppm->dtable->fieldList['createdBy']['type']     = 'user';
$config->ppm->dtable->fieldList['createdBy']['sortType'] = true;
$config->ppm->dtable->fieldList['createdBy']['order']    = 30;

$config->ppm->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->ppm->dtable->fieldList['createdDate']['title']    = $lang->ppm->createdDate;
$config->ppm->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->ppm->dtable->fieldList['createdDate']['sortType'] = true;
$config->ppm->dtable->fieldList['createdDate']['order']    = 35;

$config->ppm->dtable->fieldList['actions']['name']  = 'actions';
$config->ppm->dtable->fieldList['actions']['title'] = $lang->actions;
$config->ppm->dtable->fieldList['actions']['type']  = 'actions';
$config->ppm->dtable->fieldList['actions']['menu']  = array('edit', 'delete');
$config->ppm->dtable->fieldList['actions']['list']  = $config->ppm->actionList;
$config->ppm->dtable->fieldList['actions']['width'] = '80';

$config->ppm->taskDtable = new stdclass();
$config->ppm->taskDtable->fieldList['id']['title']    = $lang->idAB;
$config->ppm->taskDtable->fieldList['id']['type']     = 'checkID';
$config->ppm->taskDtable->fieldList['id']['sortType'] = 'desc';
$config->ppm->taskDtable->fieldList['id']['checkbox'] = false;
$config->ppm->taskDtable->fieldList['id']['required'] = true;

$config->ppm->taskDtable->fieldList['name']['fixed']        = 'left';
$config->ppm->taskDtable->fieldList['name']['title']        = $lang->task->name;
$config->ppm->taskDtable->fieldList['name']['flex']         = '';
$config->ppm->taskDtable->fieldList['name']['type']         = 'nestedTitle';
$config->ppm->taskDtable->fieldList['name']['nestedToggle'] = true;
$config->ppm->taskDtable->fieldList['name']['sortType']     = true;
$config->ppm->taskDtable->fieldList['name']['data-toggle']  = 'modal';
$config->ppm->taskDtable->fieldList['name']['data-size']    = 'lg';
$config->ppm->taskDtable->fieldList['name']['link']         = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={id}');
$config->ppm->taskDtable->fieldList['name']['required']     = true;

$config->ppm->taskDtable->fieldList['pri']['title']    = $lang->priAB;
$config->ppm->taskDtable->fieldList['pri']['type']     = 'pri';
$config->ppm->taskDtable->fieldList['pri']['sortType'] = true;
$config->ppm->taskDtable->fieldList['pri']['show']     = true;

$config->ppm->taskDtable->fieldList['assignedTo']['type']        = 'user';
$config->ppm->taskDtable->fieldList['assignedTo']['title']       = $lang->task->assignedTo;
$config->ppm->taskDtable->fieldList['assignedTo']['currentUser'] = '';
$config->ppm->taskDtable->fieldList['assignedTo']['sortType']    = true;
$config->ppm->taskDtable->fieldList['assignedTo']['show']        = true;

$config->ppm->taskDtable->fieldList['finishedBy']['title']    = $lang->task->finishedByAB;
$config->ppm->taskDtable->fieldList['finishedBy']['type']     = 'user';
$config->ppm->taskDtable->fieldList['finishedBy']['sortType'] = true;
$config->ppm->taskDtable->fieldList['finishedBy']['show']     = true;

$config->ppm->taskDtable->fieldList['status']['title']     = $lang->statusAB;
$config->ppm->taskDtable->fieldList['status']['type']      = 'status';
$config->ppm->taskDtable->fieldList['status']['statusMap'] = $lang->task->statusList;
$config->ppm->taskDtable->fieldList['status']['sortType']  = true;
$config->ppm->taskDtable->fieldList['status']['show']      = true;

$app->loadLang('bug');
$config->ppm->bug = new stdclass();
$config->ppm->bug->dtable = new stdclass();
$config->ppm->bug->dtable->fieldList = array();
$config->ppm->bug->dtable->fieldList['id']['name']     = 'id';
$config->ppm->bug->dtable->fieldList['id']['title']    = $lang->idAB;
$config->ppm->bug->dtable->fieldList['id']['type']     = 'id';
$config->ppm->bug->dtable->fieldList['id']['sortType'] = false;

$config->ppm->bug->dtable->fieldList['title']['name']     = 'title';
$config->ppm->bug->dtable->fieldList['title']['title']    = $lang->ppm->bug->title;
$config->ppm->bug->dtable->fieldList['title']['type']     = 'shorttitle';
$config->ppm->bug->dtable->fieldList['title']['link']     = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}');
$config->ppm->bug->dtable->fieldList['title']['data-app'] = 'devops';
$config->ppm->bug->dtable->fieldList['title']['fixed']    = false;

$config->ppm->bug->dtable->fieldList['source']['name']  = 'source';
$config->ppm->bug->dtable->fieldList['source']['title'] = $lang->ppm->bug->source;
$config->ppm->bug->dtable->fieldList['source']['map']   = $lang->ppm->issueSourceList;

$config->ppm->bug->dtable->fieldList['type']['name']  = 'type';
$config->ppm->bug->dtable->fieldList['type']['title'] = $lang->ppm->bug->type;
$config->ppm->bug->dtable->fieldList['type']['map']   = $lang->bug->typeList;

$config->ppm->bug->dtable->fieldList['file']['name']  = 'file';
$config->ppm->bug->dtable->fieldList['file']['title'] = $lang->ppm->bug->file;
$config->ppm->bug->dtable->fieldList['file']['width'] = 80;

$config->ppm->bug->dtable->fieldList['severity']['name']     = 'severity';
$config->ppm->bug->dtable->fieldList['severity']['title']    = $lang->ppm->bug->severity;
$config->ppm->bug->dtable->fieldList['severity']['type']     = 'severity';
$config->ppm->bug->dtable->fieldList['severity']['sortType'] = false;

$config->ppm->bug->dtable->fieldList['status']['name']      = 'status';
$config->ppm->bug->dtable->fieldList['status']['title']     = $lang->ppm->bug->status;
$config->ppm->bug->dtable->fieldList['status']['type']      = 'status';
$config->ppm->bug->dtable->fieldList['status']['sortType']  = false;
$config->ppm->bug->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;

$app->loadLang('repo');
$config->ppm->commitLogs = new stdclass();
$config->ppm->commitLogs->dtable = new stdclass();
$config->ppm->commitLogs->dtable->fieldList['id']['name']     = 'id';
$config->ppm->commitLogs->dtable->fieldList['id']['title']    = $lang->repo->revisions;
$config->ppm->commitLogs->dtable->fieldList['id']['type']     = 'text';
$config->ppm->commitLogs->dtable->fieldList['id']['data-app'] = $app->tab;
$config->ppm->commitLogs->dtable->fieldList['id']['link']     = helper::createLink('repo', 'diff', "repoID={repoID}&objectID=0&entry=&oldrevision=^&newRevision={sha}");
$config->ppm->commitLogs->dtable->fieldList['id']['minWidth'] = 40;

$config->ppm->commitLogs->dtable->fieldList['authorName']['name']     = 'authorName';
$config->ppm->commitLogs->dtable->fieldList['authorName']['title']    = $lang->repo->committer;
$config->ppm->commitLogs->dtable->fieldList['authorName']['type']     = 'user';
$config->ppm->commitLogs->dtable->fieldList['authorName']['sortType'] = false;

$config->ppm->commitLogs->dtable->fieldList['title']['name']     = 'title';
$config->ppm->commitLogs->dtable->fieldList['title']['title']    = $lang->repo->comment;
$config->ppm->commitLogs->dtable->fieldList['title']['type']     = 'text';
$config->ppm->commitLogs->dtable->fieldList['title']['minWidth'] = 342;
$config->ppm->commitLogs->dtable->fieldList['title']['hint']     = '{message}';

$config->ppm->commitLogs->dtable->fieldList['committedDate']['name']       = 'committedDate';
$config->ppm->commitLogs->dtable->fieldList['committedDate']['title']      = $lang->repo->time;
$config->ppm->commitLogs->dtable->fieldList['committedDate']['type']       = 'datetime';
$config->ppm->commitLogs->dtable->fieldList['committedDate']['formatDate'] = 'YYYY-MM-dd hh:mm';
$config->ppm->commitLogs->dtable->fieldList['committedDate']['sortType']   = false;

$config->ppm->createCheck = new stdclass();
$config->ppm->createCheck->commit = new stdclass();
$config->ppm->createCheck->commit->dtable = new stdclass();
$config->ppm->createCheck->commit->dtable->fieldList['id']['name']     = 'id';
$config->ppm->createCheck->commit->dtable->fieldList['id']['title']    = $lang->repo->revisions;
$config->ppm->createCheck->commit->dtable->fieldList['id']['type']     = 'text';
$config->ppm->createCheck->commit->dtable->fieldList['id']['data-app'] = $app->tab;
$config->ppm->createCheck->commit->dtable->fieldList['id']['link']     = helper::createLink('repo', 'diff', "repoID={repoID}&objectID=0&entry=&oldrevision=^&newRevision={id}");
$config->ppm->createCheck->commit->dtable->fieldList['id']['minWidth'] = 40;

$config->ppm->createCheck->commit->dtable->fieldList['authorName']['name']  = 'authorName';
$config->ppm->createCheck->commit->dtable->fieldList['authorName']['title'] = $lang->repo->committer;
$config->ppm->createCheck->commit->dtable->fieldList['authorName']['type']  = 'user';
$config->ppm->createCheck->commit->dtable->fieldList['authorName']['hint']  = true;

$config->ppm->createCheck->commit->dtable->fieldList['title']['name']     = 'title';
$config->ppm->createCheck->commit->dtable->fieldList['title']['title']    = $lang->repo->comment;
$config->ppm->createCheck->commit->dtable->fieldList['title']['type']     = 'text';
$config->ppm->createCheck->commit->dtable->fieldList['title']['minWidth'] = 342;
$config->ppm->createCheck->commit->dtable->fieldList['title']['hint']     = '{message}';

$config->ppm->createCheck->commit->dtable->fieldList['committedDate']['name']       = 'committedDate';
$config->ppm->createCheck->commit->dtable->fieldList['committedDate']['title']      = $lang->repo->time;
$config->ppm->createCheck->commit->dtable->fieldList['committedDate']['type']       = 'datetime';
$config->ppm->createCheck->commit->dtable->fieldList['committedDate']['sortType']   = false;
$config->ppm->createCheck->commit->dtable->fieldList['committedDate']['formatDate'] = 'YYYY-MM-dd hh:mm';

$config->ppm->createCheck->linkObject = new stdclass();
$config->ppm->createCheck->linkObject->dtable = new stdclass();
$config->ppm->createCheck->linkObject->dtable->fieldList['type']['name']     = 'type';
$config->ppm->createCheck->linkObject->dtable->fieldList['type']['title']    = $lang->ppm->object;
$config->ppm->createCheck->linkObject->dtable->fieldList['type']['data-app'] = $app->tab;
$config->ppm->createCheck->linkObject->dtable->fieldList['type']['width']    = 100;
$config->ppm->createCheck->linkObject->dtable->fieldList['type']['map']      = array('story' => $lang->story->common, 'task' => $lang->task->common, 'bug' => $lang->bug->common);

$config->ppm->createCheck->linkObject->dtable->fieldList['id']['name']   = 'id';
$config->ppm->createCheck->linkObject->dtable->fieldList['id']['title']  = $lang->repo->id;
$config->ppm->createCheck->linkObject->dtable->fieldList['id']['width']  = 60;
$config->ppm->createCheck->linkObject->dtable->fieldList['id']['link']   = array('url' => helper::createLink('{type}', 'view', "id={id}"), 'target' => '_blank');

$config->ppm->createCheck->linkObject->dtable->fieldList['title']['name']  = 'title';
$config->ppm->createCheck->linkObject->dtable->fieldList['title']['title'] = $lang->repo->title;
$config->ppm->createCheck->linkObject->dtable->fieldList['title']['type']  = 'text';
$config->ppm->createCheck->linkObject->dtable->fieldList['title']['hint']  = true;

$config->ppm->createCheck->linkObject->dtable->fieldList['status']['name']     = 'status';
$config->ppm->createCheck->linkObject->dtable->fieldList['status']['title']    = $lang->repo->status;
$config->ppm->createCheck->linkObject->dtable->fieldList['status']['type']     = 'status';
$config->ppm->createCheck->linkObject->dtable->fieldList['status']['sortType'] = false;
$config->ppm->createCheck->linkObject->dtable->fieldList['status']['width']    = 60;

$config->ppm->createCheck->linkObject->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->ppm->createCheck->linkObject->dtable->fieldList['createdBy']['title']    = $lang->repo->createdBy;
$config->ppm->createCheck->linkObject->dtable->fieldList['createdBy']['type']     = 'user';
$config->ppm->createCheck->linkObject->dtable->fieldList['createdBy']['sortType'] = false;
$config->ppm->createCheck->linkObject->dtable->fieldList['createdBy']['width']    = 100;

$config->ppm->createCheck->linkObject->dtable->fieldList['assignedTo']['name']     = 'assignedTo';
$config->ppm->createCheck->linkObject->dtable->fieldList['assignedTo']['title']    = $lang->repo->assignedTo;
$config->ppm->createCheck->linkObject->dtable->fieldList['assignedTo']['type']     = 'user';
$config->ppm->createCheck->linkObject->dtable->fieldList['assignedTo']['sortType'] = false;
$config->ppm->createCheck->linkObject->dtable->fieldList['assignedTo']['width']    = 100;

$config->ppm->createCheck->linkObject->dtable->fieldList['createdDate']['name']     = 'createdDate';
$config->ppm->createCheck->linkObject->dtable->fieldList['createdDate']['title']    = $lang->repo->time;
$config->ppm->createCheck->linkObject->dtable->fieldList['createdDate']['type']     = 'datetime';
$config->ppm->createCheck->linkObject->dtable->fieldList['createdDate']['sortType'] = false;
$config->ppm->createCheck->linkObject->dtable->fieldList['createdDate']['width']    = 120;

$config->ppm->createCheck->conflictFile = new stdclass();
$config->ppm->createCheck->conflictFile->dtable = new stdclass();
$config->ppm->createCheck->conflictFile->dtable->fieldList['file']['name']  = 'file';
$config->ppm->createCheck->conflictFile->dtable->fieldList['file']['title'] = $lang->ppm->filePath;
$config->ppm->createCheck->conflictFile->dtable->fieldList['file']['hint']  = true;
