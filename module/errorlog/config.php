<?php
$config->errorlog = new stdclass();
$config->errorlog->enabled     = true;
$config->errorlog->saveDays    = 7;
$config->errorlog->maxSaveDays = 30;

global $lang;
if(!isset($lang->errorlog)) $lang->errorlog = new stdclass();

$config->errorlog->search['module']   = 'errorlog';
$config->errorlog->search['fields']['id']          = $lang->idAB;
$config->errorlog->search['fields']['requestID']   = $lang->errorlog->requestID;
$config->errorlog->search['fields']['module']      = $lang->errorlog->module;
$config->errorlog->search['fields']['method']      = $lang->errorlog->method;
$config->errorlog->search['fields']['level']       = $lang->errorlog->level;
$config->errorlog->search['fields']['message']     = $lang->errorlog->message;
$config->errorlog->search['fields']['createdDate'] = $lang->errorlog->createdDate;

$config->errorlog->search['params']['id']          = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->errorlog->search['params']['requestID']   = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->errorlog->search['params']['module']      = array('operator' => '=',       'control' => 'select', 'values' => array());
$config->errorlog->search['params']['method']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->errorlog->search['params']['level']       = array('operator' => '=',       'control' => 'select', 'values' => $lang->errorlog->levelList);
$config->errorlog->search['params']['message']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->errorlog->search['params']['createdDate'] = array('operator' => '=',       'control' => 'date',   'values' => '');
