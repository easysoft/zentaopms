<?php
global $lang, $app;
$app->loadLang('story');
$app->loadLang('bug');
$app->loadLang('build');
$isEn = $app->getClientLang() == 'en';

$config->release->dtable = new stdclass();
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

$config->release->story = new stdclass();
$config->release->story->dtable = new stdclass();
$config->release->story->dtable->fieldList['id']['title']    = $lang->idAB;
$config->release->story->dtable->fieldList['id']['name']     = 'id';
$config->release->story->dtable->fieldList['id']['type']     = 'checkID';
$config->release->story->dtable->fieldList['id']['sortType'] = 'desc';
$config->release->story->dtable->fieldList['id']['checkbox'] = true;

$config->release->story->dtable->fieldList['title']['title']       = $lang->story->title;
$config->release->story->dtable->fieldList['title']['name']        = 'title';
$config->release->story->dtable->fieldList['title']['type']        = 'title';
$config->release->story->dtable->fieldList['title']['link']        = helper::createLink('story', 'view', 'storyID={id}');
$config->release->story->dtable->fieldList['title']['data-toggle'] = 'modal';
$config->release->story->dtable->fieldList['title']['data-size']   = 'lg';
$config->release->story->dtable->fieldList['title']['data-app']    = $app->tab;

$config->release->story->dtable->fieldList['pri']['title']   = $lang->priAB;
$config->release->story->dtable->fieldList['pri']['name']    = 'pri';
$config->release->story->dtable->fieldList['pri']['type']    = 'pri';
$config->release->story->dtable->fieldList['pri']['priList'] = $lang->story->priList;
$config->release->story->dtable->fieldList['pri']['show']    = true;

$config->release->story->dtable->fieldList['category']['name']     = 'category';
$config->release->story->dtable->fieldList['category']['title']    = $lang->story->category;
$config->release->story->dtable->fieldList['category']['sortType'] = true;
$config->release->story->dtable->fieldList['category']['type']     = 'category';
$config->release->story->dtable->fieldList['category']['map']      = $lang->story->categoryList;

$config->release->story->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->release->story->dtable->fieldList['status']['name']      = 'status';
$config->release->story->dtable->fieldList['status']['type']      = 'status';
$config->release->story->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;
$config->release->story->dtable->fieldList['status']['show']      = true;

$config->release->story->dtable->fieldList['openedBy']['title'] = $lang->openedByAB;
$config->release->story->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->release->story->dtable->fieldList['openedBy']['type']  = 'user';
$config->release->story->dtable->fieldList['openedBy']['show']  = true;

$config->release->story->dtable->fieldList['openedDate']['name']     = 'openedDate';
$config->release->story->dtable->fieldList['openedDate']['title']    = $lang->story->openedDate;
$config->release->story->dtable->fieldList['openedDate']['sortType'] = true;
$config->release->story->dtable->fieldList['openedDate']['type']     = 'date';

$config->release->story->dtable->fieldList['linkedBuild']['title'] = $lang->build->linkedBuild;
$config->release->story->dtable->fieldList['linkedBuild']['name']  = 'buildName';
$config->release->story->dtable->fieldList['linkedBuild']['type']  = 'text';
$config->release->story->dtable->fieldList['linkedBuild']['show']  = true;

$config->release->story->dtable->fieldList['estimate']['title'] = $lang->story->estimateAB;
$config->release->story->dtable->fieldList['estimate']['name']  = 'estimate';
$config->release->story->dtable->fieldList['estimate']['type']  = 'number';
$config->release->story->dtable->fieldList['estimate']['show']  = true;
if($isEn) $config->release->story->dtable->fieldList['estimate']['width'] = 100;

$config->release->story->dtable->fieldList['stage']['title'] = $lang->story->stageAB;
$config->release->story->dtable->fieldList['stage']['name']  = 'stage';
$config->release->story->dtable->fieldList['stage']['type']  = 'category';
$config->release->story->dtable->fieldList['stage']['map']   = $lang->story->stageList;
$config->release->story->dtable->fieldList['stage']['show']  = true;
if($isEn) $config->release->story->dtable->fieldList['stage']['width'] = 100;

$config->release->story->dtable->fieldList['assignedTo']['name']     = 'assignedTo';
$config->release->story->dtable->fieldList['assignedTo']['title']    = $lang->story->assignedTo;
$config->release->story->dtable->fieldList['assignedTo']['sortType'] = true;
$config->release->story->dtable->fieldList['assignedTo']['type']     = 'user';

