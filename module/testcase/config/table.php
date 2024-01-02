<?php
global $lang, $app;
$config->testcase->dtable = new stdclass();
$config->testcase->dtable->fieldList['id']['title']    = $lang->idAB;
$config->testcase->dtable->fieldList['id']['name']     = 'caseID';
$config->testcase->dtable->fieldList['id']['type']     = 'checkID';
$config->testcase->dtable->fieldList['id']['fixed']    = 'left';
$config->testcase->dtable->fieldList['id']['sortType'] = true;
$config->testcase->dtable->fieldList['id']['required'] = true;
$config->testcase->dtable->fieldList['id']['group']    = 1;

$config->testcase->dtable->fieldList['title']['title']        = $lang->testcase->title;
$config->testcase->dtable->fieldList['title']['type']         = 'title';
$config->testcase->dtable->fieldList['title']['fixed']        = 'left';
$config->testcase->dtable->fieldList['title']['sortType']     = true;
$config->testcase->dtable->fieldList['title']['nestedToggle'] = true;
$config->testcase->dtable->fieldList['title']['link']         = array('module' => 'testcase', 'method' => 'view', 'params' => "caseID={caseID}");
$config->testcase->dtable->fieldList['title']['required']     = true;
$config->testcase->dtable->fieldList['title']['group']        = 1;
$config->testcase->dtable->fieldList['title']['data-app']     = $app->tab;

$config->testcase->dtable->fieldList['branch']['title']      = $lang->testcase->branch;
$config->testcase->dtable->fieldList['branch']['type']       = 'text';
$config->testcase->dtable->fieldList['branch']['group']      = 2;
$config->testcase->dtable->fieldList['branch']['dataSource'] = array('module' => 'branch', 'method' => 'getPairs', 'params' => ['productID' => (int)'$productID']);

$config->testcase->dtable->fieldList['pri']['title']    = $lang->testcase->pri;
$config->testcase->dtable->fieldList['pri']['type']     = 'pri';
$config->testcase->dtable->fieldList['pri']['sortType'] = true;
$config->testcase->dtable->fieldList['pri']['show']     = true;
$config->testcase->dtable->fieldList['pri']['group']    = 2;

$config->testcase->dtable->fieldList['scene']['title']    = $lang->testcase->scene;
$config->testcase->dtable->fieldList['scene']['type']     = 'category';
$config->testcase->dtable->fieldList['scene']['map']      = $lang->testcase->typeList;
$config->testcase->dtable->fieldList['scene']['group']    = 2;
$config->testcase->dtable->fieldList['scene']['sortType'] = true;

$config->testcase->dtable->fieldList['type']['title']    = $lang->testcase->type;
$config->testcase->dtable->fieldList['type']['type']     = 'category';
$config->testcase->dtable->fieldList['type']['map']      = $lang->testcase->typeList;
$config->testcase->dtable->fieldList['type']['group']    = 2;
$config->testcase->dtable->fieldList['type']['sortType'] = true;

$config->testcase->dtable->fieldList['status']['title']     = $lang->testcase->status;
$config->testcase->dtable->fieldList['status']['type']      = 'status';
$config->testcase->dtable->fieldList['status']['statusMap'] = $lang->testcase->statusList;
$config->testcase->dtable->fieldList['status']['group']     = 2;
$config->testcase->dtable->fieldList['status']['sortType']  = true;

$config->testcase->dtable->fieldList['stage']['title']    = $lang->testcase->stage;
$config->testcase->dtable->fieldList['stage']['type']     = 'text';
$config->testcase->dtable->fieldList['stage']['group']    = 2;
$config->testcase->dtable->fieldList['stage']['sortType'] = true;

$config->testcase->dtable->fieldList['precondition']['title']    = $lang->testcase->precondition;
$config->testcase->dtable->fieldList['precondition']['type']     = 'desc';
$config->testcase->dtable->fieldList['precondition']['group']    = 3;
$config->testcase->dtable->fieldList['precondition']['sortType'] = true;

