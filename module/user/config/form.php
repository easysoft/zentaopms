<?php
$config->user->form = new stdclass();
$config->user->form->batchCreate = common::formConfig('user', 'batchCreate');
$config->user->form->batchCreate['dept']     = array('required' => false, 'type' => 'int',    'default' => 0);
$config->user->form->batchCreate['company']  = array('required' => false, 'type' => 'int',    'default' => 0);
$config->user->form->batchCreate['new']      = array('required' => false, 'type' => 'int',    'default' => 0);
$config->user->form->batchCreate['newName']  = array('required' => false, 'type' => 'string', 'default' => '');
$config->user->form->batchCreate['account']  = array('required' => true,  'type' => 'string', 'default' => '', 'base' => true);
$config->user->form->batchCreate['realname'] = array('required' => true,  'type' => 'string', 'default' => '');
$config->user->form->batchCreate['visions']  = array('required' => true,  'type' => 'string', 'default' => $config->vision);
$config->user->form->batchCreate['role']     = array('required' => false, 'type' => 'string', 'default' => '');
$config->user->form->batchCreate['group']    = array('required' => false, 'type' => 'string', 'default' => '', 'filter' => 'join');
$config->user->form->batchCreate['email']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->user->form->batchCreate['gender']   = array('required' => false, 'type' => 'string', 'default' => '');
$config->user->form->batchCreate['password'] = array('required' => true,  'type' => 'string', 'default' => '');
$config->user->form->batchCreate['join']     = array('required' => false, 'type' => 'date',   'default' => null);
