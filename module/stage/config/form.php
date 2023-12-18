<?php
$now = helper::now();

global $app, $config;

$config->stage->form = new stdclass();

$config->stage->form->create['name']        = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->stage->form->create['type']        = array('type' => 'string',   'required' => true,  'default' => '');
$config->stage->form->create['projectType'] = array('type' => 'string',   'required' => false, 'default' => '');
$config->stage->form->create['createdBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->stage->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
if(isset($config->setPercent) && $config->setPercent == 1) $config->stage->form->create['percent'] = array('type' => 'int', 'required' => false, 'default' => 0);
