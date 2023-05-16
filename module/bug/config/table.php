<?php
global $lang;
$config->bug->dtable = new stdclass();
$config->bug->dtable->defaultField = array('id', 'title', 'severity', 'pri', 'status', 'openedBy', 'openedDate', 'confirmed', 'assignedTo', 'resolution', 'actions');

$config->bug->dtable->fieldList['id']['name']  = 'id';
$config->bug->dtable->fieldList['id']['title'] = $lang->idAB;
$config->bug->dtable->fieldList['id']['type']  = 'checkID';
$config->bug->dtable->fieldList['id']['align'] = 'left';
$config->bug->dtable->fieldList['id']['fixed'] = 'left';

$config->bug->dtable->fieldList['title']['name']     = 'title';
$config->bug->dtable->fieldList['title']['title']    = $lang->bug->title;
$config->bug->dtable->fieldList['title']['type']     = 'title';
$config->bug->dtable->fieldList['title']['minWidth'] = '200';
$config->bug->dtable->fieldList['title']['fixed']    = 'left';
$config->bug->dtable->fieldList['title']['link']     = helper::createLink('bug', 'view', "bugID={id}");

$config->bug->dtable->fieldList['module']['name']  = 'moduleName';
$config->bug->dtable->fieldList['module']['title'] = $lang->bug->module;
$config->bug->dtable->fieldList['module']['type']  = 'shortTitle';

$config->bug->dtable->fieldList['severity']['name']  = 'severity';
$config->bug->dtable->fieldList['severity']['title'] = $lang->bug->severity;
$config->bug->dtable->fieldList['severity']['type']  = 'severity';

$config->bug->dtable->fieldList['pri']['name']  = 'pri';
$config->bug->dtable->fieldList['pri']['title'] = $lang->bug->pri;
$config->bug->dtable->fieldList['pri']['type']  = 'pri';

$config->bug->dtable->fieldList['status']['name']      = 'status';
$config->bug->dtable->fieldList['status']['title']     = $lang->bug->statusAB;
$config->bug->dtable->fieldList['status']['type']      = 'status';
$config->bug->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;

$config->bug->dtable->fieldList['branch']['name']  = 'branch';
$config->bug->dtable->fieldList['branch']['title'] = $lang->bug->branch;
$config->bug->dtable->fieldList['branch']['type']  = 'shortTitle';

$config->bug->dtable->fieldList['type']['name']  = 'type';
$config->bug->dtable->fieldList['type']['title'] = $lang->bug->type;
$config->bug->dtable->fieldList['type']['type']  = 'category';

$config->bug->dtable->fieldList['project']['name']  = 'project';
$config->bug->dtable->fieldList['project']['title'] = $lang->bug->project;
$config->bug->dtable->fieldList['project']['type']  = 'text';

$config->bug->dtable->fieldList['execution']['name']  = 'execution';
$config->bug->dtable->fieldList['execution']['title'] = $lang->bug->execution;
$config->bug->dtable->fieldList['execution']['type']  = 'text';

$config->bug->dtable->fieldList['plan']['name']  = 'plan';
$config->bug->dtable->fieldList['plan']['title'] = $lang->bug->plan;
$config->bug->dtable->fieldList['plan']['width'] = 120;

$config->bug->dtable->fieldList['openedBy']['name']    = 'openedBy';
$config->bug->dtable->fieldList['openedBy']['title']   = $lang->bug->openedByAB;
$config->bug->dtable->fieldList['openedBy']['type']    = 'user';
$config->bug->dtable->fieldList['openedBy']['userMap'] = array();

$config->bug->dtable->fieldList['openedDate']['name']   = 'openedDate';
$config->bug->dtable->fieldList['openedDate']['title']  = $lang->bug->openedDateAB;
$config->bug->dtable->fieldList['openedDate']['type']   = 'date';
$config->bug->dtable->fieldList['openedDate']['format'] = 'n月d日 H:i';

$config->bug->dtable->fieldList['openedBuild']['name']  = 'openedBuild';
$config->bug->dtable->fieldList['openedBuild']['title'] = $lang->bug->openedBuild;
$config->bug->dtable->fieldList['openedBuild']['type']  = 'text';

$config->bug->dtable->fieldList['confirmed']['name']  = 'confirmed';
$config->bug->dtable->fieldList['confirmed']['title'] = $lang->bug->confirmed;
$config->bug->dtable->fieldList['confirmed']['type']  = 'category';

$config->bug->dtable->fieldList['assignedTo']['name']  = 'assignedTo';
$config->bug->dtable->fieldList['assignedTo']['title'] = $lang->bug->assignedTo;
$config->bug->dtable->fieldList['assignedTo']['type']  = 'user';

$config->bug->dtable->fieldList['assignedDate']['name']  = 'assignedDate';
$config->bug->dtable->fieldList['assignedDate']['title'] = $lang->bug->assignedDate;
$config->bug->dtable->fieldList['assignedDate']['type']  = 'date';

$config->bug->dtable->fieldList['deadline']['name']  = 'deadline';
$config->bug->dtable->fieldList['deadline']['title'] = $lang->bug->deadline;
$config->bug->dtable->fieldList['deadline']['type']  = 'date';

