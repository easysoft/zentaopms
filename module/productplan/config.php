<?php
$config->productplan = new stdclass();
$config->productplan->create = new stdclass();
$config->productplan->edit   = new stdclass();
$config->productplan->create->requiredFields = 'title';
$config->productplan->edit->requiredFields   = 'title';

$config->productplan->editor = new stdclass();
$config->productplan->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->start  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->productplan->editor->close  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->productplan->editor->view   = array('id' => 'lastComment', 'tools' => 'simpleTools');

$config->productplan->laneColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#FFC20E', '#00A78E', '#7FBB00', '#424BAC', '#C0E9FF', '#EC2761');

$config->productplan->future = '2030-01-01';

global $app, $lang;
$app->loadLang('productplan');
$config->productplan->search['module'] = 'productplan';
$config->productplan->browse = new stdclass();

$config->productplan->search['fields']['id']     = $lang->productplan->id;
$config->productplan->search['fields']['title']  = $lang->productplan->title;
$config->productplan->search['fields']['branch'] = $lang->productplan->branch;
$config->productplan->search['fields']['status'] = $lang->productplan->status;
$config->productplan->search['fields']['begin']  = $lang->productplan->begin;
$config->productplan->search['fields']['end']    = $lang->productplan->end;

$config->productplan->search['params']['id']     = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->productplan->search['params']['title']  = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->productplan->search['params']['branch'] = array('operator' => 'include', 'control' => 'select', 'values' => '');
$config->productplan->search['params']['status'] = array('operator' => '=',       'control' => 'select', 'values' => array('' => '') + $lang->productplan->statusList);
$config->productplan->search['params']['begin']  = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->productplan->search['params']['end']    = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
