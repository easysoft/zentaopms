<?php
global $app;
$config->gitfox->form = new stdclass();
$config->gitfox->form->create = array();
$config->gitfox->form->create['appType']     = array('type' => 'string',   'required' => true,  'default' => 'gitfox');
$config->gitfox->form->create['type']        = array('type' => 'string',   'required' => true,  'default' => 'external');
$config->gitfox->form->create['name']        = array('type' => 'string',   'required' => true, 'default' => '', 'filter' => 'trim');
$config->gitfox->form->create['url']         = array('type' => 'string',   'required' => true, 'default' => '', 'filter' => 'trim');
$config->gitfox->form->create['token']       = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitfox->form->create['createdBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->gitfox->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
$config->gitfox->form->create['private']     = array('type' => 'string',   'required' => false, 'default' => uniqid());

$config->gitfox->form->edit = array();
$config->gitfox->form->edit['name']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitfox->form->edit['url']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitfox->form->edit['token']      = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitfox->form->edit['editedBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->gitfox->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
