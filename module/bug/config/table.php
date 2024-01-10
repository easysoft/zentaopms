<?php
global $lang, $app;
$config->bug->dtable = new stdclass();
$config->bug->dtable->defaultField = array('id', 'title', 'severity', 'pri', 'status', 'openedBy', 'openedDate', 'confirmed', 'assignedTo', 'resolution', 'actions');

$config->bug->dtable->fieldList['id']['title']    = $lang->idAB;
$config->bug->dtable->fieldList['id']['type']     = 'checkID';
$config->bug->dtable->fieldList['id']['fixed']    = 'left';
$config->bug->dtable->fieldList['id']['sortType'] = true;
$config->bug->dtable->fieldList['id']['required'] = true;
$config->bug->dtable->fieldList['id']['group']    = 1;

$config->bug->dtable->fieldList['product']['display']    = false;
$config->bug->dtable->fieldList['product']['dataSource'] = array('module' => 'product', 'method' => 'getPairs', 'params' => ['mode' => '', 'programID' => 0, 'append' => '', 'shadow' => 'all']);

$config->bug->dtable->fieldList['module']['display']    = false;
$config->bug->dtable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getOptionMenu', 'params' => ['rootID' => (int)'$productID', 'type' => 'bug', 'startModule' => 0, 'branch' => 'all']);

$config->bug->dtable->fieldList['title']['title']    = $lang->bug->title;
$config->bug->dtable->fieldList['title']['type']     = 'title';
$config->bug->dtable->fieldList['title']['fixed']    = 'left';
$config->bug->dtable->fieldList['title']['link']     = array('module' => 'bug', 'method' => 'view', 'params' => "bugID={id}");
$config->bug->dtable->fieldList['title']['required'] = true;
$config->bug->dtable->fieldList['title']['group']    = 1;
$config->bug->dtable->fieldList['title']['data-app'] = $app->tab;
$config->bug->dtable->fieldList['title']['sortType'] = true;

$config->bug->dtable->fieldList['severity']['title']    = $lang->bug->severity;
$config->bug->dtable->fieldList['severity']['type']     = 'severity';
$config->bug->dtable->fieldList['severity']['show']     = true;
$config->bug->dtable->fieldList['severity']['group']    = 2;
$config->bug->dtable->fieldList['severity']['sortType'] = true;

$config->bug->dtable->fieldList['pri']['title']    = $lang->bug->pri;
$config->bug->dtable->fieldList['pri']['type']     = 'pri';
$config->bug->dtable->fieldList['pri']['show']     = true;
$config->bug->dtable->fieldList['pri']['group']    = 2;
$config->bug->dtable->fieldList['pri']['sortType'] = true;

$config->bug->dtable->fieldList['status']['title']     = $lang->bug->abbr->status;
$config->bug->dtable->fieldList['status']['type']      = 'status';
$config->bug->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;
$config->bug->dtable->fieldList['status']['show']      = true;
$config->bug->dtable->fieldList['status']['group']     = 2;
$config->bug->dtable->fieldList['status']['sortType']  = true;

$config->bug->dtable->fieldList['type']['title']    = $lang->bug->type;
$config->bug->dtable->fieldList['type']['type']     = 'category';
$config->bug->dtable->fieldList['type']['map']      = $lang->bug->typeList;
$config->bug->dtable->fieldList['type']['flex']     = false;
$config->bug->dtable->fieldList['type']['group']    = 2;
$config->bug->dtable->fieldList['type']['sortType'] = true;

$config->bug->dtable->fieldList['branch']['title']      = $lang->bug->branch;
$config->bug->dtable->fieldList['branch']['type']       = 'text';
$config->bug->dtable->fieldList['branch']['group']      = 3;
$config->bug->dtable->fieldList['branch']['dataSource'] = array('module' => 'branch', 'method' => 'getPairs', 'params' => ['productID' => (int)'$productID']);
$config->bug->dtable->fieldList['branch']['sortType']   = true;

$config->bug->dtable->fieldList['project']['title']      = $lang->bug->project;
$config->bug->dtable->fieldList['project']['type']       = 'text';
$config->bug->dtable->fieldList['project']['group']      = 3;
$config->bug->dtable->fieldList['project']['dataSource'] = array('module' => 'project', 'method' => 'getPairs');
$config->bug->dtable->fieldList['project']['sortType']   = true;

