<?php
global $app, $lang;
$app->loadLang('execution');

$config->block->execution = new stdclass();
$config->block->execution->dtable = new stdclass();
$config->block->execution->dtable->fieldList = array();
$config->block->execution->dtable->fieldList['name']['name']  = 'name';
$config->block->execution->dtable->fieldList['name']['title'] = $lang->execution->name;
$config->block->execution->dtable->fieldList['name']['link']  = array('module' => 'execution', 'method' => 'task', 'params' => 'executionID={id}');
$config->block->execution->dtable->fieldList['name']['type']  = 'title';
$config->block->execution->dtable->fieldList['name']['sort']  = true;

$config->block->execution->dtable->fieldList['end']['name']  = 'end';
$config->block->execution->dtable->fieldList['end']['title'] = $lang->execution->end;
$config->block->execution->dtable->fieldList['end']['type']  = 'date';
$config->block->execution->dtable->fieldList['end']['sort']  = 'date';

$config->block->execution->dtable->fieldList['status']['name']      = 'status';
$config->block->execution->dtable->fieldList['status']['title']     = $lang->execution->status;
$config->block->execution->dtable->fieldList['status']['type']      = 'status';
$config->block->execution->dtable->fieldList['status']['statusMap'] = $lang->execution->statusList;
$config->block->execution->dtable->fieldList['status']['sort']      = true;

$config->block->execution->dtable->fieldList['totalEstimate']['name']  = 'totalEstimate';
$config->block->execution->dtable->fieldList['totalEstimate']['title'] = $lang->execution->totalEstimate;
$config->block->execution->dtable->fieldList['totalEstimate']['type']  = 'number';
$config->block->execution->dtable->fieldList['totalEstimate']['sort']  = 'number';

$config->block->execution->dtable->fieldList['totalConsumed']['name']  = 'totalConsumed';
$config->block->execution->dtable->fieldList['totalConsumed']['title'] = $lang->execution->totalConsumed;
$config->block->execution->dtable->fieldList['totalConsumed']['type']  = 'number';
$config->block->execution->dtable->fieldList['totalConsumed']['sort']  = 'number';

$config->block->execution->dtable->fieldList['progress']['name']  = 'progress';
$config->block->execution->dtable->fieldList['progress']['title'] = $lang->execution->progress;
$config->block->execution->dtable->fieldList['progress']['type']  = 'progress';
$config->block->execution->dtable->fieldList['progress']['sort']  = 'number';

$config->block->execution->dtable->fieldList['burns']['name']  = 'burns';
$config->block->execution->dtable->fieldList['burns']['title'] = $lang->execution->burn;
$config->block->execution->dtable->fieldList['burns']['type']  = 'burn';
$config->block->execution->dtable->fieldList['burns']['sort']  = false;
