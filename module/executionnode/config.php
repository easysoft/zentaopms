<?php
$config->executionnode->create         = new stdClass();
$config->executionnode->edit           = new stdClass();
$config->executionnode->create->requiredFields = 'name,hostID,imageID,cpu,memory,disk,os';
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
$config->executionnode->os->cpu['24'] = '24';
$config->executionnode->os->cpu['32'] = '32';
$config->executionnode->os->cpu['64'] = '64';

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
$config->executionnode->search['fields']['os']         = $lang->executionnode->os;
$config->executionnode->search['fields']['hostID']     = $lang->executionnode->hostName;
$config->executionnode->search['fields']['status']     = $lang->executionnode->status;
$config->executionnode->search['fields']['cpu']        = $lang->executionnode->cpu;
$config->executionnode->search['fields']['memory']     = $lang->executionnode->memory;
$config->executionnode->search['fields']['disk']       = $lang->executionnode->disk;
$config->executionnode->search['fields']['mac']        = $lang->executionnode->mac;
$config->executionnode->search['fields']['address']    = $lang->executionnode->ip;
$config->executionnode->search['params']['name']       = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->executionnode->search['params']['os']         = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->executionnode->search['params']['hostID']     = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->executionnode->search['params']['status']     = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $lang->executionnode->statusList);
$config->executionnode->search['params']['cpu']        = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $config->executionnode->os->cpu);
$config->executionnode->search['params']['memory']     = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->executionnode->search['params']['disk']       = array('operator' => '=', 'control' => 'input',  'values' => '');

$config->executionnode->editor = new stdclass();
$config->executionnode->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->executionnode->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
