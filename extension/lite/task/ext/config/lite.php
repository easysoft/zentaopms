<?php
$config->task->custom->batchCreateFields .= ',lane,region';
$config->task->datatable->defaultField = array('id', 'pri', 'name', 'status', 'assignedTo', 'lane', 'finishedBy', 'estimate', 'consumed', 'left', 'progress', 'deadline', 'actions');

$config->task->datatable->fieldList['lane']['title']    = 'lane';
$config->task->datatable->fieldList['lane']['fixed']    = 'no';
$config->task->datatable->fieldList['lane']['width']    = '120';
$config->task->datatable->fieldList['lane']['required'] = 'no';
$config->task->datatable->fieldList['lane']['sort']     = 'no';
