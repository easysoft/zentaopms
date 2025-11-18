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

$config->task->actions->view['mainActions']   = array('assignTo', 'start', 'restart', 'recordWorkhour', 'pause', 'finish', 'activate', 'close', 'cancel');
$config->task->actions->view['suffixActions'] = array('edit', 'delete', 'view');

$config->task->exportFields   = str_replace('parent,', '', $config->task->exportFields);
$config->task->templateFields = "module,story,assignedTo,mode,name,desc,type,pri,estimate,estStarted,deadline";

$config->task->exportFields = '
    id, project, execution, module, story,
    name, desc, parent,
    type, pri,estStarted, realStarted, deadline, status,estimate, consumed, left,
    keywords,mailto, progress, mode,
    openedBy, openedDate, assignedTo, assignedDate,
    finishedBy, finishedDate, canceledBy, canceledDate,
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate, activatedDate, files
    ';
