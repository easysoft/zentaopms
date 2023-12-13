<?php
$now = helper::now();

global $app, $config;

$config->design->form = new stdclass();

$config->design->form->create['name']        = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->design->form->create['type']        = array('type' => 'string',   'required' => true,  'default' => '');
$config->design->form->create['product']     = array('type' => 'int',      'required' => false, 'default' => 0);
$config->design->form->create['project']     = array('type' => 'int',      'required' => false, 'default' => 0);
$config->design->form->create['story']       = array('type' => 'int',      'required' => false, 'default' => 0);
$config->design->form->create['desc']        = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->design->form->create['version']     = array('type' => 'int',      'required' => false, 'default' => 1);
$config->design->form->create['createdBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->design->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
