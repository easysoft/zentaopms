<?php
global $lang, $app;
$app->loadLang('story');
$app->loadLang('bug');
$app->loadLang('build');
$isEn = $app->getClientLang() == 'en';

$config->release->dtable = new stdclass();
$config->release->dtable->story   = new stdclass();
$config->release->dtable->bug     = new stdclass();
$config->release->dtable->leftBug = new stdclass();

$config->release->dtable->defaultFields['linkStory']          = array('id', 'title', 'pri', 'status', 'linkedBuild', 'estimate', 'stage');
$config->release->dtable->defaultFields['linkBug']['bug']     = array('id', 'title', 'pri', 'status', 'openedBy', 'resolvedBy');
$config->release->dtable->defaultFields['linkBug']['leftBug'] = array('id', 'title', 'severity', 'pri', 'status','openedBy', 'resolvedBy');

$config->release->dtable->fieldList['id']['title'] = $lang->idAB;
$config->release->dtable->fieldList['id']['name']  = 'id';
$config->release->dtable->fieldList['id']['type']  = 'checkID';

$config->release->dtable->fieldList['system']['name']         = 'system';
$config->release->dtable->fieldList['system']['title']        = $lang->release->system;
$config->release->dtable->fieldList['system']['type']         = 'shortNestedTitle';
$config->release->dtable->fieldList['system']['fixed']        = 'left';
$config->release->dtable->fieldList['system']['show']         = true;
$config->release->dtable->fieldList['system']['required']     = true;
$config->release->dtable->fieldList['system']['nestedToggle'] = true;

$config->release->dtable->fieldList['name']['title']    = $lang->release->name;
$config->release->dtable->fieldList['name']['name']     = 'name';
$config->release->dtable->fieldList['name']['link']     = array('module' => 'release', 'method' => 'view', 'params' => 'releaseID={id}');
$config->release->dtable->fieldList['name']['type']     = 'category';
$config->release->dtable->fieldList['name']['fixed']    = 'left';
$config->release->dtable->fieldList['name']['width']    = 150;
$config->release->dtable->fieldList['name']['show']     = true;
$config->release->dtable->fieldList['name']['required'] = true;

$config->release->dtable->fieldList['branch']['title']    = $lang->release->branch;
$config->release->dtable->fieldList['branch']['name']     = 'branchName';
$config->release->dtable->fieldList['branch']['type']     = 'category';
$config->release->dtable->fieldList['branch']['sortType'] = true;
$config->release->dtable->fieldList['branch']['show']     = true;

$config->release->dtable->fieldList['project']['title'] = $lang->release->project;
$config->release->dtable->fieldList['project']['name']  = 'project';
$config->release->dtable->fieldList['project']['type']  = 'category';
$config->release->dtable->fieldList['project']['group'] = 1;
$config->release->dtable->fieldList['project']['show']  = true;

$config->release->dtable->fieldList['build']['title'] = $lang->release->includedBuild;
$config->release->dtable->fieldList['build']['name']  = 'build';
$config->release->dtable->fieldList['build']['type']  = 'category';
$config->release->dtable->fieldList['build']['group'] = 1;
$config->release->dtable->fieldList['build']['show']  = true;

$config->release->dtable->fieldList['status']['title']     = $lang->release->status;
$config->release->dtable->fieldList['status']['name']      = 'status';
$config->release->dtable->fieldList['status']['type']      = 'status';
$config->release->dtable->fieldList['status']['statusMap'] = $lang->release->statusList;
$config->release->dtable->fieldList['status']['width']     = 120;
$config->release->dtable->fieldList['status']['show']      = true;

$config->release->dtable->fieldList['date']['title']    = $lang->release->date;
$config->release->dtable->fieldList['date']['name']     = 'date';
$config->release->dtable->fieldList['date']['type']     = 'date';
$config->release->dtable->fieldList['date']['minWidth'] = $isEn ? '150' : '100';
$config->release->dtable->fieldList['date']['show']     = true;

$config->release->dtable->fieldList['releasedDate']['title']    = $lang->release->releasedDate;
$config->release->dtable->fieldList['releasedDate']['name']     = 'releasedDate';
$config->release->dtable->fieldList['releasedDate']['type']     = 'date';
$config->release->dtable->fieldList['releasedDate']['minWidth'] = $isEn ? '150' : '100';
$config->release->dtable->fieldList['releasedDate']['show']     = true;

