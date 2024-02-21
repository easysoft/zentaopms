<?php
global $lang;

$config->testsuite->actionList['linkCase']['icon']  = 'link';
$config->testsuite->actionList['linkCase']['hint']  = $lang->testsuite->linkCase;
$config->testsuite->actionList['linkCase']['text']  = $lang->testsuite->linkCase;
$config->testsuite->actionList['linkCase']['url']   = array('module' => 'testsuite', 'method' => 'linkCase', 'params' => 'suiteID={id}');
$config->testsuite->actionList['linkCase']['order'] = 5;
$config->testsuite->actionList['linkCase']['show']  = 'clickable';

$config->testsuite->actionList['edit']['icon']  = 'edit';
$config->testsuite->actionList['edit']['hint']  = $lang->testsuite->edit;
$config->testsuite->actionList['edit']['text']  = $lang->testsuite->edit;
$config->testsuite->actionList['edit']['url']   = array('module' => 'testsuite', 'method' => 'edit', 'params' => 'suiteID={id}');
$config->testsuite->actionList['edit']['order'] = 5;
$config->testsuite->actionList['edit']['show']  = 'clickable';

$config->testsuite->actionList['delete']['icon']         = 'trash';
$config->testsuite->actionList['delete']['hint']         = $lang->testsuite->delete;
$config->testsuite->actionList['delete']['text']         = $lang->testsuite->delete;
$config->testsuite->actionList['delete']['url']          = array('module' => 'testsuite', 'method' => 'delete', 'params' => 'suiteID={id}');
$config->testsuite->actionList['delete']['order']        = 10;
$config->testsuite->actionList['delete']['show']         = 'clickable';
$config->testsuite->actionList['delete']['data-confirm'] = array('message' => $lang->testsuite->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->testsuite->actionList['delete']['className']    = 'ajax-submit';

$config->testsuite->dtable = new stdclass();

$config->testsuite->dtable->fieldList['id']['name']  = 'id';
$config->testsuite->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testsuite->dtable->fieldList['id']['type']  = 'ID';
$config->testsuite->dtable->fieldList['id']['align'] = 'left';
$config->testsuite->dtable->fieldList['id']['fixed'] = 'left';

$config->testsuite->dtable->fieldList['name']['name']     = 'name';
$config->testsuite->dtable->fieldList['name']['title']    = $lang->testsuite->name;
$config->testsuite->dtable->fieldList['name']['type']     = 'title';
$config->testsuite->dtable->fieldList['name']['minWidth'] = '200';
$config->testsuite->dtable->fieldList['name']['fixed']    = 'left';

$config->testsuite->dtable->fieldList['desc']['name']  = 'desc';
$config->testsuite->dtable->fieldList['desc']['title'] = $lang->testsuite->desc;
$config->testsuite->dtable->fieldList['desc']['type']  = 'html';

$config->testsuite->dtable->fieldList['addedBy']['name']     = 'addedBy';
$config->testsuite->dtable->fieldList['addedBy']['title']    = $lang->testsuite->addedBy;
$config->testsuite->dtable->fieldList['addedBy']['type']     = 'user';
$config->testsuite->dtable->fieldList['addedBy']['sortType'] = true;
$config->testsuite->dtable->fieldList['addedBy']['align']    = 'left';

$config->testsuite->dtable->fieldList['addedDate']['name']     = 'addedDate';
$config->testsuite->dtable->fieldList['addedDate']['title']    = $lang->testsuite->addedDate;
$config->testsuite->dtable->fieldList['addedDate']['type']     = 'datetime';
$config->testsuite->dtable->fieldList['addedDate']['sortType'] = true;

$config->testsuite->dtable->fieldList['actions']['name']     = 'actions';
$config->testsuite->dtable->fieldList['actions']['title']    = $lang->actions;
$config->testsuite->dtable->fieldList['actions']['type']     = 'actions';
$config->testsuite->dtable->fieldList['actions']['fixed']    = 'right';
$config->testsuite->dtable->fieldList['actions']['sortType'] = false;
$config->testsuite->dtable->fieldList['actions']['list']     = $config->testsuite->actionList;
$config->testsuite->dtable->fieldList['actions']['menu']     = array('linkCase', 'edit', 'delete');

global $app;
$app->loadLang('testcase');
$app->loadLang('testtask');
$app->loadModuleConfig('testcase');

$config->testsuite->testcase = new stdclass();

$config->testsuite->testcase->actionList['unlinkCase']['icon']         = 'unlink';
$config->testsuite->testcase->actionList['unlinkCase']['text']         = $lang->testtask->unlinkCase;
$config->testsuite->testcase->actionList['unlinkCase']['hint']         = $lang->testtask->unlinkCase;
$config->testsuite->testcase->actionList['unlinkCase']['url']          = array('module' => 'testsuite', 'method' => 'unlinkCase', 'params' => 'suiteID={suite}&caseID={id}&confirm=yes');
$config->testsuite->testcase->actionList['unlinkCase']['className']    = 'ajax-submit';
$config->testsuite->testcase->actionList['unlinkCase']['data-confirm'] = $lang->testsuite->confirmUnlinkCase;

$config->testsuite->testcase->actionList['runCase']['icon']        = 'play';
$config->testsuite->testcase->actionList['runCase']['text']        = $lang->testtask->runCase;
$config->testsuite->testcase->actionList['runCase']['hint']        = $lang->testtask->runCase;
$config->testsuite->testcase->actionList['runCase']['url']         = array('module' => 'testtask', 'method' => 'runCase', 'params' => 'runID=0&caseID={id}&version={version}');
$config->testsuite->testcase->actionList['runCase']['data-toggle'] = 'modal';
$config->testsuite->testcase->actionList['runCase']['data-size']   = 'lg';

$config->testsuite->testcase->actionList['runResult']['icon']        = 'list-alt';
$config->testsuite->testcase->actionList['runResult']['text']        = $lang->testtask->results;
$config->testsuite->testcase->actionList['runResult']['hint']        = $lang->testtask->results;
$config->testsuite->testcase->actionList['runResult']['url']         = array('module' => 'testtask', 'method' => 'results', 'params' => 'runID=0&caseID={id}');
$config->testsuite->testcase->actionList['runResult']['data-toggle'] = 'modal';
$config->testsuite->testcase->actionList['runResult']['data-size']   = 'lg';

$config->testsuite->testcase->dtable = new stdclass();

$config->testsuite->testcase->dtable->fieldList['id']['name']  = 'id';
$config->testsuite->testcase->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testsuite->testcase->dtable->fieldList['id']['type']  = 'checkID';

$config->testsuite->testcase->dtable->fieldList['title']['name']  = 'title';
$config->testsuite->testcase->dtable->fieldList['title']['title'] = $lang->testcase->title;
$config->testsuite->testcase->dtable->fieldList['title']['type']  = 'title';
$config->testsuite->testcase->dtable->fieldList['title']['link']  = array('module' => 'testcase', 'method' => 'view', 'params' => 'caseID={id}&version={caseVersion}');

$config->testsuite->testcase->dtable->fieldList['pri']['name']  = 'pri';
$config->testsuite->testcase->dtable->fieldList['pri']['title'] = $lang->testcase->pri;
$config->testsuite->testcase->dtable->fieldList['pri']['type']  = 'pri';
$config->testsuite->testcase->dtable->fieldList['pri']['group'] = '1';

$config->testsuite->testcase->dtable->fieldList['type']['name']     = 'type';
$config->testsuite->testcase->dtable->fieldList['type']['title']    = $lang->testcase->type;
$config->testsuite->testcase->dtable->fieldList['type']['type']     = 'category';
$config->testsuite->testcase->dtable->fieldList['type']['map']      = $lang->testcase->typeList;
$config->testsuite->testcase->dtable->fieldList['type']['sortType'] = true;
$config->testsuite->testcase->dtable->fieldList['type']['group']    = '1';

$config->testsuite->testcase->dtable->fieldList['status']['name']     = 'status';
$config->testsuite->testcase->dtable->fieldList['status']['title']    = $lang->testcase->status;
$config->testsuite->testcase->dtable->fieldList['status']['type']     = 'category';
$config->testsuite->testcase->dtable->fieldList['status']['map']      = $lang->testcase->statusList;
$config->testsuite->testcase->dtable->fieldList['status']['sortType'] = true;
$config->testsuite->testcase->dtable->fieldList['status']['group']    = '1';

$config->testsuite->testcase->dtable->fieldList['module']['name']  = 'module';
$config->testsuite->testcase->dtable->fieldList['module']['title'] = $lang->testcase->module;
$config->testsuite->testcase->dtable->fieldList['module']['type']  = 'text';
$config->testsuite->testcase->dtable->fieldList['module']['group'] = '2';

$config->testsuite->testcase->dtable->fieldList['lastRunResult']['name']     = 'lastRunResult';
$config->testsuite->testcase->dtable->fieldList['lastRunResult']['title']    = $lang->testcase->lastRunResult;
$config->testsuite->testcase->dtable->fieldList['lastRunResult']['type']     = 'text';
$config->testsuite->testcase->dtable->fieldList['lastRunResult']['map']      = $lang->testcase->resultList;
$config->testsuite->testcase->dtable->fieldList['lastRunResult']['sortType'] = true;
$config->testsuite->testcase->dtable->fieldList['lastRunResult']['group']    = '3';

$config->testsuite->testcase->dtable->fieldList['bugs']['name']  = 'bugs';
$config->testsuite->testcase->dtable->fieldList['bugs']['title'] = $lang->testcase->bugsAB;
$config->testsuite->testcase->dtable->fieldList['bugs']['type']  = 'text';
$config->testsuite->testcase->dtable->fieldList['bugs']['group'] = '4';

$config->testsuite->testcase->dtable->fieldList['results']['name']  = 'results';
$config->testsuite->testcase->dtable->fieldList['results']['title'] = $lang->testcase->resultsAB;
$config->testsuite->testcase->dtable->fieldList['results']['type']  = 'text';
$config->testsuite->testcase->dtable->fieldList['results']['group'] = '4';

$config->testsuite->testcase->dtable->fieldList['stepNumber']['name']  = 'stepNumber';
$config->testsuite->testcase->dtable->fieldList['stepNumber']['title'] = $lang->testcase->stepNumberAB;
$config->testsuite->testcase->dtable->fieldList['stepNumber']['type']  = 'text';
$config->testsuite->testcase->dtable->fieldList['stepNumber']['group'] = '4';

$config->testsuite->testcase->dtable->fieldList['actions']['name']     = 'actions';
$config->testsuite->testcase->dtable->fieldList['actions']['type']     = 'actions';
$config->testsuite->testcase->dtable->fieldList['actions']['title']    = $lang->actions;
$config->testsuite->testcase->dtable->fieldList['actions']['sortType'] = false;
$config->testsuite->testcase->dtable->fieldList['actions']['list']     = $config->testsuite->testcase->actionList;
$config->testsuite->testcase->dtable->fieldList['actions']['menu']     = array_keys($config->testsuite->testcase->actionList);

$config->testsuite->linkcase = new stdclass();
$config->testsuite->linkcase->dtable = new stdclass();
$config->testsuite->linkcase->dtable->fieldList['id']     = $config->testcase->dtable->fieldList['id'];
$config->testsuite->linkcase->dtable->fieldList['title']  = $config->testcase->dtable->fieldList['title'];
$config->testsuite->linkcase->dtable->fieldList['pri']    = $config->testcase->dtable->fieldList['pri'];
$config->testsuite->linkcase->dtable->fieldList['type']   = $config->testcase->dtable->fieldList['type'];
$config->testsuite->linkcase->dtable->fieldList['status'] = $config->testcase->dtable->fieldList['status'];

$config->testsuite->linkcase->dtable->fieldList['id']['name'] = 'id';

$config->testsuite->linkcase->dtable->fieldList['title']['link']['params'] = 'caseID={id}';
$config->testsuite->linkcase->dtable->fieldList['title']['data-toggle']    = 'modal';
$config->testsuite->linkcase->dtable->fieldList['title']['data-size']      = 'lg';

$config->testsuite->linkcase->dtable->fieldList['version']['name']         = 'version';
$config->testsuite->linkcase->dtable->fieldList['version']['title']        = $lang->testsuite->linkVersion;
$config->testsuite->linkcase->dtable->fieldList['version']['type']         = 'control';
$config->testsuite->linkcase->dtable->fieldList['version']['control']      = 'picker';
$config->testsuite->linkcase->dtable->fieldList['version']['group']        = 'version';
$config->testsuite->linkcase->dtable->fieldList['version']['controlItems'] = array(1 => 1);

$config->testsuite->linkcase->dtable->fieldList['openedBy'] = $config->testcase->dtable->fieldList['openedBy'];
unset($config->testsuite->linkcase->dtable->fieldList['title']['nestedToggle']);
