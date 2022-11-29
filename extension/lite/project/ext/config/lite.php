<?php
$config->project->list->exportFields = 'id,code,name,status,PM,desc';
$config->project->datatable->defaultField = array('id', 'name', 'status', 'PM', 'begin', 'end', 'progress', 'actions');
unset($config->project->datatable->fieldList['budget']);
