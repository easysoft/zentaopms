<?php
global $lang, $app;
$app->loadLang('todo');
$app->loadLang('my');
$app->loadModuleConfig('my');

$config->block->todo = new stdclass();
$config->block->todo->dtable = new stdclass();
$config->block->todo->dtable->fieldList = array();
$config->block->todo->dtable->fieldList['id']['name']  = 'id';
$config->block->todo->dtable->fieldList['id']['title'] = $lang->idAB;
$config->block->todo->dtable->fieldList['id']['type']  = 'id';

$config->block->todo->dtable->fieldList['name']        = $config->my->todo->dtable->fieldList['name'];
$config->block->todo->dtable->fieldList['pri']         = $config->my->todo->dtable->fieldList['pri'];
$config->block->todo->dtable->fieldList['date']        = $config->my->todo->dtable->fieldList['date'];
$config->block->todo->dtable->fieldList['begin']       = $config->my->todo->dtable->fieldList['begin'];
$config->block->todo->dtable->fieldList['end']         = $config->my->todo->dtable->fieldList['end'];
$config->block->todo->dtable->fieldList['status']      = $config->my->todo->dtable->fieldList['status'];
$config->block->todo->dtable->fieldList['type']        = $config->my->todo->dtable->fieldList['type'];
$config->block->todo->dtable->fieldList['assignedBy']  = $config->my->todo->dtable->fieldList['assignedBy'];