$config->release->story->dtable->fieldList['assignedDate']['name']     = 'assignedDate';
$config->release->story->dtable->fieldList['assignedDate']['title']    = $lang->story->assignedDate;
$config->release->story->dtable->fieldList['assignedDate']['sortType'] = true;
$config->release->story->dtable->fieldList['assignedDate']['type']     = 'date';

$config->release->story->dtable->fieldList['closedBy']['name']     = 'closedBy';
$config->release->story->dtable->fieldList['closedBy']['title']    = $lang->story->closedBy;
$config->release->story->dtable->fieldList['closedBy']['sortType'] = true;
$config->release->story->dtable->fieldList['closedBy']['type']     = 'user';

$config->release->story->dtable->fieldList['closedDate']['name']     = 'closedDate';
$config->release->story->dtable->fieldList['closedDate']['title']    = $lang->story->closedDate;
$config->release->story->dtable->fieldList['closedDate']['sortType'] = true;
$config->release->story->dtable->fieldList['closedDate']['type']     = 'date';

$config->release->story->dtable->fieldList['closedReason']['name']       = 'closedReason';
$config->release->story->dtable->fieldList['closedReason']['title']      = $lang->story->closedReason;
$config->release->story->dtable->fieldList['closedReason']['sortType']   = true;
$config->release->story->dtable->fieldList['closedReason']['width']      = '90';
$config->release->story->dtable->fieldList['closedReason']['map']        = $lang->story->reasonList;

$config->release->story->dtable->fieldList['lastEditedBy']['name']     = 'lastEditedBy';
$config->release->story->dtable->fieldList['lastEditedBy']['title']    = $lang->story->lastEditedBy;
$config->release->story->dtable->fieldList['lastEditedBy']['sortType'] = true;
$config->release->story->dtable->fieldList['lastEditedBy']['type']     = 'user';

$config->release->story->dtable->fieldList['lastEditedDate']['name']     = 'lastEditedDate';
$config->release->story->dtable->fieldList['lastEditedDate']['title']    = $lang->story->lastEditedDate;
$config->release->story->dtable->fieldList['lastEditedDate']['sortType'] = true;
$config->release->story->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->release->story->dtable->fieldList['lastEditedDate']['width']    = 120;

$config->release->story->dtable->fieldList['keywords']['name']     = 'keywords';
$config->release->story->dtable->fieldList['keywords']['title']    = $lang->story->keywords;
$config->release->story->dtable->fieldList['keywords']['sortType'] = true;
$config->release->story->dtable->fieldList['keywords']['width']    = '100';

$config->release->story->dtable->fieldList['source']['name']     = 'source';
$config->release->story->dtable->fieldList['source']['title']    = $lang->story->source;
$config->release->story->dtable->fieldList['source']['sortType'] = true;
$config->release->story->dtable->fieldList['source']['width']    = '90';
$config->release->story->dtable->fieldList['source']['group']    = 10;

$config->release->story->dtable->fieldList['sourceNote']['name']     = 'sourceNote';
$config->release->story->dtable->fieldList['sourceNote']['title']    = $lang->story->sourceNote;
$config->release->story->dtable->fieldList['sourceNote']['width']    = '90';
$config->release->story->dtable->fieldList['sourceNote']['sortType'] = true;
$config->release->story->dtable->fieldList['sourceNote']['group']    = 10;

$config->release->story->dtable->fieldList['activatedDate']['name']     = 'activatedDate';
$config->release->story->dtable->fieldList['activatedDate']['title']    = $lang->story->activatedDate;
$config->release->story->dtable->fieldList['activatedDate']['sortType'] = true;
$config->release->story->dtable->fieldList['activatedDate']['type']     = 'date';
$config->release->story->dtable->fieldList['activatedDate']['group']    = 11;

$config->release->story->dtable->fieldList['version']['name']     = 'version';
$config->release->story->dtable->fieldList['version']['title']    = $lang->story->version;
$config->release->story->dtable->fieldList['version']['type']     = 'number';
$config->release->story->dtable->fieldList['version']['sortType'] = true;
$config->release->story->dtable->fieldList['version']['group']    = 11;

$config->release->story->dtable->fieldList['actions']['title']    = $lang->actions;
$config->release->story->dtable->fieldList['actions']['name']     = 'actions';
$config->release->story->dtable->fieldList['actions']['type']     = 'actions';
$config->release->story->dtable->fieldList['actions']['width']    = 100;
$config->release->story->dtable->fieldList['actions']['menu']     = array('unlinkStory');
$config->release->story->dtable->fieldList['actions']['list']     = $config->release->actionList;

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
$config->release->dtable->bug->fieldList['severity']['show']         = true;

