<?php
global $app, $lang;
$app->loadLang('testcase');

$config->caselib->testcase = new stdclass();
$config->caselib->testcase->actionList['edit']['icon']  = 'edit';
$config->caselib->testcase->actionList['edit']['hint']  = $lang->testcase->edit;
$config->caselib->testcase->actionList['edit']['text']  = $lang->testcase->edit;
$config->caselib->testcase->actionList['edit']['url']   = array('module' => 'testcase', 'method' => 'edit', 'params' => 'caseID={id}');
$config->caselib->testcase->actionList['edit']['order'] = 5;

$config->caselib->testcase->actionList['delete']['icon']         = 'trash';
$config->caselib->testcase->actionList['delete']['hint']         = $lang->testcase->delete;
$config->caselib->testcase->actionList['delete']['text']         = $lang->testcase->delete;
$config->caselib->testcase->actionList['delete']['url']          = array('module' => 'testcase', 'method' => 'delete', 'params' => 'caseID={id}');
$config->caselib->testcase->actionList['delete']['order']        = 10;
$config->caselib->testcase->actionList['delete']['className']    = 'ajax-submit';
$config->caselib->testcase->actionList['delete']['data-confirm'] = $lang->testcase->confirmDelete;

$config->caselib->dtable = new stdclass();
$config->caselib->dtable->fieldList = $config->testcase->dtable->fieldList;
$config->caselib->dtable->fieldList['title']['link'] = array('module' => 'testcase', 'method' => 'view', 'params' => "caseID={id}");

$config->caselib->dtable->fieldList['type']['show']          = true;
$config->caselib->dtable->fieldList['status']['show']        = true;
$config->caselib->dtable->fieldList['lastRunner']['show']    = false;
$config->caselib->dtable->fieldList['lastRunDate']['show']   = false;
$config->caselib->dtable->fieldList['lastRunResult']['show'] = false;

$config->caselib->dtable->fieldList['actions']['list']  = $config->caselib->testcase->actionList;
$config->caselib->dtable->fieldList['actions']['menu']  = array('edit', 'delete');
$config->caselib->dtable->fieldList['actions']['width'] = '80';
