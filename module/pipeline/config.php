<?php
global $lang, $app;

$config->pipeline = new stdclass();
$config->pipeline->create = new stdclass();
$config->pipeline->edit   = new stdclass();
$config->pipeline->create->requiredFields = 'name';
$config->pipeline->edit->requiredFields   = 'name,defaultBranch';
$config->pipeline->import = new stdclass();
$config->pipeline->import->requiredFields = 'providerID,name';

$config->pipeline->groupPrivs = array();
$config->pipeline->groupPrivs['import'] = 'create';

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

$config->pipeline->execution = new stdclass();
$config->pipeline->execution->search['module']                = 'pipelineexec';
$config->pipeline->execution->search['fields']['id']          = $lang->idAB;
$config->pipeline->execution->search['fields']['ref']         = $lang->pipeline->branch;
$config->pipeline->execution->search['fields']['name']        = $lang->pipeline->name;
$config->pipeline->execution->search['fields']['status']      = $lang->pipeline->status;
$config->pipeline->execution->search['fields']['createdBy']   = $lang->pipeline->triggerPerson;
$config->pipeline->execution->search['fields']['trigger']     = $lang->pipeline->triggerType;
$config->pipeline->execution->search['fields']['repoID']      = $lang->pipeline->repo;
$config->pipeline->execution->search['fields']['duration']    = $lang->pipeline->duration;
$config->pipeline->execution->search['fields']['createdDate'] = $lang->pipeline->triggerDate;

$config->pipeline->execution->search['params']['id']          = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->pipeline->execution->search['params']['name']        = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->pipeline->execution->search['params']['ref']         = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->pipeline->execution->search['params']['status']      = array('operator' => '=', 'control' => 'select', 'values' => $lang->pipeline->execStatusList);
$config->pipeline->execution->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->pipeline->execution->search['params']['trigger']     = array('operator' => '=', 'control' => 'select', 'values' => $lang->pipeline->triggerTypeList);
$config->pipeline->execution->search['params']['repoID']      = array('operator' => '=', 'control' => 'select', 'values' => array());
$config->pipeline->execution->search['params']['duration']    = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->pipeline->execution->search['params']['createdDate'] = array('operator' => '=', 'control' => 'datetime', 'values' => '');
