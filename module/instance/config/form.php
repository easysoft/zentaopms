<?php
global $app;
$config->instance->form = new stdclass();
$config->instance->form->create = array();
$config->instance->form->create['name']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->create['url']         = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->create['account']     = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->create['password']    = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->create['createdBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->instance->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
$config->instance->form->create['private']     = array('type' => 'string', 'required' => false, 'default' => uniqid());

$config->instance->form->edit = array();
$config->instance->form->edit['name']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->edit['url']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->edit['account']    = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->edit['password']   = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->instance->form->edit['editedBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->instance->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
