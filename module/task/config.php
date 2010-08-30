<?php
global $lang;
$config->task->create->requiredFields   = 'name,estimate,type,pri';
$config->task->edit->requiredFields     = $config->task->create->requiredFields;
$config->task->start->requiredFields    = 'estimate';
$config->task->complete->requiredFields = $config->task->start->requiredFields;

$config->task->search['module']                   = 'task';
$config->task->search['fields']['name']           = $lang->task->name;
$config->task->search['fields']['owner']          = $lang->task->owner;
$config->task->search['fields']['id']             = $lang->task->id;
$config->task->search['fields']['status']         = $lang->task->status;
$config->task->search['fields']['pri']            = $lang->task->pri;
$config->task->search['fields']['type']           = $lang->task->type;

$config->task->search['params']['name']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->task->search['params']['owner']        = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->task->search['params']['status']       = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->statusList);
$config->task->search['params']['pri']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->priList);
$config->task->search['params']['type']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->task->typeList);
