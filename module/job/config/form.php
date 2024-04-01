<?php
$now = helper::now();

global $app;
$config->job->form = new stdclass();

$config->job->form->create = array();
$config->job->form->create['name']            = array('type' => 'string', 'required' => true);
$config->job->form->create['engine']          = array('type' => 'string', 'required' => true);
$config->job->form->create['repo']            = array('type' => 'int',    'required' => true);
$config->job->form->create['reference']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['gitfoxpipeline']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['frame']           = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['triggerType']     = array('type' => 'string', 'required' => false);
$config->job->form->create['svnDir']          = array('type' => 'array',  'required' => false, 'default' => array());
$config->job->form->create['product']         = array('type' => 'int',    'required' => false, 'default' => 0);
$config->job->form->create['sonarqubeServer'] = array('type' => 'int',    'required' => false, 'default' => 0);
$config->job->form->create['projectKey']      = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['comment']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['atDay']           = array('type' => 'array',  'required' => false, 'default' => '');
$config->job->form->create['atTime']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['jkServer']        = array('type' => 'int',    'required' => false, 'default' => 0);
$config->job->form->create['jkTask']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['paramName']       = array('type' => 'array',  'required' => false, 'default' => array());
$config->job->form->create['paramValue']      = array('type' => 'array',  'required' => false, 'default' => array());
$config->job->form->create['createdBy']       = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->job->form->create['createdDate']     = array('type' => 'string', 'required' => false, 'default' => $now);

$config->job->form->edit = array();
$config->job->form->edit['name']            = array('type' => 'string', 'required' => true);
$config->job->form->edit['engine']          = array('type' => 'string', 'required' => true);
$config->job->form->edit['repo']            = array('type' => 'int',    'required' => true);
$config->job->form->edit['reference']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->edit['gitfoxpipeline']  = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->edit['frame']           = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->edit['triggerType']     = array('type' => 'string', 'required' => false);
$config->job->form->edit['svnDir']          = array('type' => 'array',  'required' => false, 'default' => array());
$config->job->form->edit['product']         = array('type' => 'int',    'required' => false, 'default' => 0);
$config->job->form->edit['sonarqubeServer'] = array('type' => 'int',    'required' => false, 'default' => 0);
$config->job->form->edit['projectKey']      = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->edit['comment']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->edit['atDay']           = array('type' => 'array',  'required' => false, 'default' => '');
$config->job->form->edit['atTime']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->edit['jkServer']        = array('type' => 'int',    'required' => false, 'default' => 0);
$config->job->form->edit['jkTask']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->edit['paramName']       = array('type' => 'array',  'required' => false, 'default' => array());
$config->job->form->edit['paramValue']      = array('type' => 'array',  'required' => false, 'default' => array());
$config->job->form->edit['editedBy']        = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->job->form->edit['editedDate']      = array('type' => 'string', 'required' => false, 'default' => $now);
