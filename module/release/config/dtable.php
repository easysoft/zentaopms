<?php
global $lang, $app;
$app->loadLang('story');
$app->loadLang('build');

$config->release->dtable = new stdclass();
$config->release->dtable->story   = new stdclass();
$config->release->dtable->bug     = new stdclass();
$config->release->dtable->leftBug = new stdclass();

$config->release->dtable->fieldList['id']['title'] = $lang->idAB;
$config->release->dtable->fieldList['id']['name']  = 'id';
$config->release->dtable->fieldList['id']['type']  = 'id';

$config->release->dtable->fieldList['name']['title'] = $lang->release->name;
$config->release->dtable->fieldList['name']['name']  = 'name';
$config->release->dtable->fieldList['name']['link']  = helper::createLink('release', 'view', 'releaseID={id}');
$config->release->dtable->fieldList['name']['type']  = 'title';

$config->release->dtable->fieldList['branch']['title']    = $lang->release->branch;
$config->release->dtable->fieldList['branch']['name']     = 'branchName';
$config->release->dtable->fieldList['branch']['type']     = 'text';
$config->release->dtable->fieldList['branch']['sortType'] = true;

$config->release->dtable->fieldList['project']['title'] = $lang->release->project;
$config->release->dtable->fieldList['project']['name']  = 'projectName';
$config->release->dtable->fieldList['project']['type']  = 'text';

$config->release->dtable->fieldList['build']['title'] = $lang->release->includedBuild;
$config->release->dtable->fieldList['build']['name']  = 'build';
$config->release->dtable->fieldList['build']['type']  = 'desc';

$config->release->dtable->fieldList['status']['title']     = $lang->release->status;
$config->release->dtable->fieldList['status']['name']      = 'status';
$config->release->dtable->fieldList['status']['type']      = 'status';
$config->release->dtable->fieldList['status']['statusMap'] = $lang->release->statusList;

$config->release->dtable->fieldList['date']['title'] = $lang->release->date;
$config->release->dtable->fieldList['date']['name']  = 'date';
$config->release->dtable->fieldList['date']['type']  = 'date';

$config->release->dtable->fieldList['actions']['title']      = $lang->actions;
$config->release->dtable->fieldList['actions']['name']       = 'actions';
$config->release->dtable->fieldList['actions']['type']       = 'actions';
$config->release->dtable->fieldList['actions']['actionsMap'] = $config->release->actionList;

$config->release->dtable->story->fieldList['id']['title']    = $lang->idAB;
$config->release->dtable->story->fieldList['id']['name']     = 'id';
$config->release->dtable->story->fieldList['id']['type']     = 'checkID';
$config->release->dtable->story->fieldList['id']['sortType'] = 'desc';
$config->release->dtable->story->fieldList['id']['checkbox'] = true;

$config->release->dtable->story->fieldList['title']['title']       = $lang->story->title;
$config->release->dtable->story->fieldList['title']['name']        = 'title';
$config->release->dtable->story->fieldList['title']['type']        = 'title';
$config->release->dtable->story->fieldList['title']['link']        = helper::createLink('story', 'view', 'storyID={id}', '', true);
$config->release->dtable->story->fieldList['title']['data-toggle'] = 'modal';

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

$config->release->dtable->story->fieldList['actions']['title'] = $lang->actions;
$config->release->dtable->story->fieldList['actions']['name']  = 'actions';
$config->release->dtable->story->fieldList['actions']['type']  = 'actions';
$config->release->dtable->story->fieldList['actions']['menu']  = array('unlinkStory');

$config->release->dtable->story->fieldList['actions']['list']['unlinkStory']['icon'] = 'unlink';
$config->release->dtable->story->fieldList['actions']['list']['unlinkStory']['hint'] = $lang->release->unlinkStory;
$config->release->dtable->story->fieldList['actions']['list']['unlinkStory']['url']  = 'javascript: unlinkStory("{id}")';
