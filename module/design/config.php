<?php
$config->design = new stdclass();
$config->design->editor = new stdclass();
$config->design->editor->create   = array('id' => 'desc',    'tools' => 'simpleTools');
$config->design->editor->edit     = array('id' => 'desc',    'tools' => 'simpleTools');
$config->design->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');

$config->design->create = new stdclass();
$config->design->edit   = new stdclass();
$config->design->create->requiredFields = 'name,type';
$config->design->edit->requiredFields   = 'name,type';

$config->design->affectedFixedNum = 7; 

global $lang;
$config->design->search['module']                = 'design';
$config->design->search['fields']['id']          = $lang->design->id;
$config->design->search['fields']['type']        = $lang->design->type;
$config->design->search['fields']['name']        = $lang->design->name;
$config->design->search['fields']['commit']      = $lang->design->submission;
$config->design->search['fields']['createdBy']   = $lang->design->createdBy;
$config->design->search['fields']['createdDate'] = $lang->design->createdDate;
$config->design->search['fields']['assignedTo']  = $lang->design->assignedTo;
$config->design->search['fields']['story']       = $lang->design->story;

$config->design->search['params']['type']        = array('operator' => '=', 'control' => 'select',  'values' => $lang->design->typeList);
$config->design->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->design->search['params']['commit']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->design->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->design->search['params']['createdDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->design->search['params']['assignedTo']  = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->design->search['params']['story']       = array('operator' => '=', 'control' => 'select',  'values' => '');
