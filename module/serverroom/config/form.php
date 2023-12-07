<?php
global $app;
$config->serverroom->form = new stdclass();
$config->serverroom->form->create['name']        = array('type' => 'string', 'required' => true,  'default' => '');
$config->serverroom->form->create['bandwidth']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->serverroom->form->create['city']        = array('type' => 'string', 'required' => false, 'default' => '');
$config->serverroom->form->create['line']        = array('type' => 'string', 'required' => true,  'default' => '');
$config->serverroom->form->create['provider']    = array('type' => 'string', 'required' => false, 'default' => '');
$config->serverroom->form->create['owner']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->serverroom->form->create['createdBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->serverroom->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->serverroom->form->edit['name']       = array('type' => 'string', 'required' => true,  'default' => '');
$config->serverroom->form->edit['bandwidth']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->serverroom->form->edit['city']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->serverroom->form->edit['line']       = array('type' => 'string', 'required' => true,  'default' => '');
$config->serverroom->form->edit['provider']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->serverroom->form->edit['owner']      = array('type' => 'string', 'required' => false, 'default' => '');
$config->serverroom->form->edit['editedBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->serverroom->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