$config->testcase->dtable->fieldList['story']['title']      = $lang->testcase->story;
$config->testcase->dtable->fieldList['story']['type']       = 'desc';
$config->testcase->dtable->fieldList['story']['link']       = array('module' => 'story', 'method' => 'view', 'params' => "storyID={story}");
$config->testcase->dtable->fieldList['story']['group']      = 3;
$config->testcase->dtable->fieldList['story']['control']    = 'select';
$config->testcase->dtable->fieldList['story']['dataSource'] = array('module' => 'story', 'method' => 'getProductStoryPairs', 'params' => ['productIdList' => (int)'$productID', 'branch' => '$branch']);

$config->testcase->dtable->fieldList['keywords']['title']    = $lang->testcase->keywords;
$config->testcase->dtable->fieldList['keywords']['type']     = 'text';
$config->testcase->dtable->fieldList['keywords']['group']    = 3;
$config->testcase->dtable->fieldList['keywords']['sortType'] = true;

$config->testcase->dtable->fieldList['openedBy']['title']    = $lang->testcase->openedByAB;
$config->testcase->dtable->fieldList['openedBy']['type']     = 'user';
$config->testcase->dtable->fieldList['openedBy']['show']     = true;
$config->testcase->dtable->fieldList['openedBy']['group']    = 4;
$config->testcase->dtable->fieldList['openedBy']['sortType'] = true;

$config->testcase->dtable->fieldList['openedDate']['title']    = $lang->testcase->openedDate;
$config->testcase->dtable->fieldList['openedDate']['type']     = 'date';
$config->testcase->dtable->fieldList['openedDate']['group']    = 4;
$config->testcase->dtable->fieldList['openedDate']['sortType'] = true;

$config->testcase->dtable->fieldList['reviewedBy']['title']    = $lang->testcase->reviewedByAB;
$config->testcase->dtable->fieldList['reviewedBy']['type']     = 'user';
$config->testcase->dtable->fieldList['reviewedBy']['group']    = 4;
$config->testcase->dtable->fieldList['reviewedBy']['sortType'] = true;

$config->testcase->dtable->fieldList['reviewedDate']['title']    = $lang->testcase->reviewedDate;
$config->testcase->dtable->fieldList['reviewedDate']['type']     = 'datetime';
$config->testcase->dtable->fieldList['reviewedDate']['group']    = 4;
$config->testcase->dtable->fieldList['reviewedDate']['sortType'] = true;

$config->testcase->dtable->fieldList['lastRunner']['title']   = $lang->testcase->lastRunner;
$config->testcase->dtable->fieldList['lastRunner']['type']    = 'user';
$config->testcase->dtable->fieldList['lastRunner']['show']    = true;
$config->testcase->dtable->fieldList['lastRunner']['group']   = 4;
$config->testcase->dtable->fieldList['lastRunner']['sortType'] = true;

$config->testcase->dtable->fieldList['lastRunDate']['title']    = $lang->testcase->lastRunDate;
$config->testcase->dtable->fieldList['lastRunDate']['type']     = 'datetime';
$config->testcase->dtable->fieldList['lastRunDate']['sortType'] = true;
$config->testcase->dtable->fieldList['lastRunDate']['show']     = true;
$config->testcase->dtable->fieldList['lastRunDate']['group']    = 4;

$config->testcase->dtable->fieldList['lastRunResult']['title']     = $lang->testcase->lastRunResult;
$config->testcase->dtable->fieldList['lastRunResult']['type']      = 'status';
$config->testcase->dtable->fieldList['lastRunResult']['statusMap'] = $lang->testcase->resultList;
$config->testcase->dtable->fieldList['lastRunResult']['show']      = true;
$config->testcase->dtable->fieldList['lastRunResult']['group']     = 4;
$config->testcase->dtable->fieldList['lastRunResult']['sortType']  = true;

