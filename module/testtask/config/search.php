<?php
global $lang;

$config->testtask->search['module'] = 'executionTesttask';

$config->testtask->search['fields']['name']             = $lang->testtask->name;
$config->testtask->search['fields']['type']             = $lang->testtask->type;
$config->testtask->search['fields']['status']           = $lang->testtask->status;
$config->testtask->search['fields']['product']          = $lang->testtask->product;
$config->testtask->search['fields']['project']          = $lang->testtask->project;
$config->testtask->search['fields']['execution']        = $lang->testtask->execution;
$config->testtask->search['fields']['owner']            = $lang->testtask->owner;
$config->testtask->search['fields']['pri']              = $lang->testtask->pri;
$config->testtask->search['fields']['createdDate']      = $lang->testtask->createdDate;
$config->testtask->search['fields']['begin']            = $lang->testtask->begin;
$config->testtask->search['fields']['end']              = $lang->testtask->end;
$config->testtask->search['fields']['realBegan']        = $lang->testtask->realBegan;
$config->testtask->search['fields']['realFinishedDate'] = $lang->testtask->realFinishedDate;
$config->testtask->search['fields']['id']               = $lang->testtask->id;

$config->testtask->search['params']['name']             = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->testtask->search['params']['type']             = array('operator' => 'include', 'control' => 'select', 'values' => $lang->testtask->typeList);
$config->testtask->search['params']['status']           = array('operator' => '=', 'control' => 'select', 'values' => $lang->testtask->statusList);
$config->testtask->search['params']['product']          = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->testtask->search['params']['project']          = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->testtask->search['params']['execution']        = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->testtask->search['params']['owner']            = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->testtask->search['params']['pri']              = array('operator' => '=', 'control' => 'select', 'values' => $lang->testtask->priList);
$config->testtask->search['params']['createdDate']      = array('operator' => '=', 'control' => 'date', 'values' => '');
$config->testtask->search['params']['begin']            = array('operator' => '=', 'control' => 'date', 'values' => '');
$config->testtask->search['params']['end']              = array('operator' => '=', 'control' => 'date', 'values' => '');
$config->testtask->search['params']['realBegan']        = array('operator' => '=', 'control' => 'date', 'values' => '');
$config->testtask->search['params']['realFinishedDate'] = array('operator' => '=', 'control' => 'date', 'values' => '');
$config->testtask->search['params']['id']               = array('operator' => '=', 'control' => 'input', 'values' => '');
