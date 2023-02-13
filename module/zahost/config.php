<?php
$config->zahost->create = new stdclass();
$config->zahost->create->requiredFields = 'name,hostType,extranet,cpuCores,memory,diskSize,vsoft';
$config->zahost->create->ipFields       = 'extranet';

$config->zahost->defaultPort = '55001';

$config->zahost->edit = new stdclass();
$config->zahost->edit->requiredFields = 'name,hostType,cpuCores,memory,diskSize,vsoft';

$config->zahost->imageListUrl = 'https://pkg.qucheng.com/zenagent/list.json';

$config->zahost->cpuCoreList = array(1 => 1, 2 => 2, 4 => 4, 6 => 6, 8 => 8, 10 => 10, 12 => 12, 16 => 16, 24 => 24, 32 => 32, 64 => 64);

$config->zahost->initBash = 'curl -sSL https://pkg.qucheng.com/zenagent/zagent.sh | bash /dev/stdin -k %s -z %s';

global $lang;
$config->zahost->search['module'] = 'zahost';
$config->zahost->search['fields']['name']            = $lang->zahost->name;
$config->zahost->search['fields']['id']              = $lang->zahost->id;
$config->zahost->search['fields']['extranet']        = $lang->zahost->IP;
$config->zahost->search['fields']['cpuCores']        = $lang->zahost->cpuCores;
$config->zahost->search['fields']['memory']          = $lang->zahost->memory;
$config->zahost->search['fields']['diskSize']        = $lang->zahost->diskSize;
$config->zahost->search['fields']['hostType']        = $lang->zahost->type;
$config->zahost->search['fields']['vsoft']           = $lang->zahost->vsoft;
$config->zahost->search['fields']['status']          = $lang->zahost->status;
$config->zahost->search['fields']['createdBy']       = $lang->zahost->createdBy;
$config->zahost->search['fields']['createdDate']     = $lang->zahost->createdDate;
$config->zahost->search['fields']['registerDate']    = $lang->zahost->registerDate;
$config->zahost->search['fields']['editedBy']        = $lang->zahost->editedBy;
$config->zahost->search['fields']['editedDate']      = $lang->zahost->editedDate;

$config->zahost->search['params']['name']            = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['id']              = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['extranet']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['cpuCores']        = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['memory']          = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['diskSize']        = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['hostType']        = array('operator' => '=', 'control' => 'select', 'values' => $lang->zahost->zaHostTypeList);
$config->zahost->search['params']['vsoft']           = array('operator' => '=', 'control' => 'select',  'values' => $lang->zahost->softwareList);
$config->zahost->search['params']['status']          = array('operator' => '=', 'control' => 'select',  'values' => $lang->zahost->statusList);
$config->zahost->search['params']['instanceNum']     = array('operator' => '=', 'control' => 'input',  'values' => '');
$config->zahost->search['params']['createdBy']       = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->zahost->search['params']['createdDate']     = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->zahost->search['params']['registerDate']    = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->zahost->search['params']['editedBy']        = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->zahost->search['params']['editedDate']      = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');

$config->zahost->editor = new stdclass();
$config->zahost->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->zahost->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->zahost->editor->view   = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

$config->zahost->automation = new stdclass();
$config->zahost->automation->zenAgentURL   = 'https://github.com/easysoft/zenagent/blob/main/guide/deploy/index.md';
$config->zahost->automation->ztfURL        = 'https://ztf.im/';
$config->zahost->automation->kvmURL        = 'https://www.linux-kvm.org/page/Documents';
$config->zahost->automation->nginxURL      = 'http://nginx.org/en/docs/';
$config->zahost->automation->novncURL      = 'https://novnc.com/info.html';
$config->zahost->automation->websockifyURL = 'https://github.com/novnc/websockify';
