<?php
global $lang, $app;
$app->loadLang('testcase');
$app->loadModuleConfig('testcase');

$config->block->case = new stdclass();
$config->block->case->dtable = new stdclass();
$config->block->case->dtable->fieldList = array();
$config->block->case->dtable->fieldList['id']            = array('name' => 'id',    'title' => $lang->idAB,            'type' => 'id');
$config->block->case->dtable->fieldList['title']         = array('name' => 'title', 'title' => $lang->testcase->title, 'type' => 'title', 'link' => array('module' => 'testcase', 'method' => 'view', 'params' => "caseID={id}"));
$config->block->case->dtable->fieldList['pri']           = $config->testcase->dtable->fieldList['pri'];
$config->block->case->dtable->fieldList['status']        = $config->testcase->dtable->fieldList['status'];
$config->block->case->dtable->fieldList['lastRunDate']   = $config->testcase->dtable->fieldList['lastRunDate'];
$config->block->case->dtable->fieldList['lastRunResult'] = $config->testcase->dtable->fieldList['lastRunResult'];

$config->block->case->dtable->fieldList['pri']['name']           = 'pri';
$config->block->case->dtable->fieldList['status']['name']        = 'status';
$config->block->case->dtable->fieldList['lastRunDate']['name']   = 'lastRunDate';
$config->block->case->dtable->fieldList['lastRunResult']['name'] = 'lastRunResult';
