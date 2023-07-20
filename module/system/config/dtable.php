<?php
global $lang, $app;
$app->loadLang('instance');

$config->system->dtable = new stdclass();

$config->system->dtable->dbList = new stdclass();

$config->system->dtable->dbList->fieldList['name']['title'] = $lang->system->dbName;
$config->system->dtable->dbList->fieldList['name']['group'] = 1;

$config->system->dtable->dbList->fieldList['dbType']['title'] = $lang->system->dbType;
$config->system->dtable->dbList->fieldList['dbType']['name']  = 'db_type';
$config->system->dtable->dbList->fieldList['dbType']['group'] = 2;

$config->system->dtable->dbList->fieldList['dbStatus']['title'] = $lang->system->dbStatus;
$config->system->dtable->dbList->fieldList['dbStatus']['name']  = 'status';
$config->system->dtable->dbList->fieldList['dbStatus']['map']   = $lang->instance->statusList;

$config->system->dtable->dbList->fieldList['actions']['name']  = 'actions';
$config->system->dtable->dbList->fieldList['actions']['title'] = $lang->actions;
$config->system->dtable->dbList->fieldList['actions']['type']  = 'actions';
$config->system->dtable->dbList->fieldList['actions']['menu']  = array('management');

$config->system->dtable->dbList->fieldList['actions']['list']['management']['icon'] = 'cog-outline';
$config->system->dtable->dbList->fieldList['actions']['list']['management']['hint'] = $lang->system->management;
