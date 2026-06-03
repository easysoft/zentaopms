<?php
$config->my->bug = new stdclass();
$config->my->bug->actionList = array();
$config->my->bug->actionList['confirm']['icon']        = 'ok';
$config->my->bug->actionList['confirm']['text']        = $lang->bug->abbr->confirmed;
$config->my->bug->actionList['confirm']['hint']        = $lang->bug->abbr->confirmed;
$config->my->bug->actionList['confirm']['url']         = helper::createLink('bug', 'confirm',"bugID={id}");
$config->my->bug->actionList['confirm']['data-toggle'] = 'modal';

$config->my->bug->actionList['resolve']['icon']        = 'checked';
$config->my->bug->actionList['resolve']['text']        = $lang->bug->resolve;
$config->my->bug->actionList['resolve']['hint']        = $lang->bug->resolve;
$config->my->bug->actionList['resolve']['url']         = helper::createLink('bug', 'resolve',"bugID={id}");
$config->my->bug->actionList['resolve']['data-toggle'] = 'modal';

$config->my->bug->actionList['close']['icon']        = 'off';
$config->my->bug->actionList['close']['text']        = $lang->bug->close;
$config->my->bug->actionList['close']['hint']        = $lang->bug->close;
$config->my->bug->actionList['close']['url']         = helper::createLink('bug', 'close',"bugID={id}");
$config->my->bug->actionList['close']['data-toggle'] = 'modal';

$config->my->bug->actionList['activate']['icon']        = 'magic';
$config->my->bug->actionList['activate']['text']        = $lang->bug->activate;
$config->my->bug->actionList['activate']['hint']        = $lang->bug->activate;
$config->my->bug->actionList['activate']['url']         = helper::createLink('bug', 'activate',"bugID={id}");
$config->my->bug->actionList['activate']['data-toggle'] = 'modal';

$config->my->bug->actionList['edit']['icon']        = 'edit';
$config->my->bug->actionList['edit']['text']        = $lang->bug->edit;
$config->my->bug->actionList['edit']['hint']        = $lang->bug->edit;
$config->my->bug->actionList['edit']['url']         = helper::createLink('bug', 'edit',"bugID={id}");
$config->my->bug->actionList['edit']['data-size']   = 'lg';
$config->my->bug->actionList['edit']['data-toggle'] = 'modal';

$config->my->bug->actionList['copy']['icon']        = 'copy';
$config->my->bug->actionList['copy']['text']        = $lang->bug->copy;
$config->my->bug->actionList['copy']['hint']        = $lang->bug->copy;
$config->my->bug->actionList['copy']['url']         = array('module' => 'bug', 'method' => 'create', 'params' => 'productID={product}&branch={branch}&extra=bugID={id}');
$config->my->bug->actionList['copy']['data-size']   = 'lg';
$config->my->bug->actionList['copy']['data-toggle'] = 'modal';

$config->my->bug->dtable = new stdclass();
$config->my->bug->dtable->fieldList['id']['name']  = 'id';
$config->my->bug->dtable->fieldList['id']['title'] = $lang->idAB;
$config->my->bug->dtable->fieldList['id']['type']  = 'checkID';
$config->my->bug->dtable->fieldList['id']['group'] = 'left';

$config->my->bug->dtable->fieldList['title']['name']     = 'title';
$config->my->bug->dtable->fieldList['title']['title']    = $lang->bug->title;
$config->my->bug->dtable->fieldList['title']['styleMap'] = array('--color-link' => 'color');
$config->my->bug->dtable->fieldList['title']['type']     = 'title';
$config->my->bug->dtable->fieldList['title']['minWidth'] = '200';
$config->my->bug->dtable->fieldList['title']['fixed']    = 'left';
$config->my->bug->dtable->fieldList['title']['link']     = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}');
$config->my->bug->dtable->fieldList['title']['group']    = 'left';

$config->my->bug->dtable->fieldList['severity']['name']  = 'severity';
$config->my->bug->dtable->fieldList['severity']['title'] = $lang->bug->severity;
$config->my->bug->dtable->fieldList['severity']['type']  = 'severity';
$config->my->bug->dtable->fieldList['severity']['group'] = '2';

$config->my->bug->dtable->fieldList['pri']['name']  = 'pri';
$config->my->bug->dtable->fieldList['pri']['title'] = $lang->bug->pri;
$config->my->bug->dtable->fieldList['pri']['type']  = 'pri';
$config->my->bug->dtable->fieldList['pri']['group'] = '2';