$config->bug->dtable->fieldList['execution']['title']      = $lang->bug->execution;
$config->bug->dtable->fieldList['execution']['type']       = 'text';
$config->bug->dtable->fieldList['execution']['group']      = 3;
$config->bug->dtable->fieldList['execution']['dataSource'] = array('module' => 'execution', 'method' =>'getPairs', 'params' => ['projectID' => 0]);
$config->bug->dtable->fieldList['execution']['sortType']   = true;

$config->bug->dtable->fieldList['plan']['title']    = $lang->bug->plan;
$config->bug->dtable->fieldList['plan']['width']    = 120;
$config->bug->dtable->fieldList['plan']['group']    = 3;
$config->bug->dtable->fieldList['plan']['sortType'] = true;

$config->bug->dtable->fieldList['openedBuild']['title']      = $lang->bug->openedBuild;
$config->bug->dtable->fieldList['openedBuild']['type']       = 'text';
$config->bug->dtable->fieldList['openedBuild']['group']      = 3;
$config->bug->dtable->fieldList['openedBuild']['control']    = 'multiple';
$config->bug->dtable->fieldList['openedBuild']['dataSource'] = array('module' => 'build', 'method' =>'getBuildPairs', 'params' => ['productIdList' => (int)'$productID', 'branch' => '$branch', 'params' => 'noempty,noterminate,nodone,withbranch']);
$config->bug->dtable->fieldList['openedBuild']['sortType']   = true;

$config->bug->dtable->fieldList['openedBy']['title']    = $lang->bug->abbr->openedBy;
$config->bug->dtable->fieldList['openedBy']['type']     = 'user';
$config->bug->dtable->fieldList['openedBy']['show']     = true;
$config->bug->dtable->fieldList['openedBy']['group']    = 4;
$config->bug->dtable->fieldList['openedBy']['sortType'] = true;

$config->bug->dtable->fieldList['openedDate']['title']    = $lang->bug->abbr->openedDate;
$config->bug->dtable->fieldList['openedDate']['type']     = 'date';
$config->bug->dtable->fieldList['openedDate']['show']     = true;
$config->bug->dtable->fieldList['openedDate']['group'] = 4;
$config->bug->dtable->fieldList['openedDate']['sortType'] = 'date';

$config->bug->dtable->fieldList['confirmed']['title']    = $lang->bug->confirmed;
$config->bug->dtable->fieldList['confirmed']['type']     = 'category';
$config->bug->dtable->fieldList['confirmed']['map']      = $lang->bug->confirmedList;
$config->bug->dtable->fieldList['confirmed']['show']     = true;
$config->bug->dtable->fieldList['confirmed']['flex']     = false;
$config->bug->dtable->fieldList['confirmed']['group']    = 5;
$config->bug->dtable->fieldList['confirmed']['sortType'] = true;

$config->bug->dtable->fieldList['assignedTo']['title']      = $lang->bug->assignedTo;
$config->bug->dtable->fieldList['assignedTo']['type']       = 'assign';
$config->bug->dtable->fieldList['assignedTo']['assignLink'] = array('module' => 'bug', 'method' => 'assignTo', 'params' => 'bugID={id}');
$config->bug->dtable->fieldList['assignedTo']['show']       = true;
$config->bug->dtable->fieldList['assignedTo']['group']      = 5;
$config->bug->dtable->fieldList['assignedTo']['sortType']   = true;

$config->bug->dtable->fieldList['assignedDate']['title']    = $lang->bug->assignedDate;
$config->bug->dtable->fieldList['assignedDate']['type']     = 'date';
$config->bug->dtable->fieldList['assignedDate']['group']    = 5;
$config->bug->dtable->fieldList['assignedDate']['sortType'] = 'date';

$config->bug->dtable->fieldList['deadline']['title']    = $lang->bug->deadline;
$config->bug->dtable->fieldList['deadline']['type']     = 'date';
$config->bug->dtable->fieldList['deadline']['group']    = 5;
$config->bug->dtable->fieldList['deadline']['sortType'] = true;

$config->bug->dtable->fieldList['resolvedBy']['title']    = $lang->bug->resolvedBy;
$config->bug->dtable->fieldList['resolvedBy']['type']     = 'user';
$config->bug->dtable->fieldList['resolvedBy']['group']    = 6;
$config->bug->dtable->fieldList['resolvedBy']['sortType'] = true;

$config->bug->dtable->fieldList['resolution']['title']    = $lang->bug->resolution;
$config->bug->dtable->fieldList['resolution']['type']     = 'category';
$config->bug->dtable->fieldList['resolution']['map']      = $lang->bug->resolutionList;
$config->bug->dtable->fieldList['resolution']['show']     = true;
$config->bug->dtable->fieldList['resolution']['group']    = 6;
$config->bug->dtable->fieldList['resolution']['sortType'] = true;

