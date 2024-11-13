<?php
global $lang, $config;

$config->backup = new stdClass();
$config->backup->dtable = new stdclass();
$config->backup->dtable->fieldList['create_time']['name']       = 'create_time';
$config->backup->dtable->fieldList['create_time']['title']      = $lang->instance->backup->date;
$config->backup->dtable->fieldList['create_time']['type']       = 'desc';
$config->backup->dtable->fieldList['create_time']['sortType']   = false;
$config->backup->dtable->fieldList['create_time']['hint']       = true;
$config->backup->dtable->fieldList['create_time']['formatDate'] = 'YYYY-MM-dd hh:mm:ss';
$config->backup->dtable->fieldList['create_time']['width']      = '120';

$config->backup->dtable->fieldList['operator']['name']     = 'operator';
$config->backup->dtable->fieldList['operator']['title']    =  $lang->instance->backup->operator;
$config->backup->dtable->fieldList['operator']['type']     = 'desc';
$config->backup->dtable->fieldList['operator']['sortType'] = false;
$config->backup->dtable->fieldList['operator']['hint']     = true;
$config->backup->dtable->fieldList['operator']['width']    = '100';

$config->backup->dtable->fieldList['dbStatus']['name']     = 'status';
$config->backup->dtable->fieldList['dbStatus']['title']    =  $lang->instance->backup->dbStatus;
$config->backup->dtable->fieldList['dbStatus']['type']     = 'desc';
$config->backup->dtable->fieldList['dbStatus']['sortType'] = false;
$config->backup->dtable->fieldList['dbStatus']['hint']     = true;
$config->backup->dtable->fieldList['dbStatus']['width']    = '80';

$config->backup->dtable->fieldList['backupSize']['name']     = 'backupSize';
$config->backup->dtable->fieldList['backupSize']['title']    =  $lang->instance->backup->backupSize;
$config->backup->dtable->fieldList['backupSize']['type']     = 'desc';
$config->backup->dtable->fieldList['backupSize']['sortType'] = false;
$config->backup->dtable->fieldList['backupSize']['hint']     = true;
$config->backup->dtable->fieldList['backupSize']['width']    = '100';

$config->backup->dtable->fieldList['restoreDate']['name']     = 'latestRestoreTime';
$config->backup->dtable->fieldList['restoreDate']['title']    =  $lang->instance->backup->restoreDate;
$config->backup->dtable->fieldList['restoreDate']['type']     = 'desc';
$config->backup->dtable->fieldList['restoreDate']['sortType'] = false;
$config->backup->dtable->fieldList['restoreDate']['hint']     = true;
$config->backup->dtable->fieldList['restoreDate']['width']    = '120';

$config->backup->dtable->fieldList['restoreStatus']['name']     = 'latestRestoreStatus';
$config->backup->dtable->fieldList['restoreStatus']['title']    =  $lang->instance->backup->restoreStatus;
$config->backup->dtable->fieldList['restoreStatus']['type']     = 'desc';
$config->backup->dtable->fieldList['restoreStatus']['sortType'] = false;
$config->backup->dtable->fieldList['restoreStatus']['hint']     = true;
$config->backup->dtable->fieldList['restoreStatus']['width']    = '80';

$config->backup->dtable->fieldList['actions']['name']     = 'actions';
$config->backup->dtable->fieldList['actions']['title']    =  $lang->instance->backup->action;
$config->backup->dtable->fieldList['actions']['type']     = 'actions';
$config->backup->dtable->fieldList['actions']['hint']     = true;
$config->backup->dtable->fieldList['actions']['menu']     = array('restore');

$config->backup->dtable->fieldList['actions']['list']['restore']['icon'] = 'icon-restart';
$config->backup->dtable->fieldList['actions']['list']['restore']['hint'] = $lang->instance->restore->common;
$config->backup->dtable->fieldList['actions']['list']['restore']['url'] = helper::createLink('instance', 'ajaxRestore', 'instanceID={instanceId}&backupName={name}');
$config->backup->dtable->fieldList['actions']['list']['restore']['className'] = 'ajax-submit';