$config->testcase->dtable->fieldList['bugs']['title']       = $lang->testcase->bugsAB;
$config->testcase->dtable->fieldList['bugs']['link']        = array('module' => 'testcase', 'method' => 'bugs', 'params' => "runID=0&caseID={caseID}");
$config->testcase->dtable->fieldList['bugs']['type']        = 'number';
$config->testcase->dtable->fieldList['bugs']['data-toggle'] = 'modal';
$config->testcase->dtable->fieldList['bugs']['data-size']   = 'lg';
$config->testcase->dtable->fieldList['bugs']['group']       = 5;

$config->testcase->dtable->fieldList['results']['title']      = $lang->testcase->resultsAB;
$config->testcase->dtable->fieldList['results']['type']       = 'number';
$config->testcase->dtable->fieldList['results']['group']      = 5;
$config->testcase->dtable->fieldList['results']['sortType']   = true;
$config->testcase->dtable->fieldList['results']['dataSource'] = array('lang' => 'resultList');

$config->testcase->dtable->fieldList['stepNumber']['title']    = $lang->testcase->stepNumberAB;
$config->testcase->dtable->fieldList['stepNumber']['type']     = 'number';
$config->testcase->dtable->fieldList['stepNumber']['group']    = 5;
$config->testcase->dtable->fieldList['stepNumber']['sortType'] = true;

$config->testcase->dtable->fieldList['version']['title']    = $lang->testcase->version;
$config->testcase->dtable->fieldList['version']['type']     = 'text';
$config->testcase->dtable->fieldList['version']['group']    = 5;
$config->testcase->dtable->fieldList['version']['sortType'] = false;

$config->testcase->dtable->fieldList['lastEditedBy']['title']    = $lang->testcase->lastEditedBy;
$config->testcase->dtable->fieldList['lastEditedBy']['type']     = 'user';
$config->testcase->dtable->fieldList['lastEditedBy']['group']    = 6;
$config->testcase->dtable->fieldList['lastEditedBy']['sortType'] = true;

$config->testcase->dtable->fieldList['lastEditedDate']['title']    = $lang->testcase->lastEditedDate;
$config->testcase->dtable->fieldList['lastEditedDate']['type']     = 'date';
$config->testcase->dtable->fieldList['lastEditedDate']['group']    = 6;
$config->testcase->dtable->fieldList['lastEditedDate']['sortType'] = true;

$config->testcase->dtable->fieldList['product']['title']      = 'product';
$config->testcase->dtable->fieldList['product']['control']    = 'hidden';
$config->testcase->dtable->fieldList['product']['dataSource'] = array('module' => 'product', 'method' => 'getPairs', 'params' => ['mode' => '', 'programID' => 0, 'append' => '', 'shadow' => 'all']);
$config->testcase->dtable->fieldList['product']['display']    = false;

$config->testcase->dtable->fieldList['module']['control']    = 'select';
$config->testcase->dtable->fieldList['module']['dataSource'] = array('module' => 'testcase', 'method' => 'getDatatableModules', 'params' => ['productID' => (int)'$productID']);
$config->testcase->dtable->fieldList['module']['display']    = false;

$config->testcase->dtable->fieldList['actions']['title']    = $lang->actions;
$config->testcase->dtable->fieldList['actions']['type']     = 'actions';
$config->testcase->dtable->fieldList['actions']['list']     = $config->testcase->actionList;
$config->testcase->dtable->fieldList['actions']['menu']     = array();
$config->testcase->dtable->fieldList['actions']['width']    = '140';
$config->testcase->dtable->fieldList['actions']['required'] = true;
$config->testcase->dtable->fieldList['actions']['group']    = 7;