$config->my->bug->dtable->fieldList['type']['name']   = 'type';
$config->my->bug->dtable->fieldList['type']['title']  = $lang->bug->type;
$config->my->bug->dtable->fieldList['type']['type']   = 'category';
$config->my->bug->dtable->fieldList['type']['map']    = $lang->bug->typeList;
$config->my->bug->dtable->fieldList['type']['group']  = '2';

$config->my->bug->dtable->fieldList['status']['name']      = 'status';
$config->my->bug->dtable->fieldList['status']['title']     = $lang->bug->abbr->status;
$config->my->bug->dtable->fieldList['status']['type']      = 'status';
$config->my->bug->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;
$config->my->bug->dtable->fieldList['status']['group']     = '2';

$config->my->bug->dtable->fieldList['product']['name']  = 'productName';
$config->my->bug->dtable->fieldList['product']['title'] = $lang->bug->product;
$config->my->bug->dtable->fieldList['product']['type']  = 'text';
$config->my->bug->dtable->fieldList['product']['group'] = '3';

$config->my->bug->dtable->fieldList['project']['name']   = 'project';
$config->my->bug->dtable->fieldList['project']['title']  = $lang->bug->project;
$config->my->bug->dtable->fieldList['project']['type']   = 'text';
$config->my->bug->dtable->fieldList['project']['group']  = '3';
$config->my->bug->dtable->fieldList['project']['hidden'] = true;

$config->my->bug->dtable->fieldList['execution']['name']   = 'execution';
$config->my->bug->dtable->fieldList['execution']['title']  = $lang->bug->execution;
$config->my->bug->dtable->fieldList['execution']['type']   = 'text';
$config->my->bug->dtable->fieldList['execution']['group']  = '3';
$config->my->bug->dtable->fieldList['execution']['hidden'] = true;

$config->my->bug->dtable->fieldList['plan']['name']   = 'plan';
$config->my->bug->dtable->fieldList['plan']['title']  = $lang->bug->plan;
$config->my->bug->dtable->fieldList['plan']['width']  = 120;
$config->my->bug->dtable->fieldList['plan']['group']  = '3';
$config->my->bug->dtable->fieldList['plan']['hidden'] = true;

$config->my->bug->dtable->fieldList['openedBuild']['name']   = 'openedBuild';
$config->my->bug->dtable->fieldList['openedBuild']['title']  = $lang->bug->openedBuild;
$config->my->bug->dtable->fieldList['openedBuild']['type']   = 'text';
$config->my->bug->dtable->fieldList['openedBuild']['group']  = '3';
$config->my->bug->dtable->fieldList['openedBuild']['hidden'] = true;

$config->my->bug->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->my->bug->dtable->fieldList['openedBy']['title'] = $lang->bug->abbr->openedBy;
$config->my->bug->dtable->fieldList['openedBy']['type']  = 'user';
$config->my->bug->dtable->fieldList['openedBy']['group'] = '4';

$config->my->bug->dtable->fieldList['openedDate']['name']  = 'openedDate';
$config->my->bug->dtable->fieldList['openedDate']['title'] = $lang->bug->abbr->openedDate;
$config->my->bug->dtable->fieldList['openedDate']['type']  = 'date';
$config->my->bug->dtable->fieldList['openedDate']['group'] = '4';

$config->my->bug->dtable->fieldList['confirmed']['name']  = 'confirmed';
$config->my->bug->dtable->fieldList['confirmed']['title'] = $lang->bug->confirmed;
$config->my->bug->dtable->fieldList['confirmed']['type']  = 'category';
$config->my->bug->dtable->fieldList['confirmed']['map']   = $lang->bug->confirmedList;
$config->my->bug->dtable->fieldList['confirmed']['group'] = '5';

$config->my->bug->dtable->fieldList['assignedTo']['name']       = 'assignedTo';
$config->my->bug->dtable->fieldList['assignedTo']['title']      = $lang->bug->assignedTo;
$config->my->bug->dtable->fieldList['assignedTo']['type']       = 'assign';
$config->my->bug->dtable->fieldList['assignedTo']['assignLink'] = array('module' => 'bug', 'method' => 'assignTo', 'params' => 'bugID={id}');
$config->my->bug->dtable->fieldList['assignedTo']['group']      = '5';

$config->my->bug->dtable->fieldList['assignedDate']['name']   = 'assignedDate';
$config->my->bug->dtable->fieldList['assignedDate']['title']  = $lang->bug->assignedDate;
$config->my->bug->dtable->fieldList['assignedDate']['type']   = 'date';
$config->my->bug->dtable->fieldList['assignedDate']['group']  = '5';
$config->my->bug->dtable->fieldList['assignedDate']['hidden'] = true;

