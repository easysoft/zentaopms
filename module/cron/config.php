<?php
$config->cron = new stdclass();
$config->cron->create = new stdclass();
$config->cron->edit   = new stdclass();
$config->cron->create->requiredFields = 'm,h,dom,mon,dow,command';
$config->cron->edit->requiredFields   = 'm,h,dom,mon,dow,command';

$config->cron->maxRunDays  = 8;
$config->cron->maxRunTime  = 65;
$config->cron->maxConsumer = 2;

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