$config->testcase->group = new stdclass();
$config->testcase->group->dtable = new stdclass();
$config->testcase->group->dtable->fieldList['storyTitle']['title']    = $lang->testcase->story;
$config->testcase->group->dtable->fieldList['storyTitle']['width']    = 'auto';
$config->testcase->group->dtable->fieldList['storyTitle']['type']     = 'title';
$config->testcase->group->dtable->fieldList['storyTitle']['fixed']    = false;
$config->testcase->group->dtable->fieldList['storyTitle']['sortType'] = true;
$config->testcase->group->dtable->fieldList['storyTitle']['group']    = 'story';

$config->testcase->group->dtable->fieldList['id'] = $config->testcase->dtable->fieldList['id'];
$config->testcase->group->dtable->fieldList['id']['type']  = 'id';
$config->testcase->group->dtable->fieldList['id']['fixed'] = false;

$config->testcase->group->dtable->fieldList['title']  = $config->testcase->dtable->fieldList['title'];
$config->testcase->group->dtable->fieldList['title']['width']        = 'auto';
$config->testcase->group->dtable->fieldList['title']['nestedToggle'] = false;
$config->testcase->group->dtable->fieldList['title']['fixed']        = false;

$config->testcase->group->dtable->fieldList['pri']           = $config->testcase->dtable->fieldList['pri'];
$config->testcase->group->dtable->fieldList['status']        = $config->testcase->dtable->fieldList['status'];
$config->testcase->group->dtable->fieldList['lastRunResult'] = $config->testcase->dtable->fieldList['lastRunResult'];
$config->testcase->group->dtable->fieldList['type']          = $config->testcase->dtable->fieldList['type'];
$config->testcase->group->dtable->fieldList['bugs']          = $config->testcase->dtable->fieldList['bugs'];
$config->testcase->group->dtable->fieldList['results']       = $config->testcase->dtable->fieldList['results'];
$config->testcase->group->dtable->fieldList['stepNumber']    = $config->testcase->dtable->fieldList['stepNumber'];
$config->testcase->group->dtable->fieldList['lastRunner']    = $config->testcase->dtable->fieldList['lastRunner'];
$config->testcase->group->dtable->fieldList['lastRunDate']   = $config->testcase->dtable->fieldList['lastRunDate'];
$config->testcase->group->dtable->fieldList['actions']       = $config->testcase->dtable->fieldList['actions'];
$config->testcase->group->dtable->fieldList['actions']['fixed'] = false;
$config->testcase->group->dtable->fieldList['actions']['menu']  = array('edit', 'delete');

$config->testcase->bug = new stdclass();
$config->testcase->bug->dtable = new stdclass();
$config->testcase->bug->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testcase->bug->dtable->fieldList['id']['type']  = 'id';

$app->loadLang('bug');
$app->loadModuleConfig('bug');
$config->testcase->bug->dtable->fieldList['title']      = $config->bug->dtable->fieldList['title'];
$config->testcase->bug->dtable->fieldList['pri']        = $config->bug->dtable->fieldList['pri'];
$config->testcase->bug->dtable->fieldList['status']     = $config->bug->dtable->fieldList['status'];
$config->testcase->bug->dtable->fieldList['type']       = $config->bug->dtable->fieldList['type'];
$config->testcase->bug->dtable->fieldList['assignedTo'] = $config->bug->dtable->fieldList['assignedTo'];
$config->testcase->bug->dtable->fieldList['resolvedBy'] = $config->bug->dtable->fieldList['resolvedBy'];
$config->testcase->bug->dtable->fieldList['resolution'] = $config->bug->dtable->fieldList['resolution'];

$config->testcase->zerocase->dtable = new stdclass();
$config->testcase->zerocase->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testcase->zerocase->dtable->fieldList['id']['type']  = 'checkID';

$config->testcase->zerocase->dtable->fieldList['title']['title'] = $lang->story->title;
$config->testcase->zerocase->dtable->fieldList['title']['type']  = 'title';
$config->testcase->zerocase->dtable->fieldList['title']['link']  = array('module' => 'story', 'method' => 'view', "storyID={id}&version=0&param={param}");

