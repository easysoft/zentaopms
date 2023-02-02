<?php
$config->project->list->exportFields = 'id,code,name,status,PM,desc';
$config->project->datatable->defaultField = array('id', 'name', 'status', 'PM', 'begin', 'end', 'progress', 'actions');

unset($config->project->datatable->fieldList['budget']);

unset($config->project->search['fields']['hasProduct']);
unset($config->project->search['fields']['parent']);
unset($config->project->search['fields']['model']);
