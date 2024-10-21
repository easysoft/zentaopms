<?php
global $app, $lang;
$app->loadLang('job');

$config->compile->search['module']                = 'compile';
$config->compile->search['fields']['name']        = $lang->compile->name;
$config->compile->search['fields']['status']      = $lang->compile->status;
$config->compile->search['fields']['repo']        = $lang->job->repo;
$config->compile->search['fields']['engine']      = $lang->compile->buildType;
$config->compile->search['fields']['triggerType'] = $lang->job->triggerType;
$config->compile->search['fields']['createdDate'] = $lang->compile->atTime;

$config->compile->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->compile->search['params']['status']      = array('operator' => '=',       'control' => 'select', 'values' => $lang->compile->statusList);
$config->compile->search['params']['repo']        = array('operator' => '=',       'control' => 'select', 'values' => array());
$config->compile->search['params']['engine']      = array('operator' => '=',       'control' => 'select', 'values' => $lang->job->engineList);
$config->compile->search['params']['triggerType'] = array('operator' => 'include', 'control' => 'select', 'values' => $lang->job->triggerTypeList);
$config->compile->search['params']['createdDate'] = array('operator' => '=',       'control' => 'date',   'values' => '');
