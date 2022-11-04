<?php
$config->executionnode->create         = new stdClass();
$config->executionnode->edit           = new stdClass();
$config->executionnode->create->requiredFields = 'name,hostID,templateID,osCpu,osMemory,osDisk';
$config->executionnode->edit->requiredFields   = '';

$config->executionnode->defaultPort = '8086';

$config->executionnode->os = new stdClass();
$config->executionnode->os->list = array();
$config->executionnode->os->list['windows'] = 'Windows';
$config->executionnode->os->list['linux']   = 'Linux';

$config->executionnode->os->cpu = array();
$config->executionnode->os->cpu['1']  = '1';
$config->executionnode->os->cpu['2']  = '2';
$config->executionnode->os->cpu['4']  = '4';
$config->executionnode->os->cpu['6']  = '6';
$config->executionnode->os->cpu['8']  = '8';
$config->executionnode->os->cpu['12'] = '12';
$config->executionnode->os->cpu['16'] = '16';

$config->executionnode->os->type = array();
$config->executionnode->os->type['windows']['winServer'] = 'Windows Server';
$config->executionnode->os->type['windows']['win11']     = 'Windows 11';
$config->executionnode->os->type['windows']['win10']     = 'Windows 10';
$config->executionnode->os->type['windows']['win7']      = 'Windows 7';
$config->executionnode->os->type['windows']['winxp']     = 'Windows XP';
$config->executionnode->os->type['linux']['ubuntu']      = 'Ubuntu';
$config->executionnode->os->type['linux']['centos']      = 'CentOS';
$config->executionnode->os->type['linux']['debian']      = 'Debian';

global $lang;
$config->executionnode->search['module'] = 'executionnode';
$config->executionnode->search['fields']['name']       = $lang->executionnode->name;
$config->executionnode->search['fields']['osType']     = $lang->executionnode->osType;
$config->executionnode->search['fields']['osVersion']  = $lang->executionnode->osVersion;
$config->executionnode->search['fields']['hostID']     = $lang->executionnode->hostName;
$config->executionnode->search['fields']['status']     = $lang->executionnode->status;
$config->executionnode->search['fields']['osCpu']      = $lang->executionnode->cpu;
$config->executionnode->search['fields']['osMemory']   = $lang->executionnode->osMemory;
$config->executionnode->search['fields']['osDisk']     = $lang->executionnode->osDisk;
$config->executionnode->search['fields']['macAddress'] = $lang->executionnode->macAddress;
$config->executionnode->search['fields']['publicIP']   = $lang->executionnode->ip;
$config->executionnode->search['params']['name']       = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->executionnode->search['params']['osType']     = array('operator' => '=', 'control' => 'select',  'values' => array('' => '') + $config->executionnode->os->type['windows'] + $config->executionnode->os->type['linux']);
$config->executionnode->search['params']['hostID']     = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->executionnode->search['params']['status']     = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $lang->executionnode->statusList);
$config->executionnode->search['params']['osCpu']      = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $config->executionnode->os->cpu);
$config->executionnode->search['params']['osMemory']   = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->executionnode->search['params']['osDisk']     = array('operator' => '=', 'control' => 'input',  'values' => '');
