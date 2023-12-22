<?php
$config->sso->form = new stdclass();

$config->sso->form->createUser = array();
$config->sso->form->createUser['account']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->sso->form->createUser['realname'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->sso->form->createUser['email']    = array('type' => 'string', 'required' => false, 'default' => '');
$config->sso->form->createUser['gender']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->sso->form->createUser['ranzhi']   = array('type' => 'string', 'required' => false, 'default' => '');