$config->testcase->zerocase->dtable->fieldList['pri']['title'] = $lang->story->pri;
$config->testcase->zerocase->dtable->fieldList['pri']['type']  = 'pri';

$config->testcase->zerocase->dtable->fieldList['planTitle']['title'] = $lang->story->planAB;
$config->testcase->zerocase->dtable->fieldList['planTitle']['type']  = 'text';

$config->testcase->zerocase->dtable->fieldList['status']['title']     = $lang->story->status;
$config->testcase->zerocase->dtable->fieldList['status']['type']      = 'status';
$config->testcase->zerocase->dtable->fieldList['status']['statusMap'] = $lang->story->statusList;

$config->testcase->zerocase->dtable->fieldList['openedBy']['title'] = $lang->story->openedByAB;
$config->testcase->zerocase->dtable->fieldList['openedBy']['type']  = 'user';

$config->testcase->zerocase->dtable->fieldList['estimate']['title'] = $lang->story->estimate;
$config->testcase->zerocase->dtable->fieldList['estimate']['type']  = 'count';

$config->testcase->zerocase->dtable->fieldList['stage']['title'] = $lang->story->stage;
$config->testcase->zerocase->dtable->fieldList['stage']['type']  = 'category';
$config->testcase->zerocase->dtable->fieldList['stage']['map']   = $lang->story->stageList;

$config->testcase->zerocase->dtable->fieldList['assignedTo']['title'] = $lang->story->assignedTo;
$config->testcase->zerocase->dtable->fieldList['assignedTo']['type']  = 'assign';

$config->testcase->zerocase->dtable->fieldList['source']['title'] = $lang->story->source;
$config->testcase->zerocase->dtable->fieldList['source']['type']  = 'category';
$config->testcase->zerocase->dtable->fieldList['source']['map']   = $lang->story->sourceList;

$config->testcase->zerocase->dtable->fieldList['actions']['title']    = $lang->actions;
$config->testcase->zerocase->dtable->fieldList['actions']['type']     = 'actions';
$config->testcase->zerocase->dtable->fieldList['actions']['list']     = $config->testcase->zerocase->actionList;
$config->testcase->zerocase->dtable->fieldList['actions']['menu']     = array('change', 'review', 'close', 'edit', 'createcase');
$config->testcase->zerocase->dtable->fieldList['actions']['required'] = true;
$config->testcase->zerocase->dtable->fieldList['actions']['group']    = '7';
$config->testcase->zerocase->dtable->fieldList['actions']['sortType'] = false;

$config->testcase->importfromlib = new stdclass();
$config->testcase->importfromlib->dtable = new stdclass();
$config->testcase->importfromlib->dtable->fieldList['id']['name']  = 'id';
$config->testcase->importfromlib->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testcase->importfromlib->dtable->fieldList['id']['type']  = 'checkID';
$config->testcase->importfromlib->dtable->fieldList['id']['fixed'] = false;

$config->testcase->importfromlib->dtable->fieldList['branch']['name']    = 'branch';
$config->testcase->importfromlib->dtable->fieldList['branch']['title']   = $lang->testcase->branch;
$config->testcase->importfromlib->dtable->fieldList['branch']['type']    = 'control';
$config->testcase->importfromlib->dtable->fieldList['branch']['control'] = array('type' => 'picker', 'props' => array('required' => true));
$config->testcase->importfromlib->dtable->fieldList['branch']['width']   = '200px';

$config->testcase->importfromlib->dtable->fieldList['pri']['name']  = 'pri';
$config->testcase->importfromlib->dtable->fieldList['pri']['title'] = $lang->testcase->pri;
$config->testcase->importfromlib->dtable->fieldList['pri']['type']  = 'pri';

