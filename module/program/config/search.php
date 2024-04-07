<?php
global $lang;
$config->program->search['module']                   = 'program';
$config->program->search['fields']['name']           = $lang->program->name;
$config->program->search['fields']['PM']             = $lang->program->PM;
$config->program->search['fields']['openedDate']     = $lang->program->openedDate;
$config->program->search['fields']['status']         = $lang->program->status;
$config->program->search['fields']['openedBy']       = $lang->program->openedBy;
$config->program->search['fields']['begin']          = $lang->program->begin;
$config->program->search['fields']['end']            = $lang->program->end;
$config->program->search['fields']['realBegan']      = $lang->program->realBegan;
$config->program->search['fields']['realEnd']        = $lang->program->realEnd;
$config->program->search['fields']['closedDate']     = $lang->program->closedDate;
$config->program->search['fields']['lastEditedDate'] = $lang->program->lastEditedDate;
$config->program->search['fields']['desc']           = $lang->program->desc;

/* This is an ordered array. */
$config->program->search['params']['name']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->program->search['params']['status']         = array('operator' => '=',       'control' => 'select', 'values' => $lang->program->statusList);
$config->program->search['params']['PM']             = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->program->search['params']['openedDate']     = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['openedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->program->search['params']['begin']          = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['end']            = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['realBegan']      = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['realEnd']        = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['closedDate']     = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['lastEditedDate'] = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->program->search['params']['desc']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
