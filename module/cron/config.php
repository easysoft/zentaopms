<?php
$config->cron = new stdclass();
$config->cron->create = new stdclass();
$config->cron->edit   = new stdclass();
$config->cron->create->requiredFields = 'm,h,dom,mon,dow,command';
$config->cron->edit->requiredFields   = 'm,h,dom,mon,dow,command';

$config->cron->maxRunDays  = 8;
$config->cron->maxRunTime  = 65;
$config->cron->maxConsumer = 4;

$config->cron->showCommands = array();
$config->cron->showCommands['gitfox'] = array();
$config->cron->showCommands['gitfox'][] = 'moduleName=metric&methodName=updateDashboardMetricLib';
$config->cron->showCommands['gitfox'][] = 'moduleName=mail&methodName=asyncSend';
$config->cron->showCommands['gitfox'][] = 'moduleName=webhook&methodName=asyncSend';
$config->cron->showCommands['gitfox'][] = 'moduleName=admin&methodName=deleteLog';
$config->cron->showCommands['gitfox'][] = 'moduleName=errorlog&methodName=deleteLog';
$config->cron->showCommands['gitfox'][] = 'moduleName=backup&methodName=backup';

global $lang;
$config->cron->dtable = new stdclass();
$config->cron->dtable->fieldList = array();
$config->cron->dtable->fieldList['m']        = array('sortType' => false, 'type' => 'number',   'title' => $lang->cron->m);
$config->cron->dtable->fieldList['h']        = array('sortType' => false, 'type' => 'number',   'title' => $lang->cron->h);
$config->cron->dtable->fieldList['dom']      = array('sortType' => false, 'type' => 'number',   'title' => $lang->cron->dom);
$config->cron->dtable->fieldList['mon']      = array('sortType' => false, 'type' => 'number',   'title' => $lang->cron->mon);
$config->cron->dtable->fieldList['dow']      = array('sortType' => false, 'type' => 'number',   'title' => $lang->cron->dow);
$config->cron->dtable->fieldList['command']  = array('sortType' => false, 'type' => 'text',     'title' => $lang->cron->command);
$config->cron->dtable->fieldList['status']   = array('sortType' => false, 'type' => 'status',   'title' => $lang->cron->status, 'statusMap' => $lang->cron->statusList);
$config->cron->dtable->fieldList['remark']   = array('sortType' => false, 'type' => 'text',     'title' => $lang->cron->remark);
$config->cron->dtable->fieldList['lastTime'] = array('sortType' => false, 'type' => 'datetime', 'title' => $lang->cron->lastTime);
$config->cron->dtable->fieldList['actions']  = array('sortType' => false, 'width' => '110px',   'title' => $lang->actions, 'type' => 'html');

$config->cron->taskEditions['weekly-createcyclereport']           = ['max', 'ipd'];
$config->cron->taskEditions['effort-remindnotrecord']             = ['biz', 'max', 'ipd'];
$config->cron->taskEditions['execution-computetaskeffort']        = ['biz', 'max', 'ipd'];
$config->cron->taskEditions['auditplan-ajaxcreatecycleauditplan'] = ['max', 'ipd'];
$config->cron->taskEditions['ai-runtimeragents']                  = ['biz', 'max', 'ipd'];
$config->cron->taskEditions['aitask-exec']                        = ['biz', 'max', 'ipd'];
