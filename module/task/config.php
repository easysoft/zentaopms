<?php
$config->task->batchCreate = 10;
$config->task->create->requiredFields      = 'name,estimate,type,pri';
$config->task->edit->requiredFields        = $config->task->create->requiredFields;
$config->task->start->requiredFields       = 'estimate';
$config->task->finish->requiredFields      = 'consumed';
$config->task->activate->requiredFields    = 'left';

$config->task->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->task->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');

$config->task->exportFields = '
    id, project, story,
    name, desc,
    type, pri,  deadline, status,estimate, consumed, left,
    mailto,
    openedBy, openedDate, assignedTo, assignedDate, 
    finishedBy, finishedDate, canceledBy, canceledDate,
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate,files
    ';
