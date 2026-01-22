<?php
$now = helper::now();

$config->pipeline->form = new stdclass();

$config->pipeline->form->create = array();
$config->pipeline->form->create['name']            = array('type' => 'string', 'required' => true);
$config->pipeline->form->create['engine']          = array('type' => 'string', 'required' => true);
$config->pipeline->form->create['repo']            = array('type' => 'int',    'required' => true);
$config->pipeline->form->create['reference']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->pipeline->form->create['frame']           = array('type' => 'string', 'required' => false, 'default' => '');
$config->pipeline->form->create['product']         = array('type' => 'int',    'required' => false, 'default' => 0);
$config->pipeline->form->create['sonarqubeServer'] = array('type' => 'int',    'required' => false, 'default' => 0);
$config->pipeline->form->create['projectKey']      = array('type' => 'string', 'required' => false, 'default' => '');
$config->pipeline->form->create['jkServer']        = array('type' => 'int',    'required' => false, 'default' => 0);
$config->pipeline->form->create['jkTask']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->pipeline->form->create['createdDate']     = array('type' => 'string', 'required' => false, 'default' => $now);

$config->pipeline->form->edit = array();
$config->pipeline->form->edit['name']            = array('type' => 'string', 'required' => true);
$config->pipeline->form->edit['engine']          = array('type' => 'string', 'required' => true);
$config->pipeline->form->edit['repo']            = array('type' => 'int',    'required' => true);
$config->pipeline->form->edit['reference']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->pipeline->form->edit['frame']           = array('type' => 'string', 'required' => false, 'default' => '');
$config->pipeline->form->edit['triggerType']     = array('type' => 'array',  'required' => false, 'default' => array(), 'filter' => 'join');
$config->pipeline->form->edit['svnDir']          = array('type' => 'array',  'required' => false, 'default' => array(), 'filter' => 'join');
$config->pipeline->form->edit['product']         = array('type' => 'int',    'required' => false, 'default' => 0);
$config->pipeline->form->edit['sonarqubeServer'] = array('type' => 'int',    'required' => false, 'default' => 0);
$config->pipeline->form->edit['projectKey']      = array('type' => 'string', 'required' => false, 'default' => '');
$config->pipeline->form->edit['comment']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->pipeline->form->edit['triggerActions']  = array('type' => 'array',  'required' => false, 'default' => array(), 'filter' => 'join');
$config->pipeline->form->edit['atDay']           = array('type' => 'array',  'required' => false, 'default' => array(), 'filter' => 'join');
$config->pipeline->form->edit['atTime']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->pipeline->form->edit['jkServer']        = array('type' => 'int',    'required' => false, 'default' => 0);
$config->pipeline->form->edit['jkTask']          = array('type' => 'string', 'required' => false, 'default' => '');
$config->pipeline->form->edit['paramName']       = array('type' => 'array',  'required' => false, 'default' => array());
$config->pipeline->form->edit['paramValue']      = array('type' => 'array',  'required' => false, 'default' => array());
$config->pipeline->form->edit['autoRun']         = array('type' => 'int',    'required' => false, 'default' => 1);
$config->pipeline->form->edit['editedDate']      = array('type' => 'string', 'required' => false, 'default' => $now);
