<?php
global $lang, $app;
$app->loadLang('bug');
$app->loadLang('story');
$config->build->dtable = new stdclass();

$config->build->dtable->fieldList['id']['title'] = $lang->idAB;
$config->build->dtable->fieldList['id']['name']  = 'id';
$config->build->dtable->fieldList['id']['type']  = 'id';

$config->build->dtable->fieldList['name']['title']    = $lang->build->nameAB;
$config->build->dtable->fieldList['name']['name']     = 'name';
$config->build->dtable->fieldList['name']['link']     = array('module' => 'build', 'method' => 'view', 'params' => 'buildID={id}');
$config->build->dtable->fieldList['name']['type']     = 'title';
$config->build->dtable->fieldList['name']['sortType'] = false;

$config->build->dtable->fieldList['product']['title'] = $lang->build->product;
$config->build->dtable->fieldList['product']['name']  = 'productName';
$config->build->dtable->fieldList['product']['type']  = 'desc';
$config->build->dtable->fieldList['product']['group'] = 1;

$config->build->dtable->fieldList['branch']['title'] = $lang->build->branch;
$config->build->dtable->fieldList['branch']['name']  = 'branchName';
$config->build->dtable->fieldList['branch']['type']  = 'desc';
$config->build->dtable->fieldList['branch']['group'] = 1;

$config->build->dtable->fieldList['execution']['title'] = $lang->build->execution;
$config->build->dtable->fieldList['execution']['name']  = 'execution';
$config->build->dtable->fieldList['execution']['type']  = 'desc';
$config->build->dtable->fieldList['execution']['group'] = 1;

$config->build->dtable->fieldList['path']['title'] = $lang->build->url;
$config->build->dtable->fieldList['path']['name']  = 'path';
$config->build->dtable->fieldList['path']['type']  = 'desc';

$config->build->dtable->fieldList['builder']['title']    = $lang->build->builder;
$config->build->dtable->fieldList['builder']['name']     = 'builder';
$config->build->dtable->fieldList['builder']['type']     = 'user';
$config->build->dtable->fieldList['builder']['sortType'] = false;

$config->build->dtable->fieldList['date']['title']    = $lang->build->date;
$config->build->dtable->fieldList['date']['name']     = 'date';
$config->build->dtable->fieldList['date']['type']     = 'date';
$config->build->dtable->fieldList['date']['sortType'] = true;

$config->build->dtable->fieldList['actions']['title']      = $lang->actions;
$config->build->dtable->fieldList['actions']['name']       = 'actions';
$config->build->dtable->fieldList['actions']['type']       = 'actions';
$config->build->dtable->fieldList['actions']['actionsMap'] = $config->build->actionList;

$config->build->story                = new stdclass();
$config->build->bug                  = new stdclass();
$config->build->generatedBug         = new stdclass();

$config->build->story->dtable        = new stdclass();
$config->build->bug->dtable          = new stdclass();
$config->build->generatedBug->dtable = new stdclass();

$config->build->story->dtable->fieldList['id']['title']    = $lang->idAB;
$config->build->story->dtable->fieldList['id']['name']     = 'id';
$config->build->story->dtable->fieldList['id']['type']     = 'checkID';
$config->build->story->dtable->fieldList['id']['sortType'] = 'desc';
$config->build->story->dtable->fieldList['id']['checkbox'] = true;

$config->build->story->dtable->fieldList['title']['title']       = $lang->story->title;
$config->build->story->dtable->fieldList['title']['name']        = 'title';
$config->build->story->dtable->fieldList['title']['type']        = 'title';
$config->build->story->dtable->fieldList['title']['link']        = array('module' => 'story', 'method' => 'view', 'params' => 'storyID={id}');
$config->build->story->dtable->fieldList['title']['data-toggle'] = 'modal';
$config->build->story->dtable->fieldList['title']['data-size']   = 'lg';
$config->build->story->dtable->fieldList['title']['data-app']    = $app->tab;

$config->build->story->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->build->story->dtable->fieldList['pri']['name']  = 'pri';
$config->build->story->dtable->fieldList['pri']['type']  = 'pri';

$config->build->story->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->build->story->dtable->fieldList['status']['name']      = 'status';
$config->build->story->dtable->fieldList['status']['type']      = 'status';
$config->build->story->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;

$config->build->story->dtable->fieldList['openedBy']['title'] = $lang->openedByAB;
$config->build->story->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->build->story->dtable->fieldList['openedBy']['type']  = 'user';