$config->release->dtable->fieldList['desc']['title']    = $lang->release->desc;
$config->release->dtable->fieldList['desc']['name']     = 'desc';
$config->release->dtable->fieldList['desc']['type']     = 'desc';
$config->release->dtable->fieldList['desc']['width']    = '160';
$config->release->dtable->fieldList['desc']['sortType'] = false;
$config->release->dtable->fieldList['desc']['hint']     = true;
$config->release->dtable->fieldList['desc']['show']     = true;

$config->release->dtable->fieldList['actions']['title'] = $lang->actions;
$config->release->dtable->fieldList['actions']['name']  = 'actions';
$config->release->dtable->fieldList['actions']['type']  = 'actions';
$config->release->dtable->fieldList['actions']['width'] = 'auto';
$config->release->dtable->fieldList['actions']['list']  = $config->release->actionList;
$config->release->dtable->fieldList['actions']['menu']  = array('linkStory', 'linkBug', 'publish|play|pause', 'edit', 'notify', 'delete');

$config->release->dtable->story->fieldList['id']['title']    = $lang->idAB;
$config->release->dtable->story->fieldList['id']['name']     = 'id';
$config->release->dtable->story->fieldList['id']['type']     = 'checkID';
$config->release->dtable->story->fieldList['id']['sortType'] = 'desc';
$config->release->dtable->story->fieldList['id']['checkbox'] = true;

$config->release->dtable->story->fieldList['title']['title']       = $lang->story->title;
$config->release->dtable->story->fieldList['title']['name']        = 'title';
$config->release->dtable->story->fieldList['title']['type']        = 'title';
$config->release->dtable->story->fieldList['title']['link']        = helper::createLink('story', 'view', 'storyID={id}');
$config->release->dtable->story->fieldList['title']['data-toggle'] = 'modal';
$config->release->dtable->story->fieldList['title']['data-size']   = 'lg';
$config->release->dtable->story->fieldList['title']['data-app']    = $app->tab;

$config->release->dtable->story->fieldList['pri']['title']   = $lang->priAB;
$config->release->dtable->story->fieldList['pri']['name']    = 'pri';
$config->release->dtable->story->fieldList['pri']['type']    = 'pri';
$config->release->dtable->story->fieldList['pri']['priList'] = $lang->story->priList;

$config->release->dtable->story->fieldList['status']['title']     = $lang->statusAB;
$config->release->dtable->story->fieldList['status']['name']      = 'status';
$config->release->dtable->story->fieldList['status']['type']      = 'status';
$config->release->dtable->story->fieldList['status']['statusMap'] = $lang->story->statusList;

$config->release->dtable->story->fieldList['linkedBuild']['title'] = $lang->build->linkedBuild;
$config->release->dtable->story->fieldList['linkedBuild']['name']  = 'buildName';
$config->release->dtable->story->fieldList['linkedBuild']['type']  = 'text';

$config->release->dtable->story->fieldList['openedBy']['title'] = $lang->openedByAB;
$config->release->dtable->story->fieldList['openedBy']['name']  = 'openedBy';
$config->release->dtable->story->fieldList['openedBy']['type']  = 'user';

$config->release->dtable->story->fieldList['estimate']['title'] = $lang->story->estimateAB;
$config->release->dtable->story->fieldList['estimate']['name']  = 'estimate';
$config->release->dtable->story->fieldList['estimate']['type']  = 'number';
if($isEn) $config->release->dtable->story->fieldList['estimate']['width'] = 100;

$config->release->dtable->story->fieldList['stage']['title'] = $lang->story->stageAB;
$config->release->dtable->story->fieldList['stage']['name']  = 'stage';
$config->release->dtable->story->fieldList['stage']['type']  = 'category';
$config->release->dtable->story->fieldList['stage']['map']   = $lang->story->stageList;
if($isEn) $config->release->dtable->story->fieldList['stage']['width'] = 100;

