<?php
global $app, $lang;
$app->loadLang('testtask');

$config->testcase->menu       = array(array('confirmStoryChange'), array('review', 'runCase|ztfRun', 'runResult', 'edit', 'createBug', 'create', 'showScript'));
$config->testcase->actionList = array();
$config->testcase->actionList['confirmStoryChange']['icon']      = 'ok';
$config->testcase->actionList['confirmStoryChange']['text']      = $lang->confirm;
$config->testcase->actionList['confirmStoryChange']['hint']      = $lang->confirm;
$config->testcase->actionList['confirmStoryChange']['url']       = array('module'  => 'testcase', 'method' => 'confirmStoryChange', 'params' => 'caseID={caseID}');
$config->testcase->actionList['confirmStoryChange']['className'] = 'ajax-submit';

$config->testcase->actionList['runCase']['icon']         = 'play';
$config->testcase->actionList['runCase']['text']         = $lang->testtask->runCase;
$config->testcase->actionList['runCase']['hint']         = $lang->testtask->runCase;
$config->testcase->actionList['runCase']['url']          = array('module' => 'testtask', 'method' => 'runCase', 'params' => 'runID=0&caseID={caseID}&version={version}');
$config->testcase->actionList['runCase']['notLoadModel'] = true;
$config->testcase->actionList['runCase']['data-toggle']  = 'modal';
$config->testcase->actionList['runCase']['data-size']    = 'lg';

$config->testcase->actionList['ztfRun']['icon']         = 'play';
$config->testcase->actionList['ztfRun']['text']         = $lang->testtask->runCase;
$config->testcase->actionList['ztfRun']['hint']         = $lang->testtask->runCase;
$config->testcase->actionList['ztfRun']['url']          = array('module' => 'testtask', 'method' => 'runCase', 'params' => 'runID=0&caseID={caseID}&version={version}');
$config->testcase->actionList['ztfRun']['notLoadModel'] = true;
$config->testcase->actionList['ztfRun']['className']    = 'ajax-submit';

$config->testcase->actionList['runResult']['icon']        = 'list-alt';
$config->testcase->actionList['runResult']['text']        = $lang->testtask->results;
$config->testcase->actionList['runResult']['hint']        = $lang->testtask->results;
$config->testcase->actionList['runResult']['url']         = array('module' => 'testtask', 'method' => 'results', 'params' => 'runID=0&caseID={caseID}');
$config->testcase->actionList['runResult']['data-toggle'] = 'modal';
$config->testcase->actionList['runResult']['data-size']   = 'lg';

$config->testcase->actionList['edit']['icon']       = 'edit';
$config->testcase->actionList['edit']['text']       = $lang->testcase->edit;
$config->testcase->actionList['edit']['hint']       = $lang->testcase->edit;
$config->testcase->actionList['edit']['url']        = array('module' => 'testcase', 'method' => 'edit', 'params' => 'caseID={caseID}&comment=false&executionID=%executionID%');
$config->testcase->actionList['edit']['data-app']   = $app->tab;
$config->testcase->actionList['edit']['notInModal'] = true;

$config->testcase->actionList['review']['icon']        = 'glasses';
$config->testcase->actionList['review']['text']        = $lang->testcase->review;
$config->testcase->actionList['review']['hint']        = $lang->testcase->review;
$config->testcase->actionList['review']['url']         = array('module' => 'testcase', 'method' => 'review', 'params' => 'caseID={caseID}');
$config->testcase->actionList['review']['data-toggle'] = 'modal';

$config->testcase->actionList['importToLib']['icon']        = 'assets';
$config->testcase->actionList['importToLib']['text']        = $lang->testcase->importToLib;
$config->testcase->actionList['importToLib']['hint']        = $lang->testcase->importToLib;
$config->testcase->actionList['importToLib']['url']         = array('module' => 'testcase', 'method' => 'importToLib', 'params' => 'caseID={caseID}');
$config->testcase->actionList['importToLib']['data-toggle'] = 'modal';
$config->testcase->actionList['importToLib']['notInModal']  = true;

