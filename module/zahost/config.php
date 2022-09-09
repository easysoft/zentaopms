<?php
$config->zahost->create = new stdclass();
$config->zahost->create->requiredFields = 'name,hostType,publicIP,cpuCores,memory,diskSize,virtualSoftware,instanceNum';
$config->zahost->create->ipFields       = 'publicIP';

global $lang;
$config->zahost->search['module'] = 'zahost';
$config->zahost->search['fields']['name']            = $lang->zahost->name;
$config->zahost->search['fields']['id']              = 'ID';
$config->zahost->search['fields']['type']            = $lang->zahost->type;
$config->zahost->search['fields']['publicIP']        = $lang->zahost->IP;
$config->zahost->search['fields']['cpuCores']        = $lang->zahost->cpuCores;
$config->zahost->search['fields']['memory']          = $lang->zahost->memory;
$config->zahost->search['fields']['diskSize']        = $lang->zahost->diskSize;
$config->zahost->search['fields']['virtualSoftware'] = $lang->zahost->virtualSoftware;
$config->zahost->search['fields']['status']          = $lang->zahost->status;
$config->zahost->search['fields']['instanceNum']     = $lang->zahost->instanceNum;
$config->zahost->search['fields']['createdBy']       = $lang->zahost->createdBy;
$config->zahost->search['fields']['createdDate']     = $lang->zahost->createdDate;
$config->zahost->search['fields']['registerDate']    = $lang->zahost->registerDate;
$config->zahost->search['fields']['editedBy']        = $lang->zahost->editedBy;
$config->zahost->search['fields']['editedDate']      = $lang->zahost->editedDate;

$config->zahost->search['params']['name']            = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['id']              = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['type']            = array('operator' => '=', 'control' => 'input',  'values' => $lang->zahost->zaHostType);
$config->zahost->search['params']['publicIP']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['cpuCores']        = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['memory']          = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['diskSize']        = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['virtualSoftware'] = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['status']          = array('operator' => '=', 'control' => 'select',  'values' => $lang->zahost->statusList);
$config->zahost->search['params']['instanceNum']     = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['createdBy']       = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->zahost->search['params']['createdDate']     = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->zahost->search['params']['registerDate']    = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->zahost->search['params']['editedBy']        = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->zahost->search['params']['editedDate']      = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