$config->release->dtable->story->fieldList['actions']['title']    = $lang->actions;
$config->release->dtable->story->fieldList['actions']['name']     = 'actions';
$config->release->dtable->story->fieldList['actions']['type']     = 'actions';
$config->release->dtable->story->fieldList['actions']['minWidth'] = 60;
$config->release->dtable->story->fieldList['actions']['menu']     = array('unlinkStory');
$config->release->dtable->story->fieldList['actions']['list']     = $config->release->actionList;
if($isEn) $config->release->dtable->story->fieldList['actions']['width'] = 80;

$config->release->dtable->bug->fieldList['id']['title']    = $lang->idAB;
$config->release->dtable->bug->fieldList['id']['name']     = 'id';
$config->release->dtable->bug->fieldList['id']['type']     = 'checkID';
$config->release->dtable->bug->fieldList['id']['sortType'] = 'desc';

$config->release->dtable->bug->fieldList['title']['title']       = $lang->bug->title;
$config->release->dtable->bug->fieldList['title']['name']        = 'title';
$config->release->dtable->bug->fieldList['title']['type']        = 'title';
$config->release->dtable->bug->fieldList['title']['link']        = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}');
$config->release->dtable->bug->fieldList['title']['data-toggle'] = 'modal';
$config->release->dtable->bug->fieldList['title']['data-size']   = 'lg';
$config->release->dtable->bug->fieldList['title']['data-app']    = $app->tab;

$config->release->dtable->bug->fieldList['severity']['title']        = $lang->bug->severity;
$config->release->dtable->bug->fieldList['severity']['name']         = 'severity';
$config->release->dtable->bug->fieldList['severity']['type']         = 'severity';
$config->release->dtable->bug->fieldList['severity']['severityList'] = $lang->bug->severityList;

$config->release->dtable->bug->fieldList['pri']['title']   = $lang->priAB;
$config->release->dtable->bug->fieldList['pri']['name']    = 'pri';
$config->release->dtable->bug->fieldList['pri']['type']    = 'pri';
$config->release->dtable->bug->fieldList['pri']['priList'] = $lang->bug->priList;

$config->release->dtable->bug->fieldList['status']['title']     = $lang->statusAB;
$config->release->dtable->bug->fieldList['status']['name']      = 'status';
$config->release->dtable->bug->fieldList['status']['type']      = 'status';
$config->release->dtable->bug->fieldList['status']['statusMap'] = $lang->bug->statusList;

$config->release->dtable->bug->fieldList['resolvedBuild']['title'] = $lang->bug->resolvedBuild;
$config->release->dtable->bug->fieldList['resolvedBuild']['name']  = 'resolvedBuild';
$config->release->dtable->bug->fieldList['resolvedBuild']['type']  = 'text';

$config->release->dtable->bug->fieldList['openedBy']['title'] = $lang->openedByAB;
$config->release->dtable->bug->fieldList['openedBy']['name']  = 'openedBy';
$config->release->dtable->bug->fieldList['openedBy']['type']  = 'user';

$config->release->dtable->bug->fieldList['openedDate']['title'] = $lang->bug->abbr->openedDate;
$config->release->dtable->bug->fieldList['openedDate']['name']  = 'openedDate';
$config->release->dtable->bug->fieldList['openedDate']['type']  = 'date';

$config->release->dtable->bug->fieldList['resolvedBy']['title'] = $lang->bug->resolvedBy;
$config->release->dtable->bug->fieldList['resolvedBy']['name']  = 'resolvedBy';
$config->release->dtable->bug->fieldList['resolvedBy']['type']  = 'user';
if($isEn) $config->release->dtable->bug->fieldList['resolvedBy']['width'] = 120;

$config->release->dtable->bug->fieldList['resolvedDate']['title'] = $lang->bug->abbr->resolvedDate;
$config->release->dtable->bug->fieldList['resolvedDate']['name']  = 'resolvedDate';
$config->release->dtable->bug->fieldList['resolvedDate']['type']  = 'date';

$config->release->dtable->bug->fieldList['actions']['title']    = $lang->actions;
$config->release->dtable->bug->fieldList['actions']['name']     = 'actions';
$config->release->dtable->bug->fieldList['actions']['type']     = 'actions';
$config->release->dtable->bug->fieldList['actions']['minWidth'] = 60;
$config->release->dtable->bug->fieldList['actions']['menu']     = array('unlinkBug');
$config->release->dtable->bug->fieldList['actions']['list']     = $config->release->actionList;
if($isEn) $config->release->dtable->bug->fieldList['actions']['width'] = 80;