$config->bug->dtable->fieldList['resolvedBy']['name']  = 'resolvedBy';
$config->bug->dtable->fieldList['resolvedBy']['title'] = $lang->bug->resolvedBy;
$config->bug->dtable->fieldList['resolvedBy']['type']  = 'user';

$config->bug->dtable->fieldList['resolution']['name']  = 'resolution';
$config->bug->dtable->fieldList['resolution']['title'] = $lang->bug->resolution;
$config->bug->dtable->fieldList['resolution']['type']  = 'category';

$config->bug->dtable->fieldList['resolvedDate']['name']  = 'assignedDate';
$config->bug->dtable->fieldList['resolvedDate']['title'] = $lang->bug->resolvedDateAB;
$config->bug->dtable->fieldList['resolvedDate']['type']  = 'date';

$config->bug->dtable->fieldList['resolvedBuild']['name']  = 'resolvedBuild';
$config->bug->dtable->fieldList['resolvedBuild']['title'] = $lang->bug->resolvedBuild;
$config->bug->dtable->fieldList['resolvedBuild']['type']  = 'text';

$config->bug->dtable->fieldList['activatedCount']['name']  = 'activatedCount';
$config->bug->dtable->fieldList['activatedCount']['title'] = $lang->bug->activatedCountAB;
$config->bug->dtable->fieldList['activatedCount']['type']  = 'count';

$config->bug->dtable->fieldList['activatedDate']['name']  = 'activatedDate';
$config->bug->dtable->fieldList['activatedDate']['title'] = $lang->bug->activatedDate;
$config->bug->dtable->fieldList['activatedDate']['type']  = 'date';

$config->bug->dtable->fieldList['story']['name']  = 'storyName';
$config->bug->dtable->fieldList['story']['title'] = $lang->bug->story;
$config->bug->dtable->fieldList['story']['type']  = 'shortTitle';
$config->bug->dtable->fieldList['story']['link']  = helper::createLink('story', 'view', 'storyID={story}');

$config->bug->dtable->fieldList['task']['name']  = 'taskName';
$config->bug->dtable->fieldList['task']['title'] = $lang->bug->task;
$config->bug->dtable->fieldList['task']['type']  = 'shortTitle';
$config->bug->dtable->fieldList['task']['link']  = helper::createLink('task', 'view', 'taskID={task}');

$config->bug->dtable->fieldList['toTask']['name']  = 'toTaskName';
$config->bug->dtable->fieldList['toTask']['title'] = $lang->bug->toTask;
$config->bug->dtable->fieldList['toTask']['type']  = 'shortTitle';
$config->bug->dtable->fieldList['toTask']['link']  = helper::createLink('task', 'view', 'taskID={toTask}');

$config->bug->dtable->fieldList['keywords']['name']  = 'keywords';
$config->bug->dtable->fieldList['keywords']['title'] = $lang->bug->keywords;
$config->bug->dtable->fieldList['keywords']['type']  = 'text';

$config->bug->dtable->fieldList['os']['name']  = 'os';
$config->bug->dtable->fieldList['os']['title'] = $lang->bug->os;
$config->bug->dtable->fieldList['os']['type']  = 'category';

$config->bug->dtable->fieldList['browser']['name']  = 'browser';
$config->bug->dtable->fieldList['browser']['title'] = $lang->bug->browser;
$config->bug->dtable->fieldList['browser']['type']  = 'category';

$config->bug->dtable->fieldList['mailto']['name']  = 'mailto';
$config->bug->dtable->fieldList['mailto']['title'] = $lang->bug->mailto;
$config->bug->dtable->fieldList['mailto']['type']  = 'user';

$config->bug->dtable->fieldList['closedBy']['name']  = 'closedBy';
$config->bug->dtable->fieldList['closedBy']['title'] = $lang->bug->closedBy;
$config->bug->dtable->fieldList['closedBy']['type']  = 'user';

$config->bug->dtable->fieldList['closedDate']['name']  = 'closedDate';
$config->bug->dtable->fieldList['closedDate']['title'] = $lang->bug->closedDate;
$config->bug->dtable->fieldList['closedDate']['type']  = 'date';

$config->bug->dtable->fieldList['lastEditedBy']['name']  = 'lastEditedBy';
$config->bug->dtable->fieldList['lastEditedBy']['title'] = $lang->bug->lastEditedBy;
$config->bug->dtable->fieldList['lastEditedBy']['type']  = 'user';

$config->bug->dtable->fieldList['lastEditedDate']['name']  = 'lastEditedDate';
$config->bug->dtable->fieldList['lastEditedDate']['title'] = $lang->bug->lastEditedDateAB;
$config->bug->dtable->fieldList['lastEditedDate']['type']  = 'date';

$config->bug->dtable->fieldList['actions']['name']  = 'actions';
$config->bug->dtable->fieldList['actions']['title'] = $lang->actions;
$config->bug->dtable->fieldList['actions']['type']  = 'actions';
$config->bug->dtable->fieldList['actions']['width'] = '140';
$config->bug->dtable->fieldList['actions']['fixed'] = 'right';
