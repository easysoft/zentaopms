<?php
global $lang, $app;
$app->loadLang('compile');

$config->pipeline = new stdclass();
$config->pipeline->create = new stdclass();
$config->pipeline->edit   = new stdclass();
$config->pipeline->create->requiredFields = 'name,repo,engine,server,pipeline';
$config->pipeline->edit->requiredFields   = 'name,repo,server,pipeline';

/* Search config. */
$config->pipeline->search['module']                = 'pipeline';
$config->pipeline->search['fields']['id']          = $lang->idAB;
$config->pipeline->search['fields']['name']        = $lang->pipeline->name;
$config->pipeline->search['fields']['lastStatus']  = $lang->pipeline->lastStatus;
$config->pipeline->search['fields']['product']     = $lang->pipeline->product;
$config->pipeline->search['fields']['repo']        = $lang->pipeline->repo;
$config->pipeline->search['fields']['engine']      = $lang->pipeline->engine;
$config->pipeline->search['fields']['frame']       = $lang->pipeline->frame;
$config->pipeline->search['fields']['triggerType'] = $lang->pipeline->triggerType;
$config->pipeline->search['fields']['lastExec']    = $lang->pipeline->lastExec;

$config->pipeline->search['params']['id']          = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->pipeline->search['params']['name']        = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->pipeline->search['params']['lastStatus']  = array('operator' => '=', 'control' => 'select', 'values' => $lang->compile->statusList);
$config->pipeline->search['params']['product']     = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->pipeline->search['params']['repo']        = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->pipeline->search['params']['engine']      = array('operator' => '=', 'control' => 'select', 'values' => $lang->pipeline->engineList);
$config->pipeline->search['params']['frame']       = array('operator' => '=', 'control' => 'select', 'values' => $lang->pipeline->frameList);
$config->pipeline->search['params']['triggerType'] = array('operator' => '=', 'control' => 'select', 'values' => $lang->pipeline->triggerTypeList);
$config->pipeline->search['params']['lastExec']    = array('operator' => '=', 'control' => 'date', 'values' => '');