$config->release->dtable->leftBug = clone $config->release->dtable->bug;

$config->release->dtable->leftBug->fieldList['openedBuild']['title']    = $lang->bug->openedBuild;
$config->release->dtable->leftBug->fieldList['openedBuild']['name']     = 'openedBuild';
$config->release->dtable->leftBug->fieldList['openedBuild']['type']     = 'text';
$config->release->dtable->leftBug->fieldList['openedBuild']['sortType'] = true;

$config->release->dtable->leftBug->fieldList['severity']['name'] = 'severityOrder';
$config->release->dtable->leftBug->fieldList['actions']['menu']  = array('unlinkLeftBug');
unset($config->release->dtable->leftBug->fieldList['resolvedBuild']);
unset($config->release->dtable->leftBug->fieldList['resolvedBy']);
unset($config->release->dtable->leftBug->fieldList['resolvedDate']);

$config->release->dtable->escapedBug = clone $config->release->dtable->bug;
$config->release->dtable->escapedBug->fieldList['id']['type'] = 'id';
unset($config->release->dtable->escapedBug->fieldList['actions']);

/* 解决的Bug列表高级表格字段*/
$config->release->bug = new stdclass();
$config->release->bug->dtable = new stdclass();

$config->release->bug->dtable->fieldList['id']['title']    = $lang->idAB;
$config->release->bug->dtable->fieldList['id']['name']     = 'id';
$config->release->bug->dtable->fieldList['id']['type']     = 'checkID';
$config->release->bug->dtable->fieldList['id']['sortType'] = 'desc';
$config->release->bug->dtable->fieldList['id']['checkbox'] = true;
$config->release->bug->dtable->fieldList['id']['show']     = true;

$config->release->bug->dtable->fieldList['title']['title']       = $lang->bug->title;
$config->release->bug->dtable->fieldList['title']['name']        = 'title';
$config->release->bug->dtable->fieldList['title']['type']        = 'title';
$config->release->bug->dtable->fieldList['title']['link']        = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}');
$config->release->bug->dtable->fieldList['title']['data-toggle'] = 'modal';
$config->release->bug->dtable->fieldList['title']['data-size']   = 'lg';
$config->release->bug->dtable->fieldList['title']['data-app']    = $app->tab;
$config->release->bug->dtable->fieldList['title']['show']        = true;

$config->release->bug->dtable->fieldList['severity']['title']        = $lang->bug->severity;
$config->release->bug->dtable->fieldList['severity']['name']         = 'severity';
$config->release->bug->dtable->fieldList['severity']['type']         = 'severity';
$config->release->bug->dtable->fieldList['severity']['severityList'] = $lang->bug->severityList;
$config->release->bug->dtable->fieldList['severity']['show']         = true;

$config->release->bug->dtable->fieldList['pri']['title']   = $lang->priAB;
$config->release->bug->dtable->fieldList['pri']['name']    = 'pri';
$config->release->bug->dtable->fieldList['pri']['type']    = 'pri';
$config->release->bug->dtable->fieldList['pri']['priList'] = $lang->bug->priList;
$config->release->bug->dtable->fieldList['pri']['show']    = true;

$config->release->bug->dtable->fieldList['module']['title']    = $lang->bug->module;
$config->release->bug->dtable->fieldList['module']['name']     = 'module';
$config->release->bug->dtable->fieldList['module']['type']     = 'category';
$config->release->bug->dtable->fieldList['module']['sortType'] = true;

$config->release->bug->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->release->bug->dtable->fieldList['status']['name']      = 'status';
$config->release->bug->dtable->fieldList['status']['type']      = 'status';
$config->release->bug->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;
$config->release->bug->dtable->fieldList['status']['show']      = true;

$config->release->bug->dtable->fieldList['type']['title']    = $lang->bug->type;
$config->release->bug->dtable->fieldList['type']['name']     = 'type';
$config->release->bug->dtable->fieldList['type']['type']     = 'category';
$config->release->bug->dtable->fieldList['type']['map']      = $lang->bug->typeList;
$config->release->bug->dtable->fieldList['type']['flex']     = false;
$config->release->bug->dtable->fieldList['type']['sortType'] = true;

