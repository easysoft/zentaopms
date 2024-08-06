<?php
global $lang;
$config->file->search['module']               = 'file';
$config->file->search['fields']['title']      = $lang->file->attachmentName;
$config->file->search['fields']['objectType'] = $lang->file->sourceObject;
$config->file->search['fields']['extension']  = $lang->file->extension;

$config->file->search['fields']['id']         = $lang->idAB;
$config->file->search['fields']['objectID']   = $lang->file->sourceID;
$config->file->search['fields']['addedBy']    = $lang->file->addedBy;
$config->file->search['fields']['addedDate']  = $lang->file->addedDate;

$config->file->search['params']['title']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->file->search['params']['objectType'] = array('operator' => '=',       'control' => 'select', 'values' => array());
$config->file->search['params']['extension']  = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->file->search['params']['id']         = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->file->search['params']['objectName'] = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->file->search['params']['addedBy']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->file->search['params']['addedDate']  = array('operator' => '=',       'control' => 'date',   'values' => '');
