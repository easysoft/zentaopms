<?php
global $app;
$config->sonarqube->form = new stdclass();
$config->sonarqube->form->create = array();
$config->sonarqube->form->create['type']        = array('type' => 'string', 'required' => true,  'default' => 'sonarqube');
$config->sonarqube->form->create['name']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->sonarqube->form->create['url']         = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->sonarqube->form->create['account']     = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->sonarqube->form->create['password']    = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->sonarqube->form->create['createdBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->sonarqube->form->create['createdDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());
$config->sonarqube->form->create['private']     = array('type' => 'string', 'required' => false, 'default' => uniqid());

$config->sonarqube->form->edit = array();
$config->sonarqube->form->edit['name']       = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->sonarqube->form->edit['url']        = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->sonarqube->form->edit['account']    = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->sonarqube->form->edit['password']   = array('type' => 'string', 'required' => true,  'default' => '', 'filter' => 'trim');
$config->sonarqube->form->edit['editedBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->sonarqube->form->edit['editedDate'] = array('type' => 'string', 'required' => false, 'default' => helper::now());

$config->sonarqube->form->createProject['projectName'] = array('type' => 'string', 'required' => true);
$config->sonarqube->form->createProject['projectKey']  = array('type' => 'string', 'required' => true);