$config->my->bug->dtable->fieldList['deadline']['name']   = 'deadline';
$config->my->bug->dtable->fieldList['deadline']['title']  = $lang->bug->deadline;
$config->my->bug->dtable->fieldList['deadline']['type']   = 'date';
$config->my->bug->dtable->fieldList['deadline']['group']  = '5';

$config->my->bug->dtable->fieldList['resolvedBy']['name']   = 'resolvedBy';
$config->my->bug->dtable->fieldList['resolvedBy']['title']  = $lang->bug->resolvedBy;
$config->my->bug->dtable->fieldList['resolvedBy']['type']   = 'user';
$config->my->bug->dtable->fieldList['resolvedBy']['group']  = '6';

$config->my->bug->dtable->fieldList['resolution']['name']  = 'resolution';
$config->my->bug->dtable->fieldList['resolution']['title'] = $lang->bug->resolution;
$config->my->bug->dtable->fieldList['resolution']['type']  = 'category';
$config->my->bug->dtable->fieldList['resolution']['map']   = $lang->bug->resolutionList;
$config->my->bug->dtable->fieldList['resolution']['group'] = '6';

$config->my->bug->dtable->fieldList['toTask']['name']   = 'toTaskName';
$config->my->bug->dtable->fieldList['toTask']['title']  = $lang->bug->toTask;
$config->my->bug->dtable->fieldList['toTask']['type']   = 'text';
$config->my->bug->dtable->fieldList['toTask']['link']   = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={toTask}');
$config->my->bug->dtable->fieldList['toTask']['group']  = '6';
$config->my->bug->dtable->fieldList['toTask']['hidden'] = true;

$config->my->bug->dtable->fieldList['resolvedDate']['name']   = 'assignedDate';
$config->my->bug->dtable->fieldList['resolvedDate']['title']  = $lang->bug->abbr->resolvedDate;
$config->my->bug->dtable->fieldList['resolvedDate']['type']   = 'date';
$config->my->bug->dtable->fieldList['resolvedDate']['group']  = '6';
$config->my->bug->dtable->fieldList['resolvedDate']['hidden'] = true;

$config->my->bug->dtable->fieldList['resolvedBuild']['name']   = 'resolvedBuild';
$config->my->bug->dtable->fieldList['resolvedBuild']['title']  = $lang->bug->resolvedBuild;
$config->my->bug->dtable->fieldList['resolvedBuild']['type']   = 'text';
$config->my->bug->dtable->fieldList['resolvedBuild']['group']  = '6';
$config->my->bug->dtable->fieldList['resolvedBuild']['hidden'] = true;

$config->my->bug->dtable->fieldList['os']['name']   = 'os';
$config->my->bug->dtable->fieldList['os']['title']  = $lang->bug->os;
$config->my->bug->dtable->fieldList['os']['type']   = 'category';
$config->my->bug->dtable->fieldList['os']['map']    = $lang->bug->osList;
$config->my->bug->dtable->fieldList['os']['group']  = '7';
$config->my->bug->dtable->fieldList['os']['hidden'] = true;

$config->my->bug->dtable->fieldList['browser']['name']   = 'browser';
$config->my->bug->dtable->fieldList['browser']['title']  = $lang->bug->browser;
$config->my->bug->dtable->fieldList['browser']['type']   = 'category';
$config->my->bug->dtable->fieldList['browser']['map']    = $lang->bug->browserList;
$config->my->bug->dtable->fieldList['browser']['group']  = '7';
$config->my->bug->dtable->fieldList['browser']['hidden'] = true;

$config->my->bug->dtable->fieldList['activatedCount']['name']   = 'activatedCount';
$config->my->bug->dtable->fieldList['activatedCount']['title']  = $lang->bug->abbr->activatedCount;
$config->my->bug->dtable->fieldList['activatedCount']['type']   = 'count';
$config->my->bug->dtable->fieldList['activatedCount']['group']  = '8';
$config->my->bug->dtable->fieldList['activatedCount']['hidden'] = true;

$config->my->bug->dtable->fieldList['activatedDate']['name']   = 'activatedDate';
$config->my->bug->dtable->fieldList['activatedDate']['title']  = $lang->bug->activatedDate;
$config->my->bug->dtable->fieldList['activatedDate']['type']   = 'date';
$config->my->bug->dtable->fieldList['activatedDate']['group']  = '8';
$config->my->bug->dtable->fieldList['activatedDate']['hidden'] = true;

$config->my->bug->dtable->fieldList['story']['name']   = 'storyName';
$config->my->bug->dtable->fieldList['story']['title']  = $lang->bug->story;
$config->my->bug->dtable->fieldList['story']['type']   = 'text';
$config->my->bug->dtable->fieldList['story']['link']   = array('module' => 'story', 'method' => 'view', 'params' => 'storyID={story}');
$config->my->bug->dtable->fieldList['story']['group']  = '8';
$config->my->bug->dtable->fieldList['story']['hidden'] = true;

