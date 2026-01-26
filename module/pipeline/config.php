<?php
global $lang, $app;
$app->loadLang('compile');

$config->pipeline = new stdclass();
$config->pipeline->create = new stdclass();
$config->pipeline->edit   = new stdclass();
$config->pipeline->create->requiredFields = 'name';
$config->pipeline->edit->requiredFields   = 'name';

$config->pipeline->editor = new stdclass();
$config->pipeline->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');

/* Search config. */
$config->pipeline->search['module']           = 'pipeline';
$config->pipeline->search['fields']['id']     = $lang->idAB;
$config->pipeline->search['fields']['name']   = $lang->pipeline->name;
$config->pipeline->search['fields']['status'] = $lang->pipeline->status;
$config->pipeline->search['fields']['repoID'] = $lang->pipeline->repo;

$config->pipeline->search['params']['id']     = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->pipeline->search['params']['name']   = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->pipeline->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $lang->pipeline->statusList);
$config->pipeline->search['params']['repoID'] = array('operator' => '=', 'control' => 'select', 'values' => array());
