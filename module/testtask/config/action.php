<?php
global $app, $lang;
$app->loadLang('testcase');

$config->testtask->actionList = array();
$config->testtask->actionList['start']['icon']        = 'play';
$config->testtask->actionList['start']['text']        = $lang->testtask->start;
$config->testtask->actionList['start']['hint']        = $lang->testtask->start;
$config->testtask->actionList['start']['url']         = array('module' => 'testtask', 'method' => 'start', 'params' => 'taskID={id}');
$config->testtask->actionList['start']['data-toggle'] = 'modal';

$config->testtask->actionList['close']['icon']        = 'off';
$config->testtask->actionList['close']['text']        = $lang->testtask->close;
$config->testtask->actionList['close']['hint']        = $lang->testtask->close;
$config->testtask->actionList['close']['url']         = array('module' => 'testtask', 'method' => 'close', 'params' => 'taskID={id}');
$config->testtask->actionList['close']['data-toggle'] = 'modal';

$config->testtask->actionList['block']['icon']        = 'pause';
$config->testtask->actionList['block']['text']        = $lang->testtask->block;
$config->testtask->actionList['block']['hint']        = $lang->testtask->block;
$config->testtask->actionList['block']['url']         = array('module' => 'testtask', 'method' => 'block', 'params' => 'taskID={id}');
$config->testtask->actionList['block']['data-toggle'] = 'modal';

$config->testtask->actionList['activate']['icon']        = 'magic';
$config->testtask->actionList['activate']['text']        = $lang->testtask->activate;
$config->testtask->actionList['activate']['hint']        = $lang->testtask->activate;
$config->testtask->actionList['activate']['url']         = array('module' => 'testtask', 'method' => 'activate', 'params' => 'taskID={id}');
$config->testtask->actionList['activate']['data-toggle'] = 'modal';

$config->testtask->actionList['cases']['icon']     = 'sitemap';
$config->testtask->actionList['cases']['text']     = $lang->testtask->cases;
$config->testtask->actionList['cases']['hint']     = $lang->testtask->cases;
$config->testtask->actionList['cases']['url']      = array('module' => 'testtask', 'method' => 'cases', 'params' => 'taskID={id}');
$config->testtask->actionList['cases']['data-app'] = $app->tab;

$config->testtask->actionList['linkCase']['icon']     = 'link';
$config->testtask->actionList['linkCase']['text']     = $lang->testtask->linkCase;
$config->testtask->actionList['linkCase']['hint']     = $lang->testtask->linkCase;
$config->testtask->actionList['linkCase']['url']      = array('module' => 'testtask', 'method' => 'linkCase', 'params' => 'taskID={id}&type=all&param=myQueryID');
$config->testtask->actionList['linkCase']['data-app'] = $app->tab;

$config->testtask->actionList['report']['icon']     = 'summary';
$config->testtask->actionList['report']['text']     = $lang->testtask->testreport;
$config->testtask->actionList['report']['hint']     = $lang->testtask->testreport;
$config->testtask->actionList['report']['url']      = array('module' => 'testreport', 'method' => 'browse', 'params' => 'objectID={product}&objectType=product&extra={id}');
$config->testtask->actionList['report']['data-app'] = $app->tab;

$config->testtask->actionList['view']['icon']        = 'list-alt';
$config->testtask->actionList['view']['text']        = $lang->testtask->view;
$config->testtask->actionList['view']['hint']        = $lang->testtask->view;
$config->testtask->actionList['view']['url']         = array('module' => 'testtask', 'method' => 'view', 'params' => 'taskID={id}');
$config->testtask->actionList['view']['data-toggle'] = 'modal';
$config->testtask->actionList['view']['data-size']   = 'lg';

$config->testtask->actionList['unitCases']['icon'] = 'list-alt';
$config->testtask->actionList['unitCases']['text'] = $lang->testtask->unitCases;
$config->testtask->actionList['unitCases']['hint'] = $lang->testtask->unitCases;
$config->testtask->actionList['unitCases']['url']  = array('module' => 'testtask', 'method' => 'unitcases', 'params' => 'taskID={id}');

