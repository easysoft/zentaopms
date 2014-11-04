<?php
$config->testcase = new stdclass();
$config->testcase->defaultSteps = 3;
$config->testcase->batchCreate  = 10;

$config->testcase->create = new stdclass();
$config->testcase->edit   = new stdclass();
$config->testcase->create->requiredFields = 'title,type';
$config->testcase->edit->requiredFields   = 'title,type';

$config->testcase->batchEdit = new stdclass();
$config->testcase->batchEdit->columns = 7;

$config->testcase->editor = new stdclass();
$config->testcase->editor->edit = array('id' => 'comment', 'tools' => 'simpleTools');
$config->testcase->editor->view = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

$config->testcase->export   = new stdclass();
$config->testcase->export->listFields   = array('type', 'stage', 'pri', 'status');

$config->testcase->exportFields = '
    id, product, module, story,
    title, precondition, stepDesc, stepExpect, keywords,
    pri, type, stage, status, lastRunResult, openedBy, openedDate,
    lastEditedBy, lastEditedDate, version,linkCase';

global $lang;
$config->testcase->search['module']                   = 'testcase';
$config->testcase->search['fields']['title']          = $lang->testcase->title;
$config->testcase->search['fields']['id']             = $lang->testcase->id;
$config->testcase->search['fields']['keywords']       = $lang->testcase->keywords;
$config->testcase->search['fields']['lastEditedBy']   = $lang->testcase->lastEditedByAB;
$config->testcase->search['fields']['type']           = $lang->testcase->type;

$config->testcase->search['fields']['openedBy']       = $lang->testcase->openedBy;
$config->testcase->search['fields']['status']         = $lang->testcase->status;
$config->testcase->search['fields']['product']        = $lang->testcase->product;
$config->testcase->search['fields']['stage']          = $lang->testcase->stage;
$config->testcase->search['fields']['module']         = $lang->testcase->module;
$config->testcase->search['fields']['pri']            = $lang->testcase->pri;

$config->testcase->search['fields']['openedDate']     = $lang->testcase->openedDate;
$config->testcase->search['fields']['lastEditedDate'] = $lang->testcase->lastEditedDateAB;

$config->testcase->search['params']['title']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->testcase->search['params']['module']       = array('operator' => 'belong',  'control' => 'select', 'values' => 'modules');
$config->testcase->search['params']['keywords']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->testcase->search['params']['lastEditedBy'] = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->testcase->search['params']['type']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->testcase->typeList);

$config->testcase->search['params']['pri']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->testcase->priList);
$config->testcase->search['params']['openedBy']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->testcase->search['params']['status']       = array('operator' => '=',       'control' => 'select', 'values' => $lang->testcase->statusList);
$config->testcase->search['params']['product']      = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->testcase->search['params']['stage']        = array('operator' => 'include', 'control' => 'select', 'values' => $lang->testcase->stageList);

$config->testcase->search['params']['openedDate']     = array('operator' => '>=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->testcase->search['params']['lastEditedDate'] = array('operator' => '>=', 'control' => 'input', 'values' => '', 'class' => 'date');
