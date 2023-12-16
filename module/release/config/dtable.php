<?php
global $lang, $app;
$app->loadLang('story');
$app->loadLang('bug');
$app->loadLang('build');

$config->release->dtable = new stdclass();
$config->release->dtable->story   = new stdclass();
$config->release->dtable->bug     = new stdclass();
$config->release->dtable->leftBug = new stdclass();

$config->release->dtable->defaultFields['linkStory'] = array('id', 'title', 'pri', 'status', 'linkedBuild', 'estimate', 'stage');
$config->release->dtable->defaultFields['linkBug']   = array('id', 'title', 'openedBy', 'resolvedBy', 'status');

$config->release->dtable->fieldList['id']['title'] = $lang->idAB;
$config->release->dtable->fieldList['id']['name']  = 'id';
$config->release->dtable->fieldList['id']['type']  = 'id';

$config->release->dtable->fieldList['name']['title'] = $lang->release->name;
$config->release->dtable->fieldList['name']['name']  = 'name';
$config->release->dtable->fieldList['name']['link']  = array('module' => 'release', 'method' => 'view', 'params' => 'releaseID={id}');
$config->release->dtable->fieldList['name']['type']  = 'title';

$config->release->dtable->fieldList['branch']['title']    = $lang->release->branch;
$config->release->dtable->fieldList['branch']['name']     = 'branchName';
$config->release->dtable->fieldList['branch']['type']     = 'text';
$config->release->dtable->fieldList['branch']['sortType'] = true;

$config->release->dtable->fieldList['project']['title'] = $lang->release->project;
$config->release->dtable->fieldList['project']['name']  = 'project';
$config->release->dtable->fieldList['project']['type']  = 'text';
$config->release->dtable->fieldList['project']['group'] = 1;

$config->release->dtable->fieldList['build']['title'] = $lang->release->includedBuild;
$config->release->dtable->fieldList['build']['name']  = 'build';
$config->release->dtable->fieldList['build']['type']  = 'desc';
$config->release->dtable->fieldList['build']['group'] = 1;

$config->release->dtable->fieldList['status']['title']     = $lang->release->status;
$config->release->dtable->fieldList['status']['name']      = 'status';
$config->release->dtable->fieldList['status']['type']      = 'status';
$config->release->dtable->fieldList['status']['statusMap'] = $lang->release->statusList;

$config->release->dtable->fieldList['date']['title'] = $lang->release->date;
$config->release->dtable->fieldList['date']['name']  = 'date';
$config->release->dtable->fieldList['date']['type']  = 'date';

$config->release->dtable->fieldList['actions']['title'] = $lang->actions;
$config->release->dtable->fieldList['actions']['name']  = 'actions';
$config->release->dtable->fieldList['actions']['type']  = 'actions';
$config->release->dtable->fieldList['actions']['list']  = $config->release->actionList;
$config->release->dtable->fieldList['actions']['menu']  = array('linkStory', 'linkBug', 'play|pause', 'edit', 'notify', 'delete');

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

$config->release->dtable->story->fieldList['pri']['title'] = $lang->priAB;
$config->release->dtable->story->fieldList['pri']['name']  = 'pri';
$config->release->dtable->story->fieldList['pri']['type']  = 'pri';

$config->release->dtable->story->fieldList['status']['title']     = $lang->statusAB;
$config->release->dtable->story->fieldList['status']['name']      = 'status';
$config->release->dtable->story->fieldList['status']['type']      = 'status';
$config->release->dtable->story->fieldList['status']['statusMap'] = $lang->story->statusList;

$config->release->dtable->story->fieldList['linkedBuild']['title'] = $lang->build->linkedBuild;
$config->release->dtable->story->fieldList['linkedBuild']['name']  = 'linkedBuild';
$config->release->dtable->story->fieldList['linkedBuild']['type']  = 'text';

$config->release->dtable->story->fieldList['openedBy']['title'] = $lang->openedByAB;
$config->release->dtable->story->fieldList['openedBy']['name']  = 'openedBy';
$config->release->dtable->story->fieldList['openedBy']['type']  = 'user';

$config->release->dtable->story->fieldList['estimate']['title'] = $lang->story->estimateAB;
$config->release->dtable->story->fieldList['estimate']['name']  = 'estimate';
$config->release->dtable->story->fieldList['estimate']['type']  = 'number';

$config->release->dtable->story->fieldList['stage']['title'] = $lang->story->stageAB;
$config->release->dtable->story->fieldList['stage']['name']  = 'stage';
$config->release->dtable->story->fieldList['stage']['type']  = 'category';
$config->release->dtable->story->fieldList['stage']['map']   = $lang->story->stageList;

$config->release->dtable->story->fieldList['actions']['title']    = $lang->actions;
$config->release->dtable->story->fieldList['actions']['name']     = 'actions';
$config->release->dtable->story->fieldList['actions']['type']     = 'actions';
$config->release->dtable->story->fieldList['actions']['minWidth'] = 60;
$config->release->dtable->story->fieldList['actions']['menu']     = array('unlinkStory');
$config->release->dtable->story->fieldList['actions']['list']     = $config->release->actionList;

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

$config->release->dtable->bug->fieldList['severity']['title'] = $lang->bug->severity;
$config->release->dtable->bug->fieldList['severity']['name']  = 'severity';
$config->release->dtable->bug->fieldList['severity']['type']  = 'severity';

$config->release->dtable->bug->fieldList['pri']['title'] = $lang->priAB;
$config->release->dtable->bug->fieldList['pri']['name']  = 'pri';
$config->release->dtable->bug->fieldList['pri']['type']  = 'pri';

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

$config->release->dtable->bug->fieldList['resolvedDate']['title'] = $lang->bug->abbr->resolvedDate;
$config->release->dtable->bug->fieldList['resolvedDate']['name']  = 'resolvedDate';
$config->release->dtable->bug->fieldList['resolvedDate']['type']  = 'date';

$config->release->dtable->bug->fieldList['actions']['title']    = $lang->actions;
$config->release->dtable->bug->fieldList['actions']['name']     = 'actions';
$config->release->dtable->bug->fieldList['actions']['type']     = 'actions';
$config->release->dtable->bug->fieldList['actions']['minWidth'] = 60;
$config->release->dtable->bug->fieldList['actions']['menu']     = array('unlinkBug');
$config->release->dtable->bug->fieldList['actions']['list']     = $config->release->actionList;

$config->release->dtable->leftBug = clone $config->release->dtable->bug;
$config->release->dtable->leftBug->fieldList['severity']['name'] = 'severityOrder';
$config->release->dtable->leftBug->fieldList['actions']['menu']  = array('unlinkLeftBug');
