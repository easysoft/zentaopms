<?php
$config->executionnode->create         = new stdClass();
$config->executionnode->edit           = new stdClass();
$config->executionnode->create->requiredFields = 'name,host,image,cpu,memory,disk,os';
$config->executionnode->edit->requiredFields   = '';

$config->executionnode->defaultPort = '8086';

$config->executionnode->os = new stdClass();

$config->executionnode->os->cpuCores = array();
$config->executionnode->os->cpuCores['1']  = '1';
$config->executionnode->os->cpuCores['2']  = '2';
$config->executionnode->os->cpuCores['4']  = '4';
$config->executionnode->os->cpuCores['6']  = '6';
$config->executionnode->os->cpuCores['8']  = '8';
$config->executionnode->os->cpuCores['12'] = '12';
$config->executionnode->os->cpuCores['16'] = '16';
$config->executionnode->os->cpuCores['24'] = '24';
$config->executionnode->os->cpuCores['32'] = '32';
$config->executionnode->os->cpuCores['64'] = '64';

global $lang;
$config->executionnode->search['module'] = 'executionnode';
$config->executionnode->search['fields']['name']       = $lang->executionnode->name;
$config->executionnode->search['fields']['osName']     = $lang->executionnode->osName;
$config->executionnode->search['fields']['host']       = $lang->executionnode->hostName;
$config->executionnode->search['fields']['status']     = $lang->executionnode->status;
$config->executionnode->search['fields']['cpuCores']   = $lang->executionnode->cpuCores;
$config->executionnode->search['fields']['memory']     = $lang->executionnode->memory;
$config->executionnode->search['fields']['diskSize']   = $lang->executionnode->diskSize;
$config->executionnode->search['fields']['mac']        = $lang->executionnode->mac;
$config->executionnode->search['fields']['address']    = $lang->executionnode->ip;
$config->executionnode->search['params']['name']       = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->executionnode->search['params']['osName']         = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->executionnode->search['params']['host']       = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->executionnode->search['params']['status']     = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $lang->executionnode->statusList);
$config->executionnode->search['params']['cpuCores']   = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $config->executionnode->os->cpuCores);
$config->executionnode->search['params']['memory']     = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->executionnode->search['params']['diskSize']   = array('operator' => '=', 'control' => 'input',  'values' => '');

$config->executionnode->editor = new stdclass();
$config->executionnode->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->executionnode->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
