<?php
declare(strict_types=1);

global $app;
$config->artifactrepo->form = new stdclass();
$config->artifactrepo->form->create = array();
$config->artifactrepo->form->create['name']        = array('required' => true,  'type' => 'string');
$config->artifactrepo->form->create['serverID']    = array('required' => true,  'type' => 'int');
$config->artifactrepo->form->create['repoName']    = array('required' => true,  'type' => 'string');
$config->artifactrepo->form->create['type']        = array('required' => true,  'type' => 'string');
$config->artifactrepo->form->create['status']      = array('required' => true,  'type' => 'string');
$config->artifactrepo->form->create['products']    = array('required' => false, 'type' => 'array',  'default' => array(), 'filter' => 'join');
$config->artifactrepo->form->create['format']      = array('required' => false, 'type' => 'string', 'default' => '');
$config->artifactrepo->form->create['createdBy']   = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->artifactrepo->form->create['editedBy']    = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->artifactrepo->form->create['createdDate'] = array('required' => false, 'type' => 'string', 'default' => helper::now());

$config->artifactrepo->form->edit = array();
$config->artifactrepo->form->edit['name']       = array('required' => true,  'type' => 'string');
$config->artifactrepo->form->edit['products']   = array('required' => false, 'type' => 'array',  'default' => array(), 'filter' => 'join');
$config->artifactrepo->form->edit['editedBy']   = array('required' => false, 'type' => 'string', 'default' => $app->user->account);
$config->artifactrepo->form->edit['editedDate'] = array('required' => false, 'type' => 'string', 'default' => helper::now());