$config->my->bug->dtable->fieldList['task']['name']   = 'taskName';
$config->my->bug->dtable->fieldList['task']['title']  = $lang->bug->task;
$config->my->bug->dtable->fieldList['task']['type']   = 'text';
$config->my->bug->dtable->fieldList['task']['link']   = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={task}');
$config->my->bug->dtable->fieldList['task']['group']  = '8';
$config->my->bug->dtable->fieldList['task']['hidden'] = true;

$config->my->bug->dtable->fieldList['mailto']['name']   = 'mailto';
$config->my->bug->dtable->fieldList['mailto']['title']  = $lang->bug->mailto;
$config->my->bug->dtable->fieldList['mailto']['type']   = 'user';
$config->my->bug->dtable->fieldList['mailto']['group']  = '9';
$config->my->bug->dtable->fieldList['mailto']['hidden'] = true;

$config->my->bug->dtable->fieldList['keywords']['name']  = 'keywords';
$config->my->bug->dtable->fieldList['keywords']['title'] = $lang->bug->keywords;
$config->my->bug->dtable->fieldList['keywords']['type']  = 'text';
$config->my->bug->dtable->fieldList['keywords']['group']  = '9';
$config->my->bug->dtable->fieldList['keywords']['hidden'] = true;

$config->my->bug->dtable->fieldList['lastEditedBy']['name']   = 'lastEditedBy';
$config->my->bug->dtable->fieldList['lastEditedBy']['title']  = $lang->bug->lastEditedBy;
$config->my->bug->dtable->fieldList['lastEditedBy']['type']   = 'user';
$config->my->bug->dtable->fieldList['lastEditedBy']['group']  = '10';
$config->my->bug->dtable->fieldList['lastEditedBy']['hidden'] = true;

$config->my->bug->dtable->fieldList['lastEditedDate']['name']   = 'lastEditedDate';
$config->my->bug->dtable->fieldList['lastEditedDate']['title']  = $lang->bug->abbr->lastEditedDate;
$config->my->bug->dtable->fieldList['lastEditedDate']['type']   = 'date';
$config->my->bug->dtable->fieldList['lastEditedDate']['group']  = '10';
$config->my->bug->dtable->fieldList['lastEditedDate']['hidden'] = true;

$config->my->bug->dtable->fieldList['closedBy']['name']   = 'closedBy';
$config->my->bug->dtable->fieldList['closedBy']['title']  = $lang->bug->closedBy;
$config->my->bug->dtable->fieldList['closedBy']['type']   = 'user';
$config->my->bug->dtable->fieldList['closedBy']['group']  = '10';
$config->my->bug->dtable->fieldList['closedBy']['hidden'] = true;

$config->my->bug->dtable->fieldList['closedDate']['name']   = 'closedDate';
$config->my->bug->dtable->fieldList['closedDate']['title']  = $lang->bug->closedDate;
$config->my->bug->dtable->fieldList['closedDate']['type']   = 'date';
$config->my->bug->dtable->fieldList['closedDate']['group']  = '10';
$config->my->bug->dtable->fieldList['closedDate']['hidden'] = true;

$config->my->bug->dtable->fieldList['module']['name']   = 'module';
$config->my->bug->dtable->fieldList['module']['title']  = $lang->bug->module;
$config->my->bug->dtable->fieldList['module']['type']   = 'text';
$config->my->bug->dtable->fieldList['module']['hidden'] = true;

$config->my->bug->dtable->fieldList['branch']['name']   = 'branch';
$config->my->bug->dtable->fieldList['branch']['title']  = $lang->bug->branch;
$config->my->bug->dtable->fieldList['branch']['type']   = 'text';
$config->my->bug->dtable->fieldList['branch']['hidden'] = true;

$config->my->bug->dtable->fieldList['actions']['name']     = 'actions';
$config->my->bug->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->bug->dtable->fieldList['actions']['type']     = 'actions';
$config->my->bug->dtable->fieldList['actions']['width']    = '140';
$config->my->bug->dtable->fieldList['actions']['sortType'] = false;
$config->my->bug->dtable->fieldList['actions']['fixed']    = 'right';
$config->my->bug->dtable->fieldList['actions']['list']     = $config->my->bug->actionList;
$config->my->bug->dtable->fieldList['actions']['menu']     = array('confirm', 'resolve', 'close|activate', 'edit', 'copy');

if($isEn)
{
    $config->my->bug->dtable->fieldList['resolvedBy']['width'] = 100;
    $config->my->bug->dtable->fieldList['assignedTo']['width'] = 120;
}
