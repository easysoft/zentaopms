<?php
$config->task->custom->batchCreateFields .= ',lane,region';

if(!isset($config->task->datatable)) $config->task->datatable = new stdclass();
$config->task->datatable->defaultField = array('id', 'pri', 'name', 'status', 'assignedTo', 'lane', 'finishedBy', 'estimate', 'consumed', 'left', 'progress', 'deadline', 'actions');

$config->task->dtable->fieldList['lane']['title']    = 'lane';
$config->task->dtable->fieldList['lane']['fixed']    = 'no';
$config->task->dtable->fieldList['lane']['width']    = '120';
$config->task->dtable->fieldList['lane']['sortType'] = false;
