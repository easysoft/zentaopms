<?php
$config->project->create->requiredFields = str_replace(',workflowGroup', '', $config->project->create->requiredFields);
$config->project->edit->requiredFields   = str_replace(',workflowGroup', '', $config->project->edit->requiredFields);

$config->project->list->exportFields = 'id,code,name,status,PM,desc';

if(!isset($config->project->datatable)) $config->project->datatable = new stdclass();
$config->project->dtable->defaultField = array('id', 'name', 'status', 'PM', 'begin', 'end', 'progress', 'actions');

unset($config->project->dtable->fieldList['budget']);

unset($config->project->search['fields']['hasProduct']);
unset($config->project->search['fields']['parent']);
unset($config->project->search['fields']['model']);

$config->project->dtable->team->fieldList['role']['align'] = 'left';
unset($config->project->dtable->team->fieldList['join']);
unset($config->project->dtable->team->fieldList['days']);
unset($config->project->dtable->team->fieldList['hours']);
unset($config->project->dtable->team->fieldList['total']);
unset($config->project->dtable->team->fieldList['limited']);

$config->project->dtable->fieldList['actions']['menu'] = array(array('start|activate|close', 'other' => array('suspend', 'activate|close')), 'edit', 'group', 'whitelist', 'delete');