$config->release->bug->dtable->fieldList['branch']['title']    = $lang->bug->branch;
$config->release->bug->dtable->fieldList['branch']['name']     = 'branch';
$config->release->bug->dtable->fieldList['branch']['type']     = 'text';
$config->release->bug->dtable->fieldList['branch']['sortType'] = true;

$config->release->bug->dtable->fieldList['project']['title']    = $lang->bug->project;
$config->release->bug->dtable->fieldList['project']['name']     = 'project';
$config->release->bug->dtable->fieldList['project']['type']     = 'text';
$config->release->bug->dtable->fieldList['project']['sortType'] = true;

$config->release->bug->dtable->fieldList['execution']['title']    = $lang->bug->execution;
$config->release->bug->dtable->fieldList['execution']['name']     = 'execution';
$config->release->bug->dtable->fieldList['execution']['type']     = 'text';
$config->release->bug->dtable->fieldList['execution']['sortType'] = true;

$config->release->bug->dtable->fieldList['openedBuild']['title']    = $lang->bug->openedBuild;
$config->release->bug->dtable->fieldList['openedBuild']['name']     = 'openedBuild';
$config->release->bug->dtable->fieldList['openedBuild']['type']     = 'text';
$config->release->bug->dtable->fieldList['openedBuild']['control']  = 'multiple';
$config->release->bug->dtable->fieldList['openedBuild']['sortType'] = true;

$config->release->bug->dtable->fieldList['openedBy']['title'] = $lang->openedByAB;
$config->release->bug->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->release->bug->dtable->fieldList['openedBy']['type']  = 'user';
$config->release->bug->dtable->fieldList['openedBy']['show']  = true;

$config->release->bug->dtable->fieldList['openedDate']['title']           = $lang->bug->abbr->openedDate;
$config->release->bug->dtable->fieldList['openedDate']['name']            = 'openedDate';
$config->release->bug->dtable->fieldList['openedDate']['type']            = 'date';
$config->release->bug->dtable->fieldList['openedDate']['show']            = true;
if($isEn) $config->release->bug->dtable->fieldList['openedDate']['width'] = '120';

$config->release->bug->dtable->fieldList['confirmed']['title']           = $lang->bug->confirmed;
$config->release->bug->dtable->fieldList['confirmed']['name']            = 'confirmed';
$config->release->bug->dtable->fieldList['confirmed']['type']            = 'category';
$config->release->bug->dtable->fieldList['confirmed']['map']             = $lang->bug->confirmedList;
$config->release->bug->dtable->fieldList['confirmed']['flex']            = false;
$config->release->bug->dtable->fieldList['confirmed']['sortType']        = true;
if($isEn) $config->release->bug->dtable->fieldList['confirmed']['width'] = '150';

$config->release->bug->dtable->fieldList['assignedTo']['title']           = $lang->bug->assignedTo;
$config->release->bug->dtable->fieldList['assignedTo']['name']            = 'assignedTo';
$config->release->bug->dtable->fieldList['assignedTo']['type']            = 'assign';
$config->release->bug->dtable->fieldList['assignedTo']['assignLink']      = array('module' => 'bug', 'method' => 'assignTo', 'params' => 'bugID={id}');
$config->release->bug->dtable->fieldList['assignedTo']['sortType']        = true;
if($isEn) $config->release->bug->dtable->fieldList['assignedTo']['width'] = '120';

$config->release->bug->dtable->fieldList['assignedDate']['title']    = $lang->bug->assignedDate;
$config->release->bug->dtable->fieldList['assignedDate']['name']     = 'assignedDate';
$config->release->bug->dtable->fieldList['assignedDate']['type']     = 'date';
$config->release->bug->dtable->fieldList['assignedDate']['sortType'] = 'date';

$config->release->bug->dtable->fieldList['deadline']['title']    = $lang->bug->deadline;
$config->release->bug->dtable->fieldList['deadline']['name']     = 'deadline';
$config->release->bug->dtable->fieldList['deadline']['type']     = 'date';
$config->release->bug->dtable->fieldList['deadline']['sortType'] = true;

