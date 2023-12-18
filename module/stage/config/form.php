<?php
$now = helper::now();

global $app, $config;

$config->stage->form = new stdclass();

$config->stage->form->create['name']        = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->stage->form->create['type']        = array('type' => 'string',   'required' => true,  'default' => '');
$config->stage->form->create['projectType'] = array('type' => 'string',   'required' => false, 'default' => '');
$config->stage->form->create['createdBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->stage->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
if(isset($config->setPercent) && $config->setPercent == 1) $config->stage->form->create['percent'] = array('type' => 'float', 'required' => true, 'default' => 0);

$config->stage->form->batchcreate['name']        = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim', 'base' => true);
$config->stage->form->batchcreate['type']        = array('type' => 'string',   'required' => false, 'default' => '');
$config->stage->form->batchcreate['projectType'] = array('type' => 'string',   'required' => false, 'default' => '');
$config->stage->form->batchcreate['createdBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->stage->form->batchcreate['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
if(isset($config->setPercent) && $config->setPercent == 1) $config->stage->form->batchcreate['percent'] = array('type' => 'float', 'required' => true, 'default' => 0);

$config->stage->form->edit['name']       = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->stage->form->edit['type']       = array('type' => 'string',   'required' => true,  'default' => '');
$config->stage->form->edit['editedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->stage->form->edit['editedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
if(isset($config->setPercent) && $config->setPercent == 1) $config->stage->form->edit['percent'] = array('type' => 'float', 'required' => true, 'default' => 0);

$config->stage->form->settype['lang']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->stage->form->settype['keys']   = array('type' => 'array',  'required' => false, 'default' => array());
$config->stage->form->settype['values'] = array('type' => 'array',  'required' => false, 'default' => array());
