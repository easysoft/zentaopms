<?php
global $lang, $app;
$app->loadLang('execution');

$config->block->scrum = new stdclass();
$config->block->scrum->dtable = new stdclass();
$config->block->scrum->dtable->fieldList = array();
$config->block->scrum->dtable->fieldList['name']          = array('name' => 'name',          'title' => $lang->execution->name,          'type' => 'title' ,   'sortType' => true, 'flex' => 1, 'link' => array('module' => 'execution', 'method' => 'task', 'params' => 'executionID={id}'), 'className' => 'scrumName');
$config->block->scrum->dtable->fieldList['status']        = array('name' => 'status',        'title' => $lang->statusAB,                 'type' => 'status',   'sortType' => true, 'statusMap' => $lang->execution->statusList);
$config->block->scrum->dtable->fieldList['end']           = array('name' => 'end',           'title' => $lang->execution->end,           'type' => 'date',     'sortType' => true);
$config->block->scrum->dtable->fieldList['totalEstimate'] = array('name' => 'totalEstimate', 'title' => $lang->execution->totalEstimate, 'type' => 'string',   'sortType' => true);
$config->block->scrum->dtable->fieldList['totalConsumed'] = array('name' => 'totalConsumed', 'title' => $lang->execution->totalConsumed, 'type' => 'string',   'sortType' => true);
$config->block->scrum->dtable->fieldList['totalLeft']     = array('name' => 'totalLeft',     'title' => $lang->execution->totalLeft,     'type' => 'string',   'sortType' => true);
$config->block->scrum->dtable->fieldList['progress']      = array('name' => 'progress',      'title' => $lang->execution->progress,      'type' => 'progress', 'sortType' => true);
$config->block->scrum->dtable->fieldList['burns']         = array('name' => 'burns',         'title' => $lang->execution->burn,          'type' => 'burn',     'sortType' => true);
