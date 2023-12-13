<?php
global $app;
$config->gitea->form = new stdclass();
$config->gitea->form->create = array();
$config->gitea->form->create['type']        = array('type' => 'string', 'required' => true,  'default' => 'gitea');
$config->gitea->form->create['name']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitea->form->create['url']         = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitea->form->create['token']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitea->form->create['createdBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->gitea->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
$config->gitea->form->create['private']     = array('type' => 'string', 'required' => false, 'default' => uniqid());

$config->gitea->form->edit = array();
$config->gitea->form->edit['name']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitea->form->edit['url']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitea->form->edit['token']      = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->gitea->form->edit['editedBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->gitea->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
