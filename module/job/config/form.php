<?php
$now = helper::now();

global $app;
$config->job->form = new stdclass();

$config->job->form->create = array();
$config->job->form->create['name']            = array('type' => 'string', 'required' => true);
$config->job->form->create['engine']          = array('type' => 'string', 'required' => true);
$config->job->form->create['repo']            = array('type' => 'int',    'required' => true);
$config->job->form->create['frame']           = array('type' => 'string', 'required' => true);
$config->job->form->create['triggerType']     = array('type' => 'string', 'required' => true);
$config->job->form->create['svnDir']          = array('type' => 'array',  'required' => false, 'default' => array());
$config->job->form->create['product']         = array('type' => 'int',    'required' => false, 'default' => 0);
$config->job->form->create['sonarqubeServer'] = array('type' => 'int',    'required' => false, 'default' => 0);
$config->job->form->create['projectKey']      = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['comment']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['atDay']           = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['atTime']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['jkServer']        = array('type' => 'int',    'required' => false, 'default' => 0);
$config->job->form->create['jkTask']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->job->form->create['paramName']       = array('type' => 'array',  'required' => false, 'default' => array());
$config->job->form->create['paramValue']      = array('type' => 'array',  'required' => false, 'default' => array());
$config->job->form->create['createdBy']       = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->job->form->create['createdDate']     = array('type' => 'string', 'required' => false, 'default' => $now);