$config->build->story->dtable->fieldList['estimate']['title'] = $lang->story->estimateAB;
$config->build->story->dtable->fieldList['estimate']['name']  = 'estimate';
$config->build->story->dtable->fieldList['estimate']['type']  = 'number';

$config->build->story->dtable->fieldList['stage']['title'] = $lang->story->stageAB;
$config->build->story->dtable->fieldList['stage']['name']  = 'stage';
$config->build->story->dtable->fieldList['stage']['type']  = 'category';
$config->build->story->dtable->fieldList['stage']['map']   = $lang->story->stageList;

$config->build->story->dtable->fieldList['actions']['title']    = $lang->actions;
$config->build->story->dtable->fieldList['actions']['name']     = 'actions';
$config->build->story->dtable->fieldList['actions']['type']     = 'actions';
$config->build->story->dtable->fieldList['actions']['menu']     = array('unlinkStory');
$config->build->story->dtable->fieldList['actions']['list']     = $config->build->actionList;
$config->build->story->dtable->fieldList['actions']['minWidth'] = '60';

$config->build->bug->dtable->fieldList['id']['title']    = $lang->idAB;
$config->build->bug->dtable->fieldList['id']['name']     = 'id';
$config->build->bug->dtable->fieldList['id']['type']     = 'checkID';
$config->build->bug->dtable->fieldList['id']['sortType'] = 'desc';

$config->build->bug->dtable->fieldList['title']['title']       = $lang->bug->title;
$config->build->bug->dtable->fieldList['title']['name']        = 'title';
$config->build->bug->dtable->fieldList['title']['type']        = 'title';
$config->build->bug->dtable->fieldList['title']['link']        = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}');
$config->build->bug->dtable->fieldList['title']['data-toggle'] = 'modal';
$config->build->bug->dtable->fieldList['title']['data-size']   = 'lg';
$config->build->bug->dtable->fieldList['title']['data-app']    = $app->tab;

$config->build->bug->dtable->fieldList['severity']['title'] = $lang->bug->severity;
$config->build->bug->dtable->fieldList['severity']['name']  = 'severity';
$config->build->bug->dtable->fieldList['severity']['type']  = 'severity';

$config->build->bug->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->build->bug->dtable->fieldList['pri']['name']  = 'pri';
$config->build->bug->dtable->fieldList['pri']['type']  = 'pri';

$config->build->bug->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->build->bug->dtable->fieldList['status']['name']      = 'status';
$config->build->bug->dtable->fieldList['status']['type']      = 'status';
$config->build->bug->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;

$config->build->bug->dtable->fieldList['openedBy']['title'] = $lang->openedByAB;
$config->build->bug->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->build->bug->dtable->fieldList['openedBy']['type']  = 'user';

$config->build->bug->dtable->fieldList['openedDate']['title'] = $lang->bug->abbr->openedDate;
$config->build->bug->dtable->fieldList['openedDate']['name']  = 'openedDate';
$config->build->bug->dtable->fieldList['openedDate']['type']  = 'date';

$config->build->bug->dtable->fieldList['resolvedBy']['title'] = $lang->bug->resolvedBy;
$config->build->bug->dtable->fieldList['resolvedBy']['name']  = 'resolvedBy';
$config->build->bug->dtable->fieldList['resolvedBy']['type']  = 'user';

$config->build->bug->dtable->fieldList['resolvedDate']['title'] = $lang->bug->abbr->resolvedDate;
$config->build->bug->dtable->fieldList['resolvedDate']['name']  = 'resolvedDate';
$config->build->bug->dtable->fieldList['resolvedDate']['type']  = 'date';

$config->build->bug->dtable->fieldList['actions']['title']    = $lang->actions;
$config->build->bug->dtable->fieldList['actions']['name']     = 'actions';
$config->build->bug->dtable->fieldList['actions']['type']     = 'actions';
$config->build->bug->dtable->fieldList['actions']['menu']     = array('unlinkBug');
$config->build->bug->dtable->fieldList['actions']['list']     = $config->build->actionList;
$config->build->bug->dtable->fieldList['actions']['minWidth'] = '60';

$config->build->generatedBug->dtable = clone $config->build->bug->dtable;
$config->build->generatedBug->dtable->fieldList['id']['checkbox'] = false;
unset($config->build->generatedBug->dtable->fieldList['actions']);

$config->build->defaultFields['linkStory'] = array('id', 'pri', 'title', 'openedBy', 'assignedTo', 'estimate', 'status', 'stage');
$config->build->defaultFields['linkBug']   = array('id', 'pri', 'title', 'openedBy', 'resolvedBy', 'status');