$config->bug->dtable->fieldList['toTask']['title']    = $lang->bug->toTask;
$config->bug->dtable->fieldList['toTask']['type']     = 'text';
$config->bug->dtable->fieldList['toTask']['link']     = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={toTask}');
$config->bug->dtable->fieldList['toTask']['group']    = 6;
$config->bug->dtable->fieldList['toTask']['sortType'] = true;

$config->bug->dtable->fieldList['resolvedDate']['title']    = $lang->bug->abbr->resolvedDate;
$config->bug->dtable->fieldList['resolvedDate']['type']     = 'date';
$config->bug->dtable->fieldList['resolvedDate']['group']    = 6;
$config->bug->dtable->fieldList['resolvedDate']['sortType'] = 'date';

$config->bug->dtable->fieldList['resolvedBuild']['title']      = $lang->bug->resolvedBuild;
$config->bug->dtable->fieldList['resolvedBuild']['type']       = 'text';
$config->bug->dtable->fieldList['resolvedBuild']['group']      = 6;
$config->bug->dtable->fieldList['resolvedBuild']['dataSource'] = array('module' => 'bug', 'method' =>'getRelatedObjects', 'params' => 'resolvedBuild&id,name');
$config->bug->dtable->fieldList['resolvedBuild']['sortType']   = true;

$config->bug->dtable->fieldList['os']['title']    = $lang->bug->os;
$config->bug->dtable->fieldList['os']['type']     = 'category';
$config->bug->dtable->fieldList['os']['map']      = $lang->bug->osList;
$config->bug->dtable->fieldList['os']['group']    = 7;
$config->bug->dtable->fieldList['os']['control']  = 'multiple';
$config->bug->dtable->fieldList['os']['sortType'] = true;

$config->bug->dtable->fieldList['browser']['title']    = $lang->bug->browser;
$config->bug->dtable->fieldList['browser']['type']     = 'category';
$config->bug->dtable->fieldList['browser']['map']      = $lang->bug->browserList;
$config->bug->dtable->fieldList['browser']['group']    = 7;
$config->bug->dtable->fieldList['browser']['control']  = 'multiple';
$config->bug->dtable->fieldList['browser']['sortType'] = true;

$config->bug->dtable->fieldList['activatedCount']['title']    = $lang->bug->abbr->activatedCount;
$config->bug->dtable->fieldList['activatedCount']['type']     = 'count';
$config->bug->dtable->fieldList['activatedCount']['group']    = 8;
$config->bug->dtable->fieldList['activatedCount']['sortType'] = true;

$config->bug->dtable->fieldList['activatedDate']['title']    = $lang->bug->activatedDate;
$config->bug->dtable->fieldList['activatedDate']['type']     = 'date';
$config->bug->dtable->fieldList['activatedDate']['group']    = 8;
$config->bug->dtable->fieldList['activatedDate']['sortType'] = 'date';

$config->bug->dtable->fieldList['story']['title']      = $lang->bug->story;
$config->bug->dtable->fieldList['story']['type']       = 'text';
$config->bug->dtable->fieldList['story']['link']       = array('module' => 'story', 'method' => 'view', 'params' => 'storyID={story}');
$config->bug->dtable->fieldList['story']['group']      = 8;
$config->bug->dtable->fieldList['story']['dataSource'] = array('module' => 'story', 'method' =>'getProductStoryPairs', 'params' => ['productIdList' => (int)'$productID']);
$config->bug->dtable->fieldList['story']['sortType']   = true;

$config->bug->dtable->fieldList['task']['title']      = $lang->bug->task;
$config->bug->dtable->fieldList['task']['type']       = 'text';
$config->bug->dtable->fieldList['task']['link']       = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={task}');
$config->bug->dtable->fieldList['task']['group']      = 8;
$config->bug->dtable->fieldList['task']['dataSource'] = array('module' => 'bug', 'method' =>'getRelatedObjects', 'params' => 'task&id,name');
$config->bug->dtable->fieldList['task']['sortType']   = true;

$config->bug->dtable->fieldList['mailto']['title']     = $lang->bug->mailto;
$config->bug->dtable->fieldList['mailto']['type']      = 'text';
$config->bug->dtable->fieldList['mailto']['group']     = 9;
$config->bug->dtable->fieldList['mailto']['sortType']  = true;
$config->bug->dtable->fieldList['mailto']['delimiter'] = ',';