$config->testcase->importfromlib->dtable->fieldList['title']['name']        = 'title';
$config->testcase->importfromlib->dtable->fieldList['title']['title']       = $lang->testcase->title;
$config->testcase->importfromlib->dtable->fieldList['title']['type']        = 'title';
$config->testcase->importfromlib->dtable->fieldList['title']['fixed']       = false;
$config->testcase->importfromlib->dtable->fieldList['title']['link']        = array('module' => 'testcase', 'method' => 'view', 'params' => "caseID={id}");
$config->testcase->importfromlib->dtable->fieldList['title']['data-toggle'] = 'modal';

$config->testcase->importfromlib->dtable->fieldList['fromModule']['name']  = 'fromModule';
$config->testcase->importfromlib->dtable->fieldList['fromModule']['title'] = $lang->testcase->fromModule;
$config->testcase->importfromlib->dtable->fieldList['fromModule']['type']  = 'category';

$config->testcase->importfromlib->dtable->fieldList['module']['name']    = 'module';
$config->testcase->importfromlib->dtable->fieldList['module']['title']   = $lang->testcase->module;
$config->testcase->importfromlib->dtable->fieldList['module']['type']    = 'control';
$config->testcase->importfromlib->dtable->fieldList['module']['control'] = array('type' => 'picker', 'props' => "RAWJS<window.getModuleCellProps>RAWJS");
$config->testcase->importfromlib->dtable->fieldList['module']['width']   = '200px';

$config->testcase->importfromlib->dtable->fieldList['type']['name']  = 'type';
$config->testcase->importfromlib->dtable->fieldList['type']['title'] = $lang->testcase->type;
$config->testcase->importfromlib->dtable->fieldList['type']['type']  = 'status';
$config->testcase->importfromlib->dtable->fieldList['type']['statusMap'] = $lang->testcase->typeList;

$config->testcase->linkbugs = new stdclass();
$config->testcase->linkbugs->dtable = new stdclass();
$config->testcase->linkbugs->dtable->fieldList['id']['name']  = 'id';
$config->testcase->linkbugs->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testcase->linkbugs->dtable->fieldList['id']['type']  = 'checkID';
$config->testcase->linkbugs->dtable->fieldList['id']['fixed'] = false;

$config->testcase->linkbugs->dtable->fieldList['pri']['name']  = 'pri';
$config->testcase->linkbugs->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->testcase->linkbugs->dtable->fieldList['pri']['type']  = 'pri';

$config->testcase->linkbugs->dtable->fieldList['title']['name']  = 'title';
$config->testcase->linkbugs->dtable->fieldList['title']['title'] = $lang->bug->title;
$config->testcase->linkbugs->dtable->fieldList['title']['type']  = 'title';
$config->testcase->linkbugs->dtable->fieldList['title']['fixed'] = false;

$config->testcase->linkbugs->dtable->fieldList['type']['name']  = 'type';
$config->testcase->linkbugs->dtable->fieldList['type']['title'] = $lang->bug->type;
$config->testcase->linkbugs->dtable->fieldList['type']['type']  = 'category';
$config->testcase->linkbugs->dtable->fieldList['type']['map']   = $lang->bug->typeList;

$config->testcase->linkbugs->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->testcase->linkbugs->dtable->fieldList['openedBy']['title'] = $lang->openedByAB;
$config->testcase->linkbugs->dtable->fieldList['openedBy']['type']  = 'user';

$config->testcase->linkbugs->dtable->fieldList['status']['name']      = 'status';
$config->testcase->linkbugs->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->testcase->linkbugs->dtable->fieldList['status']['type']      = 'status';
$config->testcase->linkbugs->dtable->fieldList['status']['statusMap'] = $lang->bug->statusList;

$config->testcase->linkcases = new stdclass();
$config->testcase->linkcases->dtable = new stdclass();
$config->testcase->linkcases->dtable->fieldList['id']['name']  = 'id';
$config->testcase->linkcases->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testcase->linkcases->dtable->fieldList['id']['type']  = 'checkID';
$config->testcase->linkcases->dtable->fieldList['id']['fixed'] = false;

