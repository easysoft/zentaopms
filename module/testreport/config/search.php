<?php
global $lang;

$config->testreport->search['module'] = 'testreport';

$config->testreport->search['fields']['title']       = $lang->testreport->title;
$config->testreport->search['fields']['product']     = $lang->testreport->product;
$config->testreport->search['fields']['project']     = $lang->testreport->project;
$config->testreport->search['fields']['execution']   = $lang->testreport->execution;
$config->testreport->search['fields']['tasks']       = $lang->testreport->tasks;
$config->testreport->search['fields']['owner']       = $lang->testreport->owner;
$config->testreport->search['fields']['members']     = $lang->testreport->members;
$config->testreport->search['fields']['createdBy']   = $lang->testreport->createdBy;
$config->testreport->search['fields']['createdDate'] = $lang->testreport->createdDate;
$config->testreport->search['fields']['begin']       = $lang->testreport->begin;
$config->testreport->search['fields']['end']         = $lang->testreport->end;
$config->testreport->search['fields']['id']          = $lang->testreport->id;

$config->testreport->search['params']['title']       = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->testreport->search['params']['product']     = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->testreport->search['params']['project']     = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->testreport->search['params']['execution']   = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->testreport->search['params']['tasks']       = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->testreport->search['params']['owner']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->testreport->search['params']['members']     = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->testreport->search['params']['createdBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->testreport->search['params']['createdDate'] = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->testreport->search['params']['begin']       = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->testreport->search['params']['end']         = array('operator' => '=',       'control' => 'date',   'values' => '');
$config->testreport->search['params']['id']          = array('operator' => '=',       'control' => 'input',  'values' => '');