$config->testcase->actionList['createBug']['icon']        = 'bug';
$config->testcase->actionList['createBug']['text']        = $lang->testcase->createBug;
$config->testcase->actionList['createBug']['hint']        = $lang->testcase->createBug;
$config->testcase->actionList['createBug']['url']         = array('module' => 'testcase', 'method' => 'createBug', 'params' => 'product={product}&caseID={caseID}&version={version}');
$config->testcase->actionList['createBug']['data-toggle'] = 'modal';
$config->testcase->actionList['createBug']['data-size']   = 'lg';

$config->testcase->actionList['create']['icon']       = 'copy';
$config->testcase->actionList['create']['text']       = $lang->testcase->copy;
$config->testcase->actionList['create']['hint']       = $lang->testcase->copy;
$config->testcase->actionList['create']['url']        = array('module' => 'testcase', 'method' => 'create', 'params' => 'productID={product}&branch={branch}&moduleID={module}&from=testcase&param={caseID}');
$config->testcase->actionList['create']['data-app']   = $app->tab;
$config->testcase->actionList['create']['notInModal'] = true;

$config->testcase->actionList['unlinkCase']['icon'] = 'unlink';
$config->testcase->actionList['unlinkCase']['text'] = $lang->testtask->unlinkCase;
$config->testcase->actionList['unlinkCase']['hint'] = $lang->testtask->unlinkCase;
$config->testcase->actionList['unlinkCase']['url']  = array('module' => 'testtask', 'method' => 'unlinkCase', 'params' => 'runID={caseID}&confirm=yes');

$config->testcase->actionList['showScript']['icon']        = 'file-code';
$config->testcase->actionList['showScript']['text']        = $lang->testcase->showScript;
$config->testcase->actionList['showScript']['hint']        = $lang->testcase->showScript;
$config->testcase->actionList['showScript']['url']         = array('module' => 'testcase', 'method' => 'showScript', 'params' => 'caseID={caseID}');
$config->testcase->actionList['showScript']['data-toggle'] = 'modal';
$config->testcase->actionList['showScript']['data-size']   = 'lg';

$config->testcase->actionList['editScene']['icon'] = 'edit';
$config->testcase->actionList['editScene']['text'] = $lang->testcase->editScene;
$config->testcase->actionList['editScene']['hint'] = $lang->testcase->editScene;
$config->testcase->actionList['editScene']['url']  = array('module' => 'testcase', 'method' => 'editScene', 'params' => 'sceneID={id}&executionID=%executionID%');

$config->testcase->actionList['deleteScene']['icon']      = 'trash';
$config->testcase->actionList['deleteScene']['text']      = $lang->testcase->deleteScene;
$config->testcase->actionList['deleteScene']['hint']      = $lang->testcase->deleteScene;
$config->testcase->actionList['deleteScene']['url']       = array('module' => 'testcase', 'method' => 'deleteScene', 'params' => 'sceneID={id}');
$config->testcase->actionList['deleteScene']['className'] = 'ajax-submit';

$config->testcase->actionList['delete']['icon']         = 'trash';
$config->testcase->actionList['delete']['text']         = $lang->testcase->deleteAction;
$config->testcase->actionList['delete']['hint']         = $lang->testcase->deleteAction;
$config->testcase->actionList['delete']['url']          = array('module' => 'testcase', 'method' => 'delete', 'params' => 'caseID={id}');
$config->testcase->actionList['delete']['class']        = 'ajax-submit';
$config->testcase->actionList['delete']['data-confirm'] = $lang->testcase->confirmDelete;
$config->testcase->actionList['delete']['notInModal']   = true;

$config->scene = new stdclass();
$config->scene->menu = array('editScene', 'deleteScene');

$config->scene->actionList['editScene']['icon'] = 'edit';
$config->scene->actionList['editScene']['text'] = $lang->testcase->editScene;
$config->scene->actionList['editScene']['hint'] = $lang->testcase->editScene;
$config->scene->actionList['editScene']['url']  = array('module' => 'testcase', 'method' => 'editScene', 'params' => 'sceneID={id}&executionID=%executionID%');

$config->scene->actionList['deleteScene']['icon']      = 'trash';
$config->scene->actionList['deleteScene']['text']      = $lang->testcase->deleteScene;
$config->scene->actionList['deleteScene']['hint']      = $lang->testcase->deleteScene;
$config->scene->actionList['deleteScene']['url']       = array('module' => 'testcase', 'method' => 'deleteScene', 'params' => 'sceneID={id}');
$config->scene->actionList['deleteScene']['className'] = 'ajax-submit';
