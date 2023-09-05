<?php
global $lang;

$config->serverroom->search['module'] = 'serverroom';
$config->serverroom->search['fields']['name']        = $lang->serverroom->name;
$config->serverroom->search['fields']['id']          = 'ID';
$config->serverroom->search['fields']['city']        = $lang->serverroom->city;
$config->serverroom->search['fields']['line']        = $lang->serverroom->line;
$config->serverroom->search['fields']['bandwidth']   = $lang->serverroom->bandwidth;
$config->serverroom->search['fields']['provider']    = $lang->serverroom->provider;
$config->serverroom->search['fields']['owner']       = $lang->serverroom->owner;
$config->serverroom->search['fields']['createdBy']   = $lang->serverroom->createdBy;
$config->serverroom->search['fields']['createdDate'] = $lang->serverroom->createdDate;
$config->serverroom->search['fields']['editedBy']    = $lang->serverroom->editedBy;
$config->serverroom->search['fields']['editedDate']  = $lang->serverroom->editedDate;

$config->serverroom->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->serverroom->search['params']['id']          = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->serverroom->search['params']['city']        = array('operator' => '=', 'control' => 'select',  'values' => $lang->serverroom->cityList);
$config->serverroom->search['params']['line']        = array('operator' => '=', 'control' => 'select',  'values' => $lang->serverroom->lineList);
$config->serverroom->search['params']['bandwidth']   = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->serverroom->search['params']['provider']    = array('operator' => '=', 'control' => 'select',  'values' => $lang->serverroom->providerList);
$config->serverroom->search['params']['owner']       = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->serverroom->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->serverroom->search['params']['createdDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->serverroom->search['params']['editedBy']    = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->serverroom->search['params']['editedDate']  = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
