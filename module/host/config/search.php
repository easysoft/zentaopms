<?php
global $lang;

$config->host->search['module'] = 'host';
$config->host->search['fields']['name']        = $lang->host->name;
$config->host->search['fields']['id']          = 'ID';
$config->host->search['fields']['serverRoom']  = $lang->host->serverRoom;
$config->host->search['fields']['intranet']    = $lang->host->intranet;
$config->host->search['fields']['extranet']    = $lang->host->extranet;
$config->host->search['fields']['group']       = $lang->host->group;
$config->host->search['fields']['status']      = $lang->host->status;
$config->host->search['fields']['osName']      = $lang->host->osName;

$config->host->search['fields']['osVersion']   = $lang->host->osVersion;
$config->host->search['fields']['createdBy']   = $lang->host->createdBy;
$config->host->search['fields']['createdDate'] = $lang->host->createdDate;
$config->host->search['fields']['editedBy']    = $lang->host->editedBy;
$config->host->search['fields']['editedDate']  = $lang->host->editedDate;

$config->host->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->host->search['params']['id']          = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->host->search['params']['serverRoom']  = array('operator' => '=', 'control' => 'select',  'values' => '');
$config->host->search['params']['intranet']    = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->host->search['params']['extranet']    = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->host->search['params']['group']       = array('operator' => '=', 'control' => 'select',  'values' => '');
$config->host->search['params']['status']      = array('operator' => '=', 'control' => 'select',  'values' => $lang->host->statusList);
$config->host->search['params']['osName']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->host->search['params']['osVersion']   = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->host->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->host->search['params']['createdDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->host->search['params']['editedBy']    = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->host->search['params']['editedDate']  = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
