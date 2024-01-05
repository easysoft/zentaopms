<?php
global $lang, $app;
$app->loadLang('task');

$config->block->task = new stdclass();
$config->block->task->dtable = new stdclass();
$config->block->task->dtable->fieldList = array();
$config->block->task->dtable->fieldList['id']['name']  = 'id';
$config->block->task->dtable->fieldList['id']['title'] = $lang->idAB;
$config->block->task->dtable->fieldList['id']['type']  = 'int';
$config->block->task->dtable->fieldList['id']['fixed'] = 'left';
$config->block->task->dtable->fieldList['id']['sort']  = 'number';

$config->block->task->dtable->fieldList['name']['name']  = 'name';
$config->block->task->dtable->fieldList['name']['title'] = $lang->task->name;
$config->block->task->dtable->fieldList['name']['link']  = array('module' => 'task', 'method' => 'view', 'params' => 'taskID={id}');
$config->block->task->dtable->fieldList['name']['type']  = 'title';
$config->block->task->dtable->fieldList['name']['fixed'] = 'left';
$config->block->task->dtable->fieldList['name']['sort']  = true;

$config->block->task->dtable->fieldList['pri']['name']  = 'pri';
$config->block->task->dtable->fieldList['pri']['title'] = $lang->priAB;
$config->block->task->dtable->fieldList['pri']['type']  = 'pri';
$config->block->task->dtable->fieldList['pri']['sort']  = true;

$config->block->task->dtable->fieldList['status']['name']      = 'status';
$config->block->task->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->block->task->dtable->fieldList['status']['type']      = 'status';
$config->block->task->dtable->fieldList['status']['statusMap'] = $lang->task->statusList;
$config->block->task->dtable->fieldList['status']['sort']      = true;

$config->block->task->dtable->fieldList['deadline']['name']  = 'deadline';
$config->block->task->dtable->fieldList['deadline']['title'] = $lang->task->deadlineAB;
$config->block->task->dtable->fieldList['deadline']['type']  = 'string';
$config->block->task->dtable->fieldList['deadline']['sort']  = 'date';

$config->block->task->dtable->fieldList['estimate']['name']  = 'estimate';
$config->block->task->dtable->fieldList['estimate']['title'] = $lang->task->estimateAB;
$config->block->task->dtable->fieldList['estimate']['type']  = 'number';
$config->block->task->dtable->fieldList['estimate']['sort']  = 'number';

$config->block->task->dtable->fieldList['left']['name']  = 'left';
$config->block->task->dtable->fieldList['left']['title'] = $lang->task->leftAB;
$config->block->task->dtable->fieldList['left']['type']  = 'number';
$config->block->task->dtable->fieldList['left']['sort']  = 'number';

$config->block->task->dtable->fieldList['progress']['name']  = 'progress';
$config->block->task->dtable->fieldList['progress']['title'] = $lang->task->progressAB;
$config->block->task->dtable->fieldList['progress']['type']  = 'progress';
$config->block->task->dtable->fieldList['progress']['sort']  = 'number';

$config->block->task->dtable->short = new stdclass();
$config->block->task->dtable->short->fieldList['id']     = $config->block->task->dtable->fieldList['id'];
$config->block->task->dtable->short->fieldList['name']   = $config->block->task->dtable->fieldList['name'];
$config->block->task->dtable->short->fieldList['status'] = $config->block->task->dtable->fieldList['status'];
