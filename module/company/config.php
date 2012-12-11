<?php
$config->company = new stdclass();
$config->company->create = new stdclass();
$config->company->edit   = new stdclass();
$config->company->create->requiredFields = 'name,pms';
$config->company->edit->requiredFields   = 'name,pms';

global $lang, $app;
$app->loadLang('action');
$config->company->dynamic = new stdclass();
$config->company->dynamic->search['module']               = 'action';
$config->company->dynamic->search['fields']['product']    = $lang->action->product;
$config->company->dynamic->search['fields']['actor']      = $lang->action->actor;
$config->company->dynamic->search['fields']['objectID']   = $lang->action->objectID;
$config->company->dynamic->search['fields']['project']    = $lang->action->project;
$config->company->dynamic->search['fields']['objectType'] = $lang->action->objectType;
$config->company->dynamic->search['fields']['date']       = $lang->action->date;
$config->company->dynamic->search['fields']['action']     = $lang->action->action;

$config->company->dynamic->search['params']['product']    = array('operator' => '=',  'control' => 'select', 'values' => '');
$config->company->dynamic->search['params']['actor']      = array('operator' => '=',  'control' => 'select', 'values' => '');
$config->company->dynamic->search['params']['objectID']   = array('operator' => '=',  'control' => 'input',  'values' => '');
$config->company->dynamic->search['params']['project']    = array('operator' => '=',  'control' => 'select', 'values' => '');
$config->company->dynamic->search['params']['objectType'] = array('operator' => '=',  'control' => 'select', 'values' => $lang->action->search->objectTypeList);
$config->company->dynamic->search['params']['date']       = array('operator' => '>=', 'control' => 'input',  'values' => '');
$config->company->dynamic->search['params']['action']     = array('operator' => '=',  'control' => 'select', 'values' => $lang->action->search->label);
