<?php
$config->zanode->create         = new stdClass();
$config->zanode->edit           = new stdClass();
$config->zanode->createimage    = new stdClass();
$config->zanode->create->requiredFields        = 'name,host,image,cpu,memory,disk,osName';
$config->zanode->create->physicsRequiredFields = 'name,extranet,cpu,memory,osName';
$config->zanode->edit->requiredFields          = '';
$config->zanode->createimage->requiredFields   = 'name';

$config->zanode->defaultPort       = '55001';
$config->zanode->defaultAccount    = 'z';
$config->zanode->defaultWinAccount = 'admin';
$config->zanode->defaultPwd        = 'CQdliYQn6tKkoFhP';

$config->zanode->initBash = 'curl -sSL https://pkg.qucheng.com/zenagent/zagent.sh | bash /dev/stdin -szvm -z%s';

$config->zanode->os = new stdClass();

$config->zanode->os->cpuCores = array();
$config->zanode->os->cpuCores['1']  = '1';
$config->zanode->os->cpuCores['2']  = '2';
$config->zanode->os->cpuCores['4']  = '4';
$config->zanode->os->cpuCores['6']  = '6';
$config->zanode->os->cpuCores['8']  = '8';
$config->zanode->os->cpuCores['12'] = '12';
$config->zanode->os->cpuCores['16'] = '16';
$config->zanode->os->cpuCores['24'] = '24';
$config->zanode->os->cpuCores['32'] = '32';
$config->zanode->os->cpuCores['64'] = '64';

global $lang;
$config->zanode->search['module'] = 'zanode';
$config->zanode->search['fields']['name']       = $lang->zanode->name;
$config->zanode->search['fields']['osName']     = $lang->zanode->osName;
$config->zanode->search['fields']['host']       = $lang->zanode->hostName;
$config->zanode->search['fields']['status']     = $lang->zanode->status;
$config->zanode->search['fields']['cpuCores']   = $lang->zanode->cpuCores;
$config->zanode->search['fields']['memory']     = $lang->zanode->memory;
$config->zanode->search['fields']['diskSize']   = $lang->zanode->diskSize;
$config->zanode->search['fields']['extranet']   = $lang->zanode->extranet;
$config->zanode->search['params']['name']       = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->zanode->search['params']['osName']     = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->zanode->search['params']['host']       = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->zanode->search['params']['status']     = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $lang->zanode->statusList);
$config->zanode->search['params']['cpuCores']   = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $config->zanode->os->cpuCores);
$config->zanode->search['params']['memory']     = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->zanode->search['params']['diskSize']   = array('operator' => '=', 'control' => 'input',  'values' => '');

$config->zanode->editor = new stdclass();
$config->zanode->editor->create      = array('id' => 'desc', 'tools' => 'simpleTools');
$config->zanode->editor->edit        = array('id' => 'desc', 'tools' => 'simpleTools');
$config->zanode->editor->createimage = array('id' => 'desc', 'tools' => 'simpleTools');
$config->zanode->editor->view        = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');
