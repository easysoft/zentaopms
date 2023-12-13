<?php
global $app;
$config->jenkins->form = new stdclass();

$config->jenkins->form->create = array();
$config->jenkins->form->create['type']        = array('type' => 'string',   'required' => true,  'default' => 'jenkins');
$config->jenkins->form->create['name']        = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->jenkins->form->create['url']         = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->jenkins->form->create['account']     = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->jenkins->form->create['token']       = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->jenkins->form->create['password']    = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->jenkins->form->create['createdBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->jenkins->form->create['createdDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());
$config->jenkins->form->create['private']     = array('type' => 'string',   'required' => false, 'default' => uniqid());

$config->jenkins->form->edit = array();
$config->jenkins->form->edit['name']       = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->jenkins->form->edit['url']        = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->jenkins->form->edit['account']    = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->jenkins->form->edit['token']      = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->jenkins->form->edit['password']   = array('type' => 'string',   'required' => false, 'default' => '', 'filter' => 'trim');
$config->jenkins->form->edit['editedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->jenkins->form->edit['editedDate'] = array('type' => 'datetime', 'required' => false, 'default' => helper::now());
