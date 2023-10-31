<?php
$config->task->custom->batchCreateFields .= ',lane,region';

if(!isset($config->task->datatable)) $config->task->datatable = new stdclass();
$config->task->datatable->defaultField = array('id', 'pri', 'name', 'status', 'assignedTo', 'lane', 'finishedBy', 'estimate', 'consumed', 'left', 'progress', 'deadline', 'actions');

$config->task->dtable->fieldList['lane']['title']    = 'lane';
$config->task->dtable->fieldList['lane']['fixed']    = 'no';
$config->task->dtable->fieldList['lane']['width']    = '120';
$config->task->dtable->fieldList['lane']['sortType'] = false;

$config->task->dtable->fieldList['name']['data-toggle'] = 'modal';
$config->task->dtable->fieldList['name']['data-size']   = 'lg';

$config->task->view->operateList['main']   = array('assignTo', 'start', 'restart', 'recordWorkhour', 'pause', 'finish', 'activate', 'close', 'cancel');
$config->task->view->operateList['common'] = array('edit', 'delete', 'view');
