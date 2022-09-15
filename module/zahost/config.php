<?php
$config->zahost->create = new stdclass();
$config->zahost->create->requiredFields = 'name,hostType,publicIP,cpuCores,memory,diskSize,virtualSoftware,instanceNum';
$config->zahost->create->ipFields       = 'publicIP';

$config->zahost->edit = new stdclass();
$config->zahost->edit->requiredFields = 'name,hostType,cpuCores,memory,diskSize,virtualSoftware,instanceNum';

$config->zahost->createtemplate = new stdclass();
$config->zahost->createtemplate->requiredFields = 'name,cpuCoreNum,memorySize,diskSize,osCategory,osType,osVersion,osLang';

$config->zahost->os = new stdClass();
$config->zahost->os->list = array();
$config->zahost->os->list['windows'] = 'Windows';
$config->zahost->os->list['linux']   = 'Linux';

$config->zahost->os->type = array();
$config->zahost->os->type['windows']['winServer'] = 'Windows Server';
$config->zahost->os->type['windows']['win11']     = 'Windows 11';
$config->zahost->os->type['windows']['win10']     = 'Windows 10';
$config->zahost->os->type['windows']['win7']      = 'Windows 7';
$config->zahost->os->type['windows']['winxp']     = 'Windows XP';
$config->zahost->os->type['linux']['ubuntu']      = 'Ubuntu';
$config->zahost->os->type['linux']['centos']      = 'CentOS';
$config->zahost->os->type['linux']['debian']      = 'Debian';

global $lang;
$config->zahost->search['module'] = 'zahost';
$config->zahost->search['fields']['name']            = $lang->zahost->name;
$config->zahost->search['fields']['id']              = $lang->zahost->id;
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

$config->vmTemplate = new stdclass();
$config->vmTemplate->os = new stdClass();
$config->vmTemplate->os->list = array();
$config->vmTemplate->os->list['windows'] = 'Windows';
$config->vmTemplate->os->list['linux']   = 'Linux';

$config->vmTemplate->os->cpu = array();
$config->vmTemplate->os->cpu['1']  = '1';
$config->vmTemplate->os->cpu['2']  = '2';
$config->vmTemplate->os->cpu['4']  = '4';
$config->vmTemplate->os->cpu['6']  = '6';
$config->vmTemplate->os->cpu['8']  = '8';
$config->vmTemplate->os->cpu['12'] = '12';
$config->vmTemplate->os->cpu['16'] = '16';

$config->vmTemplate->os->memory = array();
$config->vmTemplate->os->memory['512']   = '512MB';
$config->vmTemplate->os->memory['1024']  = '1GB';
$config->vmTemplate->os->memory['2048']  = '2GB';
$config->vmTemplate->os->memory['4096']  = '4GB';
$config->vmTemplate->os->memory['9120']  = '8GB';
$config->vmTemplate->os->memory['16384'] = '16GB';

$config->vmTemplate->os->disk = array();
$config->vmTemplate->os->disk['10240']  = '10GB';
$config->vmTemplate->os->disk['20480']  = '20GB';
$config->vmTemplate->os->disk['40960']  = '40GB';
$config->vmTemplate->os->disk['61440']  = '60GB';
$config->vmTemplate->os->disk['81920']  = '80GB';
$config->vmTemplate->os->disk['102400'] = '100GB';
$config->vmTemplate->os->disk['204800'] = '200GB';
$config->vmTemplate->os->disk['307200'] = '300GB';

$config->vmTemplate->os->type = array();
$config->vmTemplate->os->type['windows']['winServer'] = 'Windows Server';
$config->vmTemplate->os->type['windows']['win11']     = 'Windows 11';
$config->vmTemplate->os->type['windows']['win10']     = 'Windows 10';
$config->vmTemplate->os->type['windows']['win7']      = 'Windows 7';
$config->vmTemplate->os->type['windows']['winxp']     = 'Windows XP';
$config->vmTemplate->os->type['linux']['ubuntu']      = 'Ubuntu';
$config->vmTemplate->os->type['linux']['centos']      = 'CentOS';
$config->vmTemplate->os->type['linux']['debian']      = 'Debian';

$config->vmTemplate->search['module'] = 'vmTemplate';
$config->vmTemplate->search['fields']['id']         = $lang->zahost->id;
$config->vmTemplate->search['fields']['name']       = $lang->zahost->name;
$config->vmTemplate->search['fields']['cpuCoreNum'] = $lang->zahost->cpuCores;
$config->vmTemplate->search['fields']['memorySize'] = $lang->zahost->memory;
$config->vmTemplate->search['fields']['diskSize']   = $lang->zahost->diskSize;
$config->vmTemplate->search['fields']['osType']     = $lang->zahost->vmTemplate->osType;
$config->vmTemplate->search['fields']['osVersion']  = $lang->zahost->vmTemplate->osVersion;

$config->vmTemplate->search['params']['id']         = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->vmTemplate->search['params']['name']       = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->vmTemplate->search['params']['memorySize'] = array('operator' => '=', 'control' => 'select',  'values' => array('' => '') + $config->vmTemplate->os->memory);
$config->vmTemplate->search['params']['diskSize']   = array('operator' => '=', 'control' => 'select',  'values' => array('' => '') + $config->vmTemplate->os->disk);
$config->vmTemplate->search['params']['osType']     = array('operator' => '=', 'control' => 'select',  'values' => array('' => '') + $config->vmTemplate->os->type['windows'] + $config->vmTemplate->os->type['linux']);
$config->vmTemplate->search['params']['cpuCoreNum'] = array('operator' => '=', 'control' => 'select',  'values' => array('' => '') + $config->vmTemplate->os->cpu);
