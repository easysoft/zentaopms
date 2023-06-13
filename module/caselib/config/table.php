<?php
global $app, $lang;
$app->loadLang('testcase');
$app->loadModuleConfig('testcase');

$config->caselib->testcase = new stdclass();
$config->caselib->testcase->actionList['edit']['icon']  = 'edit';
$config->caselib->testcase->actionList['edit']['hint']  = $lang->testcase->edit;
$config->caselib->testcase->actionList['edit']['text']  = $lang->testcase->edit;
$config->caselib->testcase->actionList['edit']['url']   = array('module' => 'testcase', 'method' => 'edit', 'params' => 'caseID={id}');
$config->caselib->testcase->actionList['edit']['order'] = 5;
$config->caselib->testcase->actionList['edit']['show']  = 'clickable';

$config->caselib->testcase->actionList['delete']['icon']  = 'trash';
$config->caselib->testcase->actionList['delete']['hint']  = $lang->testcase->delete;
$config->caselib->testcase->actionList['delete']['text']  = $lang->testcase->delete;
$config->caselib->testcase->actionList['delete']['url']   = array('module' => 'testcase', 'method' => 'delete', 'params' => 'caseID={id}');
$config->caselib->testcase->actionList['delete']['order'] = 10;
$config->caselib->testcase->actionList['delete']['show']  = 'clickable';

$config->caselib->testcase->dtable = new stdclass();
$config->caselib->testcase->dtable->fieldList['title']    = $config->testcase->dtable->fieldList['title'];
$config->caselib->testcase->dtable->fieldList['pri']      = $config->testcase->dtable->fieldList['pri'];
$config->caselib->testcase->dtable->fieldList['type']     = $config->testcase->dtable->fieldList['type'];
$config->caselib->testcase->dtable->fieldList['status']   = $config->testcase->dtable->fieldList['status'];
$config->caselib->testcase->dtable->fieldList['openedBy'] = $config->testcase->dtable->fieldList['openedBy'];

$config->caselib->testcase->dtable->fieldList['actions']['name']       = 'actions';
$config->caselib->testcase->dtable->fieldList['actions']['title']      = $lang->actions;
$config->caselib->testcase->dtable->fieldList['actions']['type']       = 'actions';
$config->caselib->testcase->dtable->fieldList['actions']['width']      = '140';
$config->caselib->testcase->dtable->fieldList['actions']['fixed']      = 'right';
$config->caselib->testcase->dtable->fieldList['actions']['list']       = $config->caselib->testcase->actionList;
$config->caselib->testcase->dtable->fieldList['actions']['menu']       = array('edit', 'delete');