$config->release->bug->dtable->fieldList['resolvedBy']['title']           = $lang->bug->resolvedBy;
$config->release->bug->dtable->fieldList['resolvedBy']['name']            = 'resolvedBy';
$config->release->bug->dtable->fieldList['resolvedBy']['type']            = 'user';
$config->release->bug->dtable->fieldList['resolvedBy']['show']            = true;
if($isEn) $config->release->bug->dtable->fieldList['resolvedBy']['width'] = 120;

$config->release->bug->dtable->fieldList['resolution']['title']           = $lang->bug->resolution;
$config->release->bug->dtable->fieldList['resolution']['name']            = 'resolution';
$config->release->bug->dtable->fieldList['resolution']['type']            = 'category';
$config->release->bug->dtable->fieldList['resolution']['map']             = $lang->bug->resolutionList;
$config->release->bug->dtable->fieldList['resolution']['sortType']        = true;
if($isEn) $config->release->bug->dtable->fieldList['resolution']['width'] = '100';

$config->release->bug->dtable->fieldList['toTask']['title']    = $lang->bug->toTask;
$config->release->bug->dtable->fieldList['toTask']['name']     = 'toTask';
$config->release->bug->dtable->fieldList['toTask']['type']     = 'text';
$config->release->bug->dtable->fieldList['toTask']['link']     = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={toTask}');
$config->release->bug->dtable->fieldList['toTask']['sortType'] = true;

$config->release->bug->dtable->fieldList['resolvedDate']['title']    = $lang->bug->abbr->resolvedDate;
$config->release->bug->dtable->fieldList['resolvedDate']['name']     = 'resolvedDate';
$config->release->bug->dtable->fieldList['resolvedDate']['type']     = 'date';
$config->release->bug->dtable->fieldList['resolvedDate']['show']     = true;
$config->release->bug->dtable->fieldList['resolvedDate']['sortType'] = 'date';

$config->release->bug->dtable->fieldList['resolvedBuild']['title'] = $lang->bug->resolvedBuild;
$config->release->bug->dtable->fieldList['resolvedBuild']['name']  = 'resolvedBuild';
$config->release->bug->dtable->fieldList['resolvedBuild']['type']  = 'text';
$config->release->bug->dtable->fieldList['resolvedBuild']['show']  = true;

$config->release->bug->dtable->fieldList['os']['title']    = $lang->bug->os;
$config->release->bug->dtable->fieldList['os']['name']     = 'os';
$config->release->bug->dtable->fieldList['os']['type']     = 'category';
$config->release->bug->dtable->fieldList['os']['map']      = $lang->bug->osList;
$config->release->bug->dtable->fieldList['os']['control']  = 'multiple';
$config->release->bug->dtable->fieldList['os']['sortType'] = true;

$config->release->bug->dtable->fieldList['browser']['title']    = $lang->bug->browser;
$config->release->bug->dtable->fieldList['browser']['name']     = 'browser';
$config->release->bug->dtable->fieldList['browser']['type']     = 'category';
$config->release->bug->dtable->fieldList['browser']['map']      = $lang->bug->browserList;
$config->release->bug->dtable->fieldList['browser']['control']  = 'multiple';
$config->release->bug->dtable->fieldList['browser']['sortType'] = true;

$config->release->bug->dtable->fieldList['activatedCount']['title']    = $lang->bug->abbr->activatedCount;
$config->release->bug->dtable->fieldList['activatedCount']['name']     = 'activatedCount';
$config->release->bug->dtable->fieldList['activatedCount']['type']     = 'count';
$config->release->bug->dtable->fieldList['activatedCount']['sortType'] = true;

$config->release->bug->dtable->fieldList['activatedDate']['title']    = $lang->bug->activatedDate;
$config->release->bug->dtable->fieldList['activatedDate']['name']     = 'activatedDate';
$config->release->bug->dtable->fieldList['activatedDate']['type']     = 'date';
$config->release->bug->dtable->fieldList['activatedDate']['sortType'] = 'date';

$config->release->bug->dtable->fieldList['story']['title']    = $lang->bug->story;
$config->release->bug->dtable->fieldList['story']['name']     = 'story';
$config->release->bug->dtable->fieldList['story']['type']     = 'text';
$config->release->bug->dtable->fieldList['story']['link']     = array('module' => 'story', 'method' => 'view', 'params' => 'storyID={story}');
$config->release->bug->dtable->fieldList['story']['sortType'] = true;