$config->release->dtable->bug->fieldList['pri']['title']   = $lang->priAB;
$config->release->dtable->bug->fieldList['pri']['name']    = 'pri';
$config->release->dtable->bug->fieldList['pri']['type']    = 'pri';
$config->release->dtable->bug->fieldList['pri']['priList'] = $lang->bug->priList;
$config->release->dtable->bug->fieldList['pri']['show']    = true;

$config->release->dtable->bug->fieldList['module']['title'] = $lang->bug->module;
$config->release->dtable->bug->fieldList['module']['name']  = 'module';
$config->release->dtable->bug->fieldList['module']['type']  = 'category';

$config->release->dtable->bug->fieldList['status']['title']     = $lang->statusAB;
$config->release->dtable->bug->fieldList['status']['name']      = 'status';
$config->release->dtable->bug->fieldList['status']['type']      = 'status';
$config->release->dtable->bug->fieldList['status']['statusMap'] = $lang->bug->statusList;
$config->release->dtable->bug->fieldList['status']['show']      = true;

$config->release->dtable->bug->fieldList['resolvedBuild']['title'] = $lang->bug->resolvedBuild;
$config->release->dtable->bug->fieldList['resolvedBuild']['name']  = 'resolvedBuild';
$config->release->dtable->bug->fieldList['resolvedBuild']['type']  = 'text';
$config->release->dtable->bug->fieldList['resolvedBuild']['show']  = true;

$config->release->dtable->bug->fieldList['openedBy']['title'] = $lang->openedByAB;
$config->release->dtable->bug->fieldList['openedBy']['name']  = 'openedBy';
$config->release->dtable->bug->fieldList['openedBy']['type']  = 'user';
$config->release->dtable->bug->fieldList['openedBy']['show']  = true;

$config->release->dtable->bug->fieldList['openedDate']['title'] = $lang->bug->abbr->openedDate;
$config->release->dtable->bug->fieldList['openedDate']['name']  = 'openedDate';
$config->release->dtable->bug->fieldList['openedDate']['type']  = 'date';
$config->release->dtable->bug->fieldList['openedDate']['show']  = true;

$config->release->dtable->bug->fieldList['resolvedBy']['title'] = $lang->bug->resolvedBy;
$config->release->dtable->bug->fieldList['resolvedBy']['name']  = 'resolvedBy';
$config->release->dtable->bug->fieldList['resolvedBy']['type']  = 'user';
$config->release->dtable->bug->fieldList['resolvedBy']['show']  = true;
if($isEn) $config->release->dtable->bug->fieldList['resolvedBy']['width'] = 120;

$config->release->dtable->bug->fieldList['resolvedDate']['title'] = $lang->bug->abbr->resolvedDate;
$config->release->dtable->bug->fieldList['resolvedDate']['name']  = 'resolvedDate';
$config->release->dtable->bug->fieldList['resolvedDate']['type']  = 'date';
$config->release->dtable->bug->fieldList['resolvedDate']['show']  = true;

$config->release->dtable->bug->fieldList['actions']['title'] = $lang->actions;
$config->release->dtable->bug->fieldList['actions']['name']  = 'actions';
$config->release->dtable->bug->fieldList['actions']['type']  = 'actions';
$config->release->dtable->bug->fieldList['actions']['width'] = 100;
$config->release->dtable->bug->fieldList['actions']['menu']  = array('unlinkBug');
$config->release->dtable->bug->fieldList['actions']['list']  = $config->release->actionList;

$config->release->leftBug = new stdClass();
$config->release->leftBug->dtable = clone $config->release->dtable->bug;

$config->release->leftBug->dtable->fieldList['openedBuild']['title']    = $lang->bug->openedBuild;
$config->release->leftBug->dtable->fieldList['openedBuild']['name']     = 'openedBuild';
$config->release->leftBug->dtable->fieldList['openedBuild']['type']     = 'text';
$config->release->leftBug->dtable->fieldList['openedBuild']['sortType'] = true;

$config->release->leftBug->dtable->fieldList['severity']['name'] = 'severityOrder';
$config->release->leftBug->dtable->fieldList['actions']['menu']  = array('unlinkLeftBug');
unset($config->release->leftBug->dtable->fieldList['resolvedBuild']);
unset($config->release->leftBug->dtable->fieldList['resolvedBy']);
unset($config->release->leftBug->dtable->fieldList['resolvedDate']);

$config->release->dtable->escapedBug = clone $config->release->dtable->bug;
$config->release->dtable->escapedBug->fieldList['id']['type'] = 'id';
unset($config->release->dtable->escapedBug->fieldList['actions']);