$config->bug->dtable->fieldList['keywords']['title']    = $lang->bug->keywords;
$config->bug->dtable->fieldList['keywords']['type']     = 'text';
$config->bug->dtable->fieldList['keywords']['group']    = 9;
$config->bug->dtable->fieldList['keywords']['sortType'] = true;

$config->bug->dtable->fieldList['lastEditedBy']['title']    = $lang->bug->lastEditedBy;
$config->bug->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->bug->dtable->fieldList['lastEditedBy']['group']    = 10;
$config->bug->dtable->fieldList['lastEditedBy']['sortType'] = true;
$config->bug->dtable->fieldList['lastEditedBy']['width']    = '90px';

$config->bug->dtable->fieldList['lastEditedDate']['title']    = $lang->bug->abbr->lastEditedDate;
$config->bug->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->bug->dtable->fieldList['lastEditedDate']['group']    = 10;
$config->bug->dtable->fieldList['lastEditedDate']['sortType'] = 'date';

$config->bug->dtable->fieldList['closedBy']['title']    = $lang->bug->closedBy;
$config->bug->dtable->fieldList['closedBy']['type']     = 'user';
$config->bug->dtable->fieldList['closedBy']['group']    = 10;
$config->bug->dtable->fieldList['closedBy']['sortType'] = true;

$config->bug->dtable->fieldList['closedDate']['title']    = $lang->bug->closedDate;
$config->bug->dtable->fieldList['closedDate']['type']     = 'date';
$config->bug->dtable->fieldList['closedDate']['group']    = 10;
$config->bug->dtable->fieldList['closedDate']['sortType'] = 'date';

$config->bug->dtable->fieldList['steps']['title']   = 'steps';
$config->bug->dtable->fieldList['steps']['control'] = 'textarea';
$config->bug->dtable->fieldList['steps']['display'] = false;

$config->bug->dtable->fieldList['case']['title']      = 'case';
$config->bug->dtable->fieldList['case']['dataSource'] = array('module' => 'bug', 'method' =>'getRelatedObjects', 'params' => 'case&id,title');
$config->bug->dtable->fieldList['case']['display']    = false;

$config->bug->dtable->fieldList['actions']['title']    = $lang->actions;
$config->bug->dtable->fieldList['actions']['type']     = 'actions';
$config->bug->dtable->fieldList['actions']['width']    = '140';
$config->bug->dtable->fieldList['actions']['sortType'] = false;
$config->bug->dtable->fieldList['actions']['fixed']    = 'right';
$config->bug->dtable->fieldList['actions']['list']     = $config->bug->actionList;
$config->bug->dtable->fieldList['actions']['menu']     = array('confirm', 'resolve', 'close|activate', 'edit', 'copy');

$config->bug->linkBugs = new stdclass();
$config->bug->linkBugs->dtable = new stdclass();
$config->bug->linkBugs->dtable->fieldList['id']['title']    = $lang->idAB;
$config->bug->linkBugs->dtable->fieldList['id']['type']     = 'checkID';
$config->bug->linkBugs->dtable->fieldList['id']['checkbox'] = true;
$config->bug->linkBugs->dtable->fieldList['id']['align']    = 'left';
$config->bug->linkBugs->dtable->fieldList['id']['fixed']    = 'left';

$config->bug->linkBugs->dtable->fieldList['pri']['title'] = $lang->bug->pri;
$config->bug->linkBugs->dtable->fieldList['pri']['type']  = 'pri';

$config->bug->linkBugs->dtable->fieldList['product']['title'] = $lang->bug->product;
$config->bug->linkBugs->dtable->fieldList['product']['type']  = 'text';
$config->bug->linkBugs->dtable->fieldList['product']['link']  = array('module' => 'product', 'method' => 'view', 'params' => 'productID={product}');

$config->bug->linkBugs->dtable->fieldList['title']['title'] = $lang->bug->title;
$config->bug->linkBugs->dtable->fieldList['title']['type']  = 'title';
$config->bug->linkBugs->dtable->fieldList['title']['link']  = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}');

$config->bug->linkBugs->dtable->fieldList['status']['title']     = $lang->bug->abbr->status;
$config->bug->linkBugs->dtable->fieldList['status']['type']      = 'status';
$config->bug->linkBugs->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;

$config->bug->linkBugs->dtable->fieldList['openedBy']['title'] = $lang->bug->abbr->openedBy;
$config->bug->linkBugs->dtable->fieldList['openedBy']['type']  = 'user';

$config->bug->linkBugs->dtable->fieldList['assignedTo']['title'] = $lang->bug->assignedTo;
$config->bug->linkBugs->dtable->fieldList['assignedTo']['type']  = 'user';