$config->release->bug->dtable->fieldList['task']['title']    = $lang->bug->task;
$config->release->bug->dtable->fieldList['task']['name']     = 'task';
$config->release->bug->dtable->fieldList['task']['type']     = 'text';
$config->release->bug->dtable->fieldList['task']['link']     = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={task}');
$config->release->bug->dtable->fieldList['task']['sortType'] = true;

$config->release->bug->dtable->fieldList['mailto']['title']     = $lang->bug->mailto;
$config->release->bug->dtable->fieldList['mailto']['name']      = 'mailto';
$config->release->bug->dtable->fieldList['mailto']['type']      = 'text';
$config->release->bug->dtable->fieldList['mailto']['sortType']  = true;
$config->release->bug->dtable->fieldList['mailto']['delimiter'] = ',';

if(in_array($config->edition, array('max', 'ipd')))
{
    $config->release->bug->dtable->fieldList['injection']['title']   = $lang->bug->injection;
    $config->release->bug->dtable->fieldList['injection']['name']    = 'injection';
    $config->release->bug->dtable->fieldList['injection']['control'] = 'picker';
    $config->release->bug->dtable->fieldList['injection']['type']    = 'text';
    $config->release->bug->dtable->fieldList['injection']['map']     = $lang->bug->injectionList;

    $config->release->bug->dtable->fieldList['identify']['title']   = $lang->bug->identify;
    $config->release->bug->dtable->fieldList['identify']['name']    = 'identify';
    $config->release->bug->dtable->fieldList['identify']['control'] = 'picker';
    $config->release->bug->dtable->fieldList['identify']['type']    = 'text';
    $config->release->bug->dtable->fieldList['identify']['map']     = $lang->bug->identifyList;
}

$config->release->bug->dtable->fieldList['keywords']['title']    = $lang->bug->keywords;
$config->release->bug->dtable->fieldList['keywords']['name']     = 'keywords';
$config->release->bug->dtable->fieldList['keywords']['type']     = 'text';
$config->release->bug->dtable->fieldList['keywords']['sortType'] = true;

$config->release->bug->dtable->fieldList['lastEditedBy']['title']    = $lang->bug->lastEditedBy;
$config->release->bug->dtable->fieldList['lastEditedBy']['name']     = 'lastEditedBy';
$config->release->bug->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->release->bug->dtable->fieldList['lastEditedBy']['sortType'] = true;
$config->release->bug->dtable->fieldList['lastEditedBy']['width']    = '90px';

$config->release->bug->dtable->fieldList['lastEditedDate']['title']    = $lang->bug->abbr->lastEditedDate;
$config->release->bug->dtable->fieldList['lastEditedDate']['name']     = 'lastEditedDate';
$config->release->bug->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->release->bug->dtable->fieldList['lastEditedDate']['sortType'] = 'date';

$config->release->bug->dtable->fieldList['closedBy']['title']    = $lang->bug->closedBy;
$config->release->bug->dtable->fieldList['closedBy']['name']     = 'closedBy';
$config->release->bug->dtable->fieldList['closedBy']['type']     = 'user';
$config->release->bug->dtable->fieldList['closedBy']['sortType'] = true;

$config->release->bug->dtable->fieldList['closedDate']['title']    = $lang->bug->closedDate;
$config->release->bug->dtable->fieldList['closedDate']['name']     = 'closedDate';
$config->release->bug->dtable->fieldList['closedDate']['type']     = 'date';
$config->release->bug->dtable->fieldList['closedDate']['sortType'] = 'date';

$config->release->bug->dtable->fieldList['actions']['title']    = $lang->actions;
$config->release->bug->dtable->fieldList['actions']['name']     = 'actions';
$config->release->bug->dtable->fieldList['actions']['type']     = 'actions';
$config->release->bug->dtable->fieldList['actions']['width']    = 100;
$config->release->bug->dtable->fieldList['actions']['minWidth'] = 60;
$config->release->bug->dtable->fieldList['actions']['menu']     = array('unlinkBug');
$config->release->bug->dtable->fieldList['actions']['list']     = $config->release->actionList;
