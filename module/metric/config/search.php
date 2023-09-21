<?php
global $lang;
$config->metric->browse = new stdClass();
$config->metric->browse->search['module']               = 'metric';
$config->metric->browse->search['fields']['name']       = $lang->metric->name;
$config->metric->browse->search['fields']['scope']      = $lang->metric->scope;
$config->metric->browse->search['fields']['object']     = $lang->metric->object;
$config->metric->browse->search['fields']['purpose']    = $lang->metric->purpose;
$config->metric->browse->search['fields']['desc']       = $lang->metric->desc;
$config->metric->browse->search['fields']['createdBy']  = $lang->metric->createdBy;

$config->metric->browse->search['params']['name']       = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->metric->browse->search['params']['scope']      = array('operator' => '=',        'control' => 'select', 'values' => $lang->metric->scopeList);
$config->metric->browse->search['params']['object']     = array('operator' => '=',        'control' => 'select', 'values' => $lang->metric->objectList);
$config->metric->browse->search['params']['purpose']    = array('operator' => '=',        'control' => 'select', 'values' => $lang->metric->purposeList);
$config->metric->browse->search['params']['desc']       = array('operator' => 'include',  'control' => 'input',  'values' => '');
$config->metric->browse->search['params']['createdBy']  = array('operator' => '=',        'control' => 'select', 'values' => 'users');
