<?php
global $lang, $app;
$app->loadLang('compile');

$config->job = new stdclass();
$config->job->create = new stdclass();
$config->job->edit   = new stdclass();
$config->job->create->requiredFields = 'name,repo,engine,server,pipeline';
$config->job->edit->requiredFields   = 'name,repo,server,pipeline';

/* Search config. */
$config->job->search['module']                = 'job';
$config->job->search['fields']['id']          = $lang->idAB;
$config->job->search['fields']['name']        = $lang->job->name;
$config->job->search['fields']['lastStatus']  = $lang->job->lastStatus;
$config->job->search['fields']['product']     = $lang->job->product;
$config->job->search['fields']['repo']        = $lang->job->repo;
$config->job->search['fields']['engine']      = $lang->job->engine;
$config->job->search['fields']['frame']       = $lang->job->frame;
$config->job->search['fields']['triggerType'] = $lang->job->triggerType;
$config->job->search['fields']['lastExec']    = $lang->job->lastExec;

$config->job->search['params']['id']          = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->job->search['params']['name']        = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->job->search['params']['lastStatus']  = array('operator' => '=', 'control' => 'select', 'values' => $lang->compile->statusList);
$config->job->search['params']['product']     = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->job->search['params']['repo']        = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->job->search['params']['engine']      = array('operator' => '=', 'control' => 'select', 'values' => $lang->job->engineList);
$config->job->search['params']['frame']       = array('operator' => '=', 'control' => 'select', 'values' => $lang->job->frameList);
$config->job->search['params']['triggerType'] = array('operator' => '=', 'control' => 'select', 'values' => $lang->job->triggerTypeList);
$config->job->search['params']['lastExec']    = array('operator' => '=', 'control' => 'date', 'values' => '');
