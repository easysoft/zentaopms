<?php
global $lang;

$config->account->search['module'] = 'account';
$config->account->search['fields']['name']        = $lang->account->name;
$config->account->search['fields']['id']          = 'ID';
$config->account->search['fields']['provider']    = $lang->account->provider;
$config->account->search['fields']['account']     = $lang->account->account;
$config->account->search['fields']['email']       = $lang->account->email;
$config->account->search['fields']['mobile']      = $lang->account->mobile;
$config->account->search['fields']['createdBy']   = $lang->account->createdBy;
$config->account->search['fields']['createdDate'] = $lang->account->createdDate;
$config->account->search['fields']['editedBy']    = $lang->account->editedBy;
$config->account->search['fields']['editedDate']  = $lang->account->editedDate;

$config->account->search['params']['name']        =  array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->account->search['params']['id']          =  array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->account->search['params']['provider']    =  array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->account->search['params']['account']     =  array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->account->search['params']['email']       =  array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->account->search['params']['mobile']      =  array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->account->search['params']['createdBy']   =  array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->account->search['params']['createdDate'] =  array('operator' => '=', 'control' => 'input',   'values' => '', 'class' => 'date');
$config->account->search['params']['editedBy']    =  array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->account->search['params']['editedDate']  =  array('operator' => '=', 'control' => 'input',   'values' => '', 'class' => 'date');