$config->testtask->actionList['edit']['icon']     = 'edit';
$config->testtask->actionList['edit']['text']     = $lang->testtask->edit;
$config->testtask->actionList['edit']['hint']     = $lang->testtask->edit;
$config->testtask->actionList['edit']['url']      = array('module' => 'testtask', 'method' => 'edit', 'params' => 'taskID={id}');
$config->testtask->actionList['edit']['data-app'] = $app->tab;

$config->testtask->actionList['delete']['icon']         = 'trash';
$config->testtask->actionList['delete']['text']         = $lang->testtask->delete;
$config->testtask->actionList['delete']['hint']         = $lang->testtask->delete;
$config->testtask->actionList['delete']['url']          = array('module' => 'testtask', 'method' => 'delete', 'params' => 'taskID={id}');
$config->testtask->actionList['delete']['className']    = 'ajax-submit';
$config->testtask->actionList['delete']['data-confirm'] = array('message' => $lang->testtask->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

if(!isset($config->testtask->cases)) $config->testtask->cases = new stdclass();
$config->testtask->cases->actionList['confirmChange']['icon']  = 'search';
$config->testtask->cases->actionList['confirmChange']['text']  = $lang->testcase->confirmChange;
$config->testtask->cases->actionList['confirmChange']['hint']  = $lang->testcase->confirmChange;
$config->testtask->cases->actionList['confirmChange']['url']   = array('module' => 'testcase', 'method' => 'confirmChange', 'params' => 'id={case}&taskID={task}&from=list');
$config->testtask->cases->actionList['confirmChange']['class'] = 'ajax-submit';

$config->testtask->cases->actionList['createBug']['icon']        = 'bug';
$config->testtask->cases->actionList['createBug']['text']        = $lang->testcase->createBug;
$config->testtask->cases->actionList['createBug']['hint']        = $lang->testcase->createBug;
$config->testtask->cases->actionList['createBug']['url']         = array('module' => 'testcase', 'method' => 'createBug', 'params' => 'product={product}&caseID={case}&version={version}&runID={id}', 'data-width' => '90%');
$config->testtask->cases->actionList['createBug']['data-width']  = 'lg';
$config->testtask->cases->actionList['createBug']['data-toggle'] = 'modal';

$config->testtask->cases->actionList['runCase']['icon']        = 'play';
$config->testtask->cases->actionList['runCase']['text']        = $lang->testtask->runCase;
$config->testtask->cases->actionList['runCase']['hint']        = $lang->testtask->runCase;
$config->testtask->cases->actionList['runCase']['url']         = array('module' => 'testtask', 'method' => 'runCase', 'params' => 'id={id}');
$config->testtask->cases->actionList['runCase']['data-size']   = 'lg';
$config->testtask->cases->actionList['runCase']['data-toggle'] = 'modal';

$config->testtask->cases->actionList['results']['icon'] = 'list-alt';
$config->testtask->cases->actionList['results']['text'] = $lang->testtask->results;
$config->testtask->cases->actionList['results']['hint'] = $lang->testtask->results;
$config->testtask->cases->actionList['results']['url']  = array('module' => 'testtask', 'method' => 'results', 'params' => 'id={id}');
$config->testtask->cases->actionList['results']['data-size']   = 'lg';
$config->testtask->cases->actionList['results']['data-toggle'] = 'modal';

$config->testtask->cases->actionList['unlinkCase']['icon']         = 'unlink';
$config->testtask->cases->actionList['unlinkCase']['text']         = $lang->testtask->unlinkCase;
$config->testtask->cases->actionList['unlinkCase']['hint']         = $lang->testtask->unlinkCase;
$config->testtask->cases->actionList['unlinkCase']['url']          = array('module' => 'testtask', 'method' => 'unlinkCase', 'params' => 'caseID={id}');
$config->testtask->cases->actionList['unlinkCase']['class']        = 'ajax-submit';
$config->testtask->cases->actionList['unlinkCase']['data-confirm'] = $lang->testtask->confirmUnlinkCase;
