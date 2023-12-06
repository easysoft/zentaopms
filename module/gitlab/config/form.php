<?php

$config->gitlab->form = new stdclass();

$config->gitlab->form->create = common::formConfig('gitlab', 'create');

$config->gitlab->form->create['name']  = array('type' => 'string',   'required' => true, 'default' => '');
$config->gitlab->form->create['url']   = array('type' => 'string',   'required' => true, 'default' => '');
$config->gitlab->form->create['token'] = array('type' => 'string',   'required' => true,  'default' => '');

$config->gitlab->form->edit = common::formConfig('gitlab', 'edit');

$config->gitlab->form->edit['name']  = array('type' => 'string',   'required' => true, 'default' => '');
$config->gitlab->form->edit['url']   = array('type' => 'string',   'required' => true, 'default' => '');
$config->gitlab->form->edit['token'] = array('type' => 'string',   'required' => true,  'default' => '');

$config->gitlab->form->user = new stdclass();

$config->gitlab->form->user->create = common::formConfig('gitlab', 'createUser');
$config->gitlab->form->user->create['account']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['name']             = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['username']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['email']            = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['password']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['password_repeat']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['projects_limit']   = array('type' => 'int', 'required' => false, 'default' => '100000');
$config->gitlab->form->user->create['can_create_group'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->create['external']         = array('type' => 'string', 'required' => false, 'default' => '');

$config->gitlab->form->user->edit = common::formConfig('gitlab', 'editUser');
$config->gitlab->form->user->edit['id']               = array('type' => 'int', 'required' => true);
$config->gitlab->form->user->edit['account']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['name']             = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['username']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['email']            = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['password']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['password_repeat']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->gitlab->form->user->edit['projects_limit']   = array('type' => 'int', 'required' => false, 'default' => '100000');
$config->gitlab->form->user->edit['can_create_group'] = array('type' => 'string', 'required' => false, 'default' => '0');
$config->gitlab->form->user->edit['external']         = array('type' => 'string', 'required' => false, 'default' => '0');
