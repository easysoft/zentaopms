<?php
$config->user = new stdclass();
$config->user->create = new stdclass();
$config->user->edit   = new stdclass();

$config->user->create->requiredFields = 'account,realname,visions,password';
$config->user->edit->requiredFields   = 'account,realname,visions';

$config->user->availableBatchCreateFields = 'dept,email,gender,commiter,join,skype,qq,dingding,weixin,mobile,slack,whatsapp,phone,address,zipcode';
$config->user->availableBatchEditFields   = 'dept,email,commiter,skype,qq,dingding,weixin,mobile,slack,whatsapp,phone,address,zipcode';

$config->user->custom = new stdclass();
$config->user->custom->batchCreateFields = 'dept,join,email,gender';
$config->user->custom->batchEditFields   = 'dept,join,email,commiter';

$config->user->contactField         = 'mobile,phone,qq,dingding,weixin,skype,whatsapp,slack';
$config->user->failTimes            = 6;
$config->user->lockMinutes          = 10;
$config->user->batchCreate          = 10;
$config->user->resetPasswordTimeout = 3;

$config->user->loginImg = array();
$config->user->loginImg['logo'] = 'zt-login-logo.svg';
$config->user->loginImg['bg']   = 'zt-login-bg.svg';
$config->user->loginImg['ai']   = 'zt-login-ai.svg';

$config->user->defaultFields['todo']      = array('id', 'name', 'pri', 'date', 'begin', 'end', 'status', 'type');
$config->user->defaultFields['task']      = array('id', 'name', 'pri', 'status', 'execution', 'deadline', 'estimate', 'consumed', 'left');
$config->user->defaultFields['story']     = array('id', 'title', 'pri', 'status', 'product', 'plan', 'openedBy', 'estimate', 'stage');
$config->user->defaultFields['bug']       = array('id', 'title', 'severity', 'pri', 'type', 'openedBy', 'resolvedBy', 'resolution');
$config->user->defaultFields['testtask']  = array('id', 'title', 'execution', 'build', 'begin', 'end', 'status');
$config->user->defaultFields['testcase']  = array('id', 'title', 'pri', 'type', 'status', 'openedBy', 'lastRunner', 'lastRunDate', 'lastRunResult');
$config->user->defaultFields['execution'] = array('id', 'name', 'status', 'role', 'begin', 'end', 'join', 'hours');
