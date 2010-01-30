<?php
global $lang, $app;
$app->loadLang('testcase');
$config->testcase->search['module']                   = 'testcase';
$config->testcase->search['fields']['id']             = $lang->testcase->id;
$config->testcase->search['fields']['title']          = $lang->testcase->title;
$config->testcase->search['fields']['steps']          = $lang->testcase->steps;
$config->testcase->search['fields']['openedBy']       = $lang->testcase->openedBy;
$config->testcase->search['fields']['lastEditedBy']   = $lang->testcase->lastEditedBy;
$config->testcase->search['fields']['status']         = $lang->testcase->status;
$config->testcase->search['fields']['pri']            = $lang->testcase->pri;
$config->testcase->search['fields']['type']           = $lang->testcase->type;
$config->testcase->search['fields']['openedDate']     = $lang->testcase->openedDate;
$config->testcase->search['fields']['lastEditedDate'] = $lang->testcase->lastEditedDate;

$config->testcase->search['params']['title']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->testcase->search['params']['steps']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->testcase->search['params']['openedBy']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->testcase->search['params']['lastEditedBy'] = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->testcase->search['params']['status']       = array('operator' => '=',       'control' => 'select', 'values' => $lang->testcase->statusList);
$config->testcase->search['params']['pri']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->testcase->priList);
$config->testcase->search['params']['type']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->testcase->typeList);