$config->testcase->linkcases->dtable->fieldList['pri']['name']  = 'pri';
$config->testcase->linkcases->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->testcase->linkcases->dtable->fieldList['pri']['type']  = 'pri';

$config->testcase->linkcases->dtable->fieldList['title']['name']  = 'title';
$config->testcase->linkcases->dtable->fieldList['title']['title'] = $lang->testcase->title;
$config->testcase->linkcases->dtable->fieldList['title']['type']  = 'title';
$config->testcase->linkcases->dtable->fieldList['title']['fixed'] = false;

$config->testcase->linkcases->dtable->fieldList['type']['name']  = 'type';
$config->testcase->linkcases->dtable->fieldList['type']['title'] = $lang->testcase->type;
$config->testcase->linkcases->dtable->fieldList['type']['type']  = 'category';
$config->testcase->linkcases->dtable->fieldList['type']['map']   = $lang->testcase->typeList;

$config->testcase->linkcases->dtable->fieldList['openedBy']['name']  = 'openedBy';
$config->testcase->linkcases->dtable->fieldList['openedBy']['title'] = $lang->openedByAB;
$config->testcase->linkcases->dtable->fieldList['openedBy']['type']  = 'user';

$config->testcase->linkcases->dtable->fieldList['status']['name']      = 'status';
$config->testcase->linkcases->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->testcase->linkcases->dtable->fieldList['status']['type']      = 'status';
$config->testcase->linkcases->dtable->fieldList['status']['statusMap'] = $lang->testcase->statusList;

$config->scene->dtable = new stdclass();
$config->scene->dtable->fieldList['id']['title']    = $lang->idAB;
$config->scene->dtable->fieldList['id']['type']     = 'checkID';
$config->scene->dtable->fieldList['id']['fixed']    = 'left';
$config->scene->dtable->fieldList['id']['sortType'] = true;
$config->scene->dtable->fieldList['id']['required'] = true;
$config->scene->dtable->fieldList['id']['group']    = 1;

$config->scene->dtable->fieldList['title']['title']        = $lang->testcase->sceneTitle;
$config->scene->dtable->fieldList['title']['type']         = 'title';
$config->scene->dtable->fieldList['title']['fixed']        = 'left';
$config->scene->dtable->fieldList['title']['nestedToggle'] = true;
$config->scene->dtable->fieldList['title']['required']     = true;
$config->scene->dtable->fieldList['title']['group']        = 1;

$config->scene->dtable->fieldList['openedBy']['title'] = $lang->testcase->openedByAB;
$config->scene->dtable->fieldList['openedBy']['type']  = 'user';
$config->scene->dtable->fieldList['openedBy']['show']  = true;
$config->scene->dtable->fieldList['openedBy']['group'] = 2;

$config->scene->dtable->fieldList['openedDate']['title'] = $lang->testcase->openedDate;
$config->scene->dtable->fieldList['openedDate']['type']  = 'date';
$config->scene->dtable->fieldList['openedDate']['group'] = 2;

$config->scene->dtable->fieldList['lastEditedBy']['title'] = $lang->testcase->lastEditedBy;
$config->scene->dtable->fieldList['lastEditedBy']['type']  = 'user';
$config->scene->dtable->fieldList['lastEditedBy']['group'] = 3;

$config->scene->dtable->fieldList['lastEditedDate']['title'] = $lang->testcase->lastEditedDate;
$config->scene->dtable->fieldList['lastEditedDate']['type']  = 'date';
$config->scene->dtable->fieldList['lastEditedDate']['group'] = 3;

$config->scene->dtable->fieldList['actions']['title']    = $lang->actions;
$config->scene->dtable->fieldList['actions']['type']     = 'actions';
$config->scene->dtable->fieldList['actions']['list']     = $config->scene->actionList;
$config->scene->dtable->fieldList['actions']['menu']     = $config->scene->menu;
$config->scene->dtable->fieldList['actions']['required'] = true;
$config->scene->dtable->fieldList['actions']['group']    = 4;